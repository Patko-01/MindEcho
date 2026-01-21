<x-layout>
    <div class="container form-container p-5 mt-5 mb-5">
        <h2>Login</h2>
        <form id="login-form" action="{{ route('login') }}" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="mb-3 mt-4">
                <label for="loginPersonEmail" class="form-label">Email</label>
                <input type="email" value="{{ old('email') }}" name="email" autocomplete="on" class="form-control" id="loginPersonEmail" aria-describedby="personEmailHelp" required>
                <div class="field-error text-danger small mt-1" data-for="email"></div>
            </div>
            <div class="mb-3">
                <label for="loginPersonPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="loginPersonPassword" autocomplete="on" aria-describedby="personPasswordHelp" required>
                <div class="field-error text-danger small mt-1" data-for="password"></div>
            </div>

            <button id="login-submit" type="submit" class="midBtn btn btn-dark w-100" aria-label="Login">Login</button>

            <div class="text-center mt-3">
                <small class="text-muted">Don't have an account? <a href="{{ route('show.register') }}" class="link-primary">Sign up</a></small>
            </div>

            @if($errors->any())
                <div class="alert mt-3 p-0">
                    <ul class="p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li class="text-decoration-none list-unstyled text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</x-layout>
