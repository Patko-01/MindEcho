<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('profile.edit');
    }
    public function update(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $validated['name'],
        ];

        // Only set the password when the user actually submitted a non-empty password
        if ($request->filled('password')) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()->route('home')->with('status', 'Profile updated successfully.');
    }
    public function destroy(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Profile deleted successfully.');
    }
}
