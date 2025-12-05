<x-layout>
    <div class="container p-5 w-50">
        <h2>Register</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="mb-3 mt-4">
                <label for="loginPersonName1" class="form-label">Name</label>
                <input type="text" value="{{ old('name') }}" name="name" class="form-control" id="loginPersonName1" aria-describedby="personNameHelp" required>
            </div>
            <div class="mb-3">
                <label for="loginPersonEmail1" class="form-label">Email</label>
                <input type="email" value="{{ old('email') }}" name="email" class="form-control" id="loginPersonEmail1" aria-describedby="personEmailHelp" required>
            </div>
            <div class="mb-3">
                <label for="loginPersonPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="loginPersonPassword1" aria-describedby="personPasswordHelp" required>
            </div>
            <div class="mb-3">
                <label for="loginPersonPassword2" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="loginPersonPassword2" aria-describedby="personPasswordHelp" required>
            </div>

            <button type="submit" class="midBtn btn btn-dark w-100">Register</button>

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
