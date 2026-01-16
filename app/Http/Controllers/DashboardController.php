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
use Illuminate\Http\Client\ConnectionException;

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
        $model = $data['model'];

        if ($data['tag'] != "Thoughts") {
            $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $data['content'], 'tag' => $data['tag'], 'content' => $data['content']]);
            return redirect()->route('dashboard')
                ->with('tag', $tag)
                ->with('model', $model)
                ->with('entry', $entry->only(['id', 'entry_title', 'content', 'tag']));
        }

        $ollamaHost = config('services.ollama.host', 'http://127.0.0.1:11434');
        $title = 'Untitled Entry';
        try {
            $ollamaTitleResponse = Http::post($ollamaHost . '/api/generate', [
                'model' => 'llama3.2:latest',
                'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to create a short, clear title (maximum 6 words) that captures the core idea of the journal entry. No extra words. In case the entry is nonsensical or empty, respond with "Untitled Entry".',
                'prompt' => $data['content'],
                'stream' => false,
            ]);
            $titleRaw = $ollamaTitleResponse->json('response');
            $maybeTitle = is_string($titleRaw) ? trim($titleRaw) : '';
            if (!empty($maybeTitle)) {
                $title = trim($maybeTitle, "\" \n\t.");
            }
        } catch (ConnectionException $e) {
            return redirect()->route('dashboard')
                ->with('tag', $tag)
                ->with('model', $model)
                ->with('error', $e->getMessage());
        }

        $question = 'What feels most important about this moment?';
        try {
            $ollamaResponse = Http::post($ollamaHost . '/api/generate', [
                'model' => $model,
                'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to ask one thoughtful, open-ended question that helps the user reflect deeper on their emotions, values, needs, or motivations. No advices. No explanations. Only the question, no extra words.',
                'prompt' => $data['content'],
                'stream' => false,
            ]);
            $questionRaw = $ollamaResponse->json('response');
            $maybeQuestion = is_string($questionRaw) ? trim($questionRaw) : '';
            if (!empty($maybeQuestion)) {
                $question = $maybeQuestion;
            }
        } catch (ConnectionException $e) {
            return redirect()->route('dashboard')
                ->with('tag', $tag)
                ->with('model', $model)
                ->with('error', $e->getMessage());
        }

        $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $title, 'tag' => $tag, 'content' => $data['content']]);
        Response::create(['entry_id' => $entry->id, 'model_name' => $model,'content' => $question]);

        $payload = [
            'id' => $entry->id,
            'entry_title' => $title,
            'content' => $data['content'],
            'aiQuestion' => $question,
            'created_at' => $entry->created_at,
            'tag' => 'Thoughts',
            'model' => $model,
        ];

        return redirect()->route('dashboard')
            ->with('tag', $tag)
            ->with('model', $model)
            ->with('entry', $payload);
    }

    public function showEntry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entry_id' => 'required|integer|exists:entries,id',
        ]);

        $entry = Entry::where('id', $data['entry_id'])->where('user_id', $request->user()->id)->firstOrFail();
        $response = Response::where('entry_id', $entry->id)->firstOrFail();

        $payload = [
            'id' => $entry->id,
            'entry_title' => $entry->entry_title,
            'content' => $entry->content,
            'aiQuestion' => $response->content,
            'created_at' => $entry->created_at,
            'tag' => $entry->tag,
            'model' => $response->model_name,
        ];

        return redirect()->route('dashboard')
            ->with('tag', session('tag', $entry->tag))
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
