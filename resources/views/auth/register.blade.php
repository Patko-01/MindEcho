<x-layout>
    <div class="container form-container p-5 mt-5 mb-5">
        <h2>Register</h2>
        <form id="register-form" action="{{ route('register') }}" method="POST" novalidate>
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="mb-3 mt-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" value="{{ old('name') }}" name="name" class="form-control" id="name" aria-describedby="personNameHelp" required>
                <div class="field-error text-danger small mt-1" data-for="name"></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" value="{{ old('email') }}" name="email" class="form-control" id="email" aria-describedby="personEmailHelp" required>
                <div class="field-error text-danger small mt-1" data-for="email"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" aria-describedby="personPasswordHelp" required>
                <div class="field-error text-danger small mt-1" data-for="password"></div>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" aria-describedby="personPasswordHelp" required>
                <div class="field-error text-danger small mt-1" data-for="password_confirmation"></div>
            </div>

            <button id="register-submit" type="submit" class="midBtn btn btn-dark w-100">Register</button>

            <div class="text-center mt-3">
                <small class="text-muted">Already have an account? <a href="{{ route('show.login') }}" class="link-primary">Sign in</a></small>
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
