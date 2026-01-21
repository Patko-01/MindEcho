<x-layout>
    <div class="container p-5 w-50">
        <h2>Edit Profile</h2>
        <form id="profile-edit-form" action="{{ route('profile.update', $user->id) }}" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="mb-3 mt-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" value="{{ old('name', $user->name) }}" name="name" class="form-control" id="name" aria-describedby="personNameHelp" required>
                <div class="field-error text-danger small mt-1" data-for="name"></div>
            </div>
            <div class="mb-3">
                <label for="loginPersonEmail" class="form-label">Email</label>
                <input type="email" value="{{ old('email', $user->email) }}" name="email" autocomplete="off" class="form-control" id="loginPersonEmail" aria-describedby="personEmailHelp" disabled>
            </div>
            <div class="mb-3">
                <label for="loginPersonPassword" class="form-label">New password (optional)</label>
                <input type="password" name="password" class="form-control" id="loginPersonPassword" aria-describedby="personNewOptionalPasswordHelp">
                <div class="field-error text-danger small mt-1" data-for="password"></div>
            </div>

            <button id="profile-submit" type="submit" class="midBtn btn btn-dark w-100" aria-label="Save changes">Save Changes</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Don't like your account?</small>
        </div>

        <div class="text-center">
            <form id="profile-delete-form" action="{{ route('profile.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline link-primary">Delete account</button>
            </form>
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
    </div>
</x-layout>
