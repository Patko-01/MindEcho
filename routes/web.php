<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
});

Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile/{id}', 'index')->name('show.profile.edit');
    Route::post('/profile/{id}', 'update')->name('profile.update');
    Route::delete('/profile/{id}', 'destroy')->name('profile.destroy');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth')->controller(DashboardController::class)->group(function () {
    Route::post('/dashboard', 'newEntry')->name('dashboard.newEntry');
    Route::delete('/dashboard', 'destroy')->name('dashboard.destroy');
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/dashboard/entry', 'showEntry')->name('dashboard.showEntry');
});

Route::middleware(['auth', 'can:access-admin'])->controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin');
    Route::post('/admin', 'addModel')->name('admin.addModel');
    Route::delete('/admin', 'destroy')->name('admin.destroy');
});


