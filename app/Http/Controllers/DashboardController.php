<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\AiModel;
use App\Models\Note;
use App\Models\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class DashboardController extends Controller
{
    private function getConversation(int $entryId): Collection
    {
        $notes = Note::where('entry_id', $entryId)->orderBy('created_at')->get();
        $responses = Response::where('entry_id', $entryId)->orderBy('created_at')->get();

        $conversation = collect();
        foreach ($notes as $i => $note) {
            $conversation->push([
                'note' => $note->content,
                'model_name' => $responses[$i]->model_name ?? null,
                'response' => $responses[$i]->content ?? null,
            ]);
        }

        return $conversation;
    }

    private function buildConversationPrompt(int $entryId): string
    {
        $notes = Note::where('entry_id', $entryId)->orderBy('created_at')->take(10)->get()->reverse();
        $responses = Response::where('entry_id', $entryId)->orderBy('created_at')->take(10)->get()->reverse();

        $prompt = '';

        foreach ($notes as $index => $note) {
            $prompt .= "User ({$note->created_at->format('Y-m-d H:i')}):\n";
            $prompt .= trim($note->content) . "\n\n";

            if (isset($responses[$index])) {
                $prompt .= "Assistant:\n";
                $prompt .= trim($responses[$index]->content) . "\n\n";
            }
        }

        return trim($prompt);
    }

    private function generateTitle(string $content, string $ollamaHost): string
    {
        $firstModel = AiModel::orderBy('id')->firstOrFail();

        try {
            $response = Http::timeout(0)->post($ollamaHost . '/api/generate', [
                'model' => $firstModel->name,
                'system' => 'You are a journaling assistant. The user will provide a journal entry. Your tasks is to create a short, clear title (maximum 6 words) that captures the core idea of the journal entry. No extra words. In case the entry is nonsensical or empty, respond with "Untitled Entry".',
                'prompt' => $content,
                'stream' => false,
            ]);

            return trim($response->json('response') ?: 'Untitled Entry', "\" \n\t.");
        } catch (Throwable) {
            return 'Untitled Entry';
        }
    }

    private function reflectionSystemPrompt(): string
    {
        return <<<PROMPT
        You are a journaling assistant helping a user reflect over time.

        You will receive messages from the user which may include:
        - Journal entries
        - Previous reflections
        - Casual comments or jokes

        Your task:
        - Ask ONE thoughtful, open-ended question about the user’s feelings, values, or experiences.
        - If the user writes something unrelated or playful, you may lightly acknowledge it in the question, but continue reflecting.
        - Do NOT give advice, explanations, or instructions.
        - Output ONLY the question.
        PROMPT;
    }

    public function index(Request $request): Factory|View
    {
        $user = $request->user();

        $entries = Entry::query()->where('user_id', $user->id)->latest()->get();
        $data = $entries->groupBy('tag');

        $models = AiModel::pluck('name');

        $usedModel = session('model') && $models->contains(session('model'))
            ? session('model')
            : ($models->first() ?? 'llama3.2:latest');

        return view('pages.dashboard', compact('data', 'models', 'usedModel'));
    }

    public function newEntry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string',
            'tag' => 'required|string',
            'model' => 'required|string|exists:ai_models,name',
            'old_entry_id' => 'nullable|integer|exists:entries,id',
        ]);

        $tag = $data['tag'];
        $model = $data['model'];

        if ($data['tag'] != "Thoughts") {
            $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $data['content'], 'tag' => $data['tag']]);
            Note::create(['entry_id' => $entry->id, 'content' => $data['content']]);
            return redirect()->route('dashboard')
                ->with('tag', $tag)
                ->with('model', $model)
                ->with('entry', $entry->only(['id', 'entry_title', 'content', 'tag']));
        }

        $ollamaHost = config('services.ollama.host', 'http://127.0.0.1:11434');

        if (isset($data['old_entry_id'])) {
            $entry = Entry::where('id', $data['old_entry_id'])->where('user_id', $request->user()->id)->firstOrFail();
            $title = $entry->entry_title;

            $prompt = $this->buildConversationPrompt($data['old_entry_id']);
        } else {
            $title = $this->generateTitle($data['content'], $ollamaHost);
            $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $title, 'tag' => $tag]);

            $prompt = '';
        }

        $prompt .= "\n\nUser (" . now()->format('Y-m-d H:i') . "):\n";
        $prompt .= trim($data['content']);

        try {
            $ollamaResponse = Http::timeout(0)->post($ollamaHost . '/api/generate', [
                'model' => $model,
                'system' => $this->reflectionSystemPrompt(),
                'prompt' => $prompt,
                'stream' => false,
            ]);
        } catch (ConnectionException $e) {
            return redirect()->route('dashboard')
                ->with('tag', $tag)
                ->with('model', $model)
                ->with('error', $e->getMessage());
        }

        $question = trim($ollamaResponse->json('response') ?? 'What feels most important about this moment?');

        Note::create(['entry_id' => $entry->id, 'content' => $data['content']]);
        Response::create(['entry_id' => $entry->id, 'model_name' => $model,'content' => $question]);

        $payload = [
            'id' => $entry->id,
            'entry_title' => $title,
            'conversation' => $this->getConversation($entry->id),
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

        $payload = [
            'id' => $entry->id,
            'entry_title' => $entry->entry_title,
            'conversation' => $this->getConversation($entry->id),
            'created_at' => $entry->created_at,
            'tag' => $entry->tag,
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
        $entry->note()->delete();
        $entry->delete();

        return response()->noContent();
    }
}
