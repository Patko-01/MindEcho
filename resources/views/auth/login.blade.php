<x-layout>
    <div class="container form-container p-5 mt-5 mb-5">
        <h2>Login</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="mb-3 mt-4">
                <label for="loginPersonEmail1" class="form-label">Email</label>
                <input type="email" value="{{ old('email') }}" name="email" class="form-control" id="loginPersonEmail1" aria-describedby="personEmailHelp" required>
            </div>
            <div class="mb-3">
                <label for="loginPersonPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="loginPersonPassword1" aria-describedby="personPasswordHelp" required>
            </div>

            <button type="submit" class="midBtn btn btn-dark w-100">Login</button>

            <div class="text-center mt-3">
                <small class="text-muted">Don't have an account? <a href="{{ route('show.register') }}" class="link-primary">Sign up</a></small>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</x-layout>
