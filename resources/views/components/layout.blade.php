<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MindEcho</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap.js'])
</head>
<body>
@if(session('success'))
    <div class="alert alert-success text-center" role="alert">
        {{ session('success') }}
    </div>
@endif
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <a class="navbar-brand mb-0" href="{{ route('home') }}">Mindecho</a>
            @auth
                <div class="d-inline-flex flex-nowrap align-items-center ms-2">
                    <span class="me-2 mb-0">Welcome, {{ optional(auth()->user())->name }}!</span>
                    <a class="nav-link p-0 d-inline-block" href="{{ route('show.profile.edit') }}" aria-label="Edit profile">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </a>
                </div>
            @endauth
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About us</a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @can('access-admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin') }}">Admin</a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn authBtn ms-1">Logout</button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="btn authBtn ms-1" href="{{ route('show.login') }}">Sign in</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main>
    {{ $slot }}
</main>

<div class="container">
    <footer class="row row-cols-1 row-cols-md-5 py-5 border-top">
        <div class="col mb-3">
            <a href="{{ route('home') }}" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none" aria-label="Bootstrap">
                <span class="navbar-brand">Mindecho</span>
            </a>
            <p class="text-body-secondary">© 2025 MindEcho. All rights reserved.</p>
        </div>
        <div class="col mb-3"></div>
        <div class="col mb-3"></div>
        <div class="col mb-3"><h5>Quick Links</h5>
            <ul class="nav flex-column">
                @guest
                    <li class="nav-item mb-2"><a href="{{ route('show.login') }}" class="nav-link p-0 text-body-secondary">Sign in</a></li>
                @endguest

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline mb-2">
                       @csrf
                       <button type="submit" class="nav-link p-0 text-body-secondary">Logout</button>
                    </form>
                @endauth

                <li class="nav-item mb-2"><a href="{{ route('about') }}" class="nav-link p-0 text-body-secondary">About us</a></li>

                @auth
                    <li class="nav-item mb-2"><a href="{{ route('dashboard') }}" class="nav-link p-0 text-body-secondary">Dashboard</a></li>
                @endauth
            </ul>
        </div>
        <div class="col mb-3"><h5>Contact</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="mailto:support@mindecho.ai" class="nav-link p-0 text-body-secondary">patriksam258@gmail.com</a></li>
                @auth
                    <li class="nav-item mb-2"><a href="{{ route('contact') }}" class="nav-link p-0 text-body-secondary">Support</a></li>
                @endauth
            </ul>
        </div>
    </footer>
</div>
</body>
</html>
