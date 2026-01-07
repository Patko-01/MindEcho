<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function newEntry(Request $request): RedirectResponse
    {
        // Simple behavior: print posted data and redirect back
        $data = $request->all();

        // Echo the data (as the user requested "echo will do"). Note: in practice,
        // browsers will immediately follow the redirect and may not display this output.
        echo '<pre>';
        echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo '</pre>';

        // Also flash the data into the session so it can be shown after redirect.
        session()->flash('last_post', $data);

        Entry::create(['user_id' => $request->user()->id, 'entry_title' => 'so_far_empty', 'tag' => $data['tag'], 'content' => $data['content']]);

        // Then redirect back to the dashboard
        return redirect()->route('dashboard');
    }
}
