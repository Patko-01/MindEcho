<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Jobs\PullModelJob;

class AdminController extends Controller
{
    public function index(): Factory|View
    {
        return view('pages.admin');
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
}
