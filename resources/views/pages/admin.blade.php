<x-layout>
    <div class="container form-container p-5 mt-5 mb-5">
        <h2 class="mb-4">Add model</h2>
        <form action="{{ route('admin.addModel') }}" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->
            <div class="mb-3">
                <label for="modelName" class="form-label">Model name</label>
                <input type="text" id="modelName" placeholder="example.ver:latest" name="modelName" class="form-control" aria-describedby="modelNameHelp" maxlength="255" required>
            </div>
            <div class="mb-3">
                <label for="modelDescription" class="form-label">Model description</label>
                <textarea name="modelDescription" id="modelDescription" class="form-control" placeholder="Enter model description" aria-describedby="modelDescriptionHelp" maxlength="255" rows="5" required></textarea>
            </div>
            <button type="submit" class="midBtn btn btn-dark w-100">Submit</button>

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
