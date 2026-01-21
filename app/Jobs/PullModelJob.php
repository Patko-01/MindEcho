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

class PullModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $modelName;
    protected string $modelDescription;

    public function __construct(string $modelName, string $modelDescription)
    {
        $this->modelName = $modelName;
        $this->modelDescription = $modelDescription;
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $ollamaHost = config('services.ollama.host', 'http://127.0.0.1:11434');

        $response = Http::timeout(0)->post($ollamaHost . '/api/pull', [
            'name' => $this->modelName,
        ]);

        if ($response->failed()) {
            throw new RuntimeException("Ollama pull failed with status " . $response->status());
        }

        $lines = explode("\n", trim($response->body()));

        foreach ($lines as $line) {
            if ($line === '') {
                continue;
            }

            $json = json_decode($line, true);
            if (!is_array($json)) {
                continue;
            }

            if (isset($json['error'])) {
                throw new RuntimeException("Ollama pull failed " . $json['error']);
            }
        }

        AiModel::firstOrCreate(
            ['name' => $this->modelName],
            ['description' => $this->modelDescription]
        );
    }
}
