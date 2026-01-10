<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Models;
use App\Models\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response as HttpResponse;

class DashboardController extends Controller
{
    public function index(Request $request): Factory|View
    {
        $user = $request->user();

        $entries = Entry::query()->where('user_id', $user->id)->latest()->get();
        $data = $entries->groupBy('tag');

        $models = Models::pluck('name');

        $sessionModel = session('model');
        $usedModel = $sessionModel && $models->contains($sessionModel)
            ? $sessionModel
            : ($models->first() ?? 'llama3.2:latest');

        return view('pages.dashboard', compact('data', 'models', 'usedModel'));
    }

    public function newEntry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string',
            'tag' => 'required|string',
            'model' => 'nullable|string|exists:models,name',
        ]);

        $tag = $data['tag'];
        $model = $data['model'] ?? 'llama3.2:latest';

        if ($data['tag'] != "Thoughts") {
            $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $data['content'], 'tag' => $data['tag'], 'content' => $data['content']]);
            return redirect()->route('dashboard')
                ->with('tag', $tag)
                ->with('model', $model)
                ->with('entry', $entry->only(['id', 'entry_title', 'content']));
        }

        $ollamaTitleResponse = Http::post('http://localhost:11434/api/generate', [
            'model' => 'llama3.2:latest', // Use a consistent model for title generation
            'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to create a short, clear title (maximum 6 words) that captures the core idea of the journal entry. No extra words. In case the entry is nonsensical or empty, respond with "Untitled Entry".',
            'prompt' => $data['content'],
            'stream' => false,
        ]);
        $title = trim($ollamaTitleResponse->json('response'));
        if (empty($title)) {
            $title = 'Untitled Entry';
        }
        $title = trim($title, "\" \n\t.");

        $ollamaResponse = Http::post('http://localhost:11434/api/generate', [
            'model' => $model,
            'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to ask one thoughtful, open-ended question that helps the user reflect deeper on their emotions, values, needs, or motivations. No advices. No explanations. Only the question, no extra words.',
            'prompt' => $data['content'],
            'stream' => false,
        ]);
        $question = trim($ollamaResponse->json('response'));
        if (empty($question)) {
            $question = 'What feels most important about this moment?';
        }

        $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $title, 'tag' => $tag, 'content' => $data['content']]);
        Response::create(['entry_id' => $entry->id, 'content' => $question]);

        $payload = [
            'id' => $entry->id,
            'entry_title' => $title,
            'content' => $data['content'],
            'aiQuestion' => $question,
            'created_at' => $entry->created_at,
        ];

        return redirect()->route('dashboard')
            ->with('tag', $tag)
            ->with('model', $model)
            ->with('entry', $payload);
    }

    public function destroy(Request $request): HttpResponse
    {
        $data = $request->validate([
            'entry_id' => 'required|integer|exists:entries,id',
        ]);

        $entry = Entry::where('id', $data['entry_id'])->where('user_id', $request->user()->id)->firstOrFail();

        $entry->response()->delete();
        $entry->delete();

        return response()->noContent();
    }
}
