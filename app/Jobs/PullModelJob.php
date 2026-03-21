<?php

namespace App\Jobs;

use App\Models\AiModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class PullModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $modelId;

    public function __construct(int $modelId)
    {
        $this->modelId = $modelId;
    }

    /**
     * @throws Throwable
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $aiModel = AiModel::findOrFail($this->modelId);

        $ollamaHost = config('services.ollama.host', 'http://127.0.0.1:11434');

        try {
            $response = Http::timeout(0)->post($ollamaHost . '/api/pull', [
                'name' => $aiModel->name,
            ]);

            if ($response->failed()) {
                $aiModel->update(['status' => 'failed']);
                throw new RuntimeException("Ollama pull failed with status " . $response->status());
            }

            $lines = explode("\n", trim($response->body()));

            foreach ($lines as $line) {
                if ($line === '') continue;

                $json = json_decode($line, true);
                if (!is_array($json)) continue;

                if (isset($json['error'])) {
                    $aiModel->update(['status' => 'failed']);
                    throw new RuntimeException("Ollama pull failed: " . $json['error']);
                }
            }

            $aiModel->update(['status' => 'ready']);
        } catch (Throwable $e) {
            $aiModel->update(['status' => 'failed']);
            throw $e;
        }
    }
}
