<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->middleware('auth')->name('show.profile.edit');
    Route::post('/profile', 'update')->middleware('auth')->name('profile.update');
    Route::delete('/profile', 'destroy')->middleware('auth')->name('profile.destroy');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});
