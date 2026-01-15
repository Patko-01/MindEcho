<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index (): Factory|View
    {
        return view('welcome');
    }

    public function about (): Factory|View
    {
        return view('pages.about');
    }

    public function contact(): Factory|View
    {
        return view('pages.contact');
    }
}
