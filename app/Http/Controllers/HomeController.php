<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('welcome');
    }

    public function about (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('pages.about');
    }

    public function contact(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('pages.contact');
    }

    public function dashboard(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('pages.dashboard');
    }
}
