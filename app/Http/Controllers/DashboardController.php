<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function newEntry(Request $request): RedirectResponse
    {
        $data = $request->all();

        $systemPrompt = <<<PROMPT
        You are a journaling assistant.

        The user will provide a journal entry.

        Your tasks:
        1. Create a short, clear title (maximum 6 words) that captures the core idea of the journal entry.
        2. Ask one thoughtful, open-ended question that helps the user reflect deeper on their emotions, values, needs, or motivations.

        Rules:
        - Do not give advice.
        - Do not explain.
        - Ask only the question.
        - Do not add extra text.

        Respond ONLY in valid JSON using exactly this structure:
        {
          "title": "...",
          "question": "..."
        }
        PROMPT;

        //$prompt = "User journal entry (time: " . date('Y-m-d H:i:s') . "): " . $data['content']; // variation of prompts so the model doesn't get stuck when similar entries are provided

        $ollamaResponse = Http::post('http://localhost:11434/api/generate', [
            'model' => 'llama3.2:latest',
            'system' => $systemPrompt,
            'prompt' => $data['content'],
            'stream' => false,
        ]);
        $json = json_decode($ollamaResponse->json()['response'] ?? '', true);
        if (!is_array($json)) {
            $json = [];
        }

        $title = $json['title'] ?? 'Untitled Entry';
        $question = $json['question'] ?? 'What feels most important about this moment?';

        $entry = Entry::create(['user_id' => $request->user()->id, 'entry_title' => $title, 'tag' => $data['tag'], 'content' => $data['content']]);
        Response::create(['entry_id' => $entry->id, 'content' => $question]);

        session()->flash('ai_title', $title);
        session()->flash('ai_response', $question);
        return redirect()->route('dashboard');
    }
}
