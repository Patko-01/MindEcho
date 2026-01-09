<?php

namespace App\Http\Controllers;

use App\Models\Entry;
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

        return view('pages.dashboard', [ 'data' => $data ]);
    }

    public function newEntry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string',
            'tag' => 'required|string',
            'model' => 'nullable|string|exists:models,name',
        ]);

        $tag = $data['tag'];

        if ($data['tag'] != "Thoughts") {
            Entry::create(['user_id' => $request->user()->id, 'entry_title' => $data['content'], 'tag' => $data['tag'], 'content' => $data['content']]);
            return redirect()->route('dashboard', ['tag' => $tag]);
        }

        $model = $data['model'] ?? 'llama3.2:latest';

        $ollamaTitleResponse = Http::post('http://localhost:11434/api/generate', [
            'model' => $model,
            'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to create a short, clear title (maximum 6 words) that captures the core idea of the journal entry.',
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
            'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to ask one thoughtful, open-ended question that helps the user reflect deeper on their emotions, values, needs, or motivations. No advice. No explanation. Only the question.',
            'prompt' => $data['content'],
            'stream' => false,
        ]);
        $question = trim($ollamaResponse->json('response'));
        if (empty($question)) {
            $question = 'What feels most important about this moment?';
        }

        $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $title, 'tag' => $data['tag'], 'content' => $data['content']]);
        Response::create(['entry_id' => $entry->id, 'content' => $question]);

        session()->flash('ai_title', $title);
        session()->flash('ai_response', $question);
        return redirect()->route('dashboard', ['tag' => $tag]);
    }

    public function destroy(Request $request): HttpResponse
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'entry_id' => 'required|integer|exists:entries,id',
        ]);

        $entry = Entry::findOrFail($data['entry_id']);

        if ($entry->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $entry->response()->delete();
        $entry->delete();

        return response()->noContent();
    }
}
