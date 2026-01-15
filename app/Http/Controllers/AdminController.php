<?php

namespace App\Http\Controllers;

use App\Models\Models;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(): Factory|View
    {
        return view('pages.admin');
    }

    public function addModel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modelName' => 'required|string|max:255',
            'modelDescription' => 'required|string|max:255',
        ]);

        Models::create(['name' => $data['modelName'], 'description' => $data['modelDescription']]);

        return redirect()->route('admin')->with('success', 'Model added successfully!');
    }
}
