<?php

namespace App\Http\Controllers;

use App\Models\Models;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Jobs\PullModelJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class AdminController extends Controller
{
    public function index(): Factory|View
    {
        $models = Models::all();
        $users = User::all();

        return view('pages.admin')->with('models', $models)->with('users', $users);
    }

    public function addModel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modelName' => 'required|string|max:255|unique:models,name',
            'modelDescription' => 'required|string|max:255',
        ]);

        PullModelJob::dispatch($data['modelName'], $data['modelDescription']);

        return redirect()->route('admin')->with('success', 'Model pull queued. It will be added once downloaded.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modelId' => 'required|integer|exists:models,id',
        ]);

        $model = Models::findOrFail($data['modelId']);

        $ollamaHost = config('services.ollama.host', 'http://127.0.0.1:11434');
        try {
            $response = Http::timeout(0)->delete($ollamaHost . '/api/delete', [
                'name' => $model->name,
            ]);
            if ($response->successful() || $response->json('status') === 'deleted') {
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
