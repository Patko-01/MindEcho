<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('auth.register');
    }
    public function showLogin(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('auth.login');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('home');
    }
    /**
     * @throws ValidationException when login fails due to invalid credentials
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validated)) { //attempts to authenticate the user with the provided credentials
            $request->session()->regenerate(); //prevents session fixation attacks by regenerating the session ID

            return redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'credentials' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate(); //clears the session data
        $request->session()->regenerateToken(); //prevents CSRF attacks by regenerating the CSRF token

        return redirect()->route('show.login');
    }
}
