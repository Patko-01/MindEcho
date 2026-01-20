<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    private function authorizeUserAccess(int $id): ?User
    {
        $user = User::findOrFail($id);

        // If admin (can access-admin), allow editing any user
        if (Gate::allows('access-admin')) {
            return $user;
        }

        if (Auth::user()->getAuthIdentifier() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return $user;
    }

    public function index(int $id): Factory|View
    {
        $user = $this->authorizeUserAccess($id);
        return view('profile.edit', ['user' => $user]);
    }

    public function update(Request $request, int $id): Redirector|RedirectResponse
    {
        $user = $this->authorizeUserAccess($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $user->getAuthIdentifier(),
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $validated['name'],
        ];

        if ($request->filled('password')) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        $route = 'home';

        if (Gate::allows('access-admin')) {
            $route = 'admin';
        }

        return redirect()->route($route);
    }

    public function destroy(Request $request, int $id): Redirector|RedirectResponse
    {
        $user = $this->authorizeUserAccess($id);

        // If deleting own account, logout first
        if (Auth::id() === $user->getAuthIdentifier()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $user->delete();

        $route = 'home';

        if (Gate::allows('access-admin')) {
            $route = 'admin';
        }

        return redirect()->route($route)->with('success', 'Profile deleted successfully.');
    }
}
