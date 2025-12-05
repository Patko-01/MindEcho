<x-layout>
    <div class="container p-5 w-50">
        <h2>Edit Profile</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="mb-3 mt-4">
                <label for="loginPersonName1" class="form-label">Name</label>
                <input type="text" value="{{ old('name', auth()->user()?->name) }}" name="name" class="form-control" id="loginPersonName1" aria-describedby="personNameHelp" required>
            </div>
            <div class="mb-3">
                <label for="loginPersonEmail1" class="form-label">Email</label>
                <input type="email" value="{{ old('email', auth()->user()?->email) }}" name="email" class="form-control" id="loginPersonEmail1" aria-describedby="personEmailHelp" disabled>
            </div>
            <div class="mb-3">
                <label for="loginPersonPassword1" class="form-label">New password (optional)</label>
                <input type="password" name="password" class="form-control" id="loginPersonPassword1" aria-describedby="personNewOptionalPasswordHelp">
            </div>

            <button type="submit" class="midBtn btn btn-dark w-100">Save Changes</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Don't like your account?
                <!-- DELETE form must be outside the update form to avoid nested forms -->
            </small>
        </div>

        <div class="text-center">
            <form action="{{ route('profile.destroy') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline link-primary">Delete account</button>
            </form>
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
    </div>
</x-layout>
