<?php

namespace App\Http\Controllers;

use App\Models\AiModel;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Jobs\PullModelJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function index(): Factory|View
    {
        $models = AiModel::all();
        $users = User::all();

        return view('pages.admin')->with('models', $models)->with('users', $users);
    }

    public function getStatuses()
    {
        $oldIds = Cache::get('oldModelIds', []);
        $currentIds = AiModel::whereIn('status', ['ready', 'failed'])->pluck('id');

        if (empty($oldIds)) {
            Cache::put('oldModelIds', $currentIds);
            return response()->json(['name' => '']);
        }

        $newModel = AiModel::whereIn('id', $currentIds)
            ->whereNotIn('id', $oldIds)
            ->first();

        Cache::put('oldModelIds', $currentIds);

        return response()->json([
            'name' => $newModel?->name ?? '',
            'status' => $newModel?->status ?? ''
        ]);
    }

    public function addModel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modelName' => 'required|string|max:255|unique:ai_models,name',
            'modelDescription' => 'required|string|max:255',
        ]);

        $aiModel = AiModel::updateOrCreate(
            ['name' => $data['modelName']],
            [
                'description' => $data['modelDescription'],
                'status' => 'processing',
            ]
        );

        PullModelJob::dispatch($aiModel->id);

        return redirect()->route('admin')->with('success', 'Model pull queued. It will be added once downloaded.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modelId' => 'required|integer|exists:ai_models,id',
        ]);

        $model = AiModel::findOrFail($data['modelId']);

        if ($model->status == 'failed') {
            $model->delete();
            return redirect()->route('admin')->with('success', 'Model deleted successfully.');
        }

        $ollamaHost = config('services.ollama.host', 'http://127.0.0.1:11434');
        try {
            $response = Http::timeout(0)->delete($ollamaHost . '/api/delete', [
                'name' => $model->name,
            ]);
            if ($response->successful() || $response->getStatusCode() === 404 || $response->json('status') === 'deleted') {
                $model->delete();
                return redirect()->route('admin')->with('success', 'Model deleted successfully.');
            } else {
                return redirect()->route('admin')->with('error', 'Failed to delete model from Ollama. Response: ' . $response->body());
            }
        } catch (ConnectionException $e) {
            return redirect()->route('admin')->with('error', $e->getMessage());
        }
    }
}
