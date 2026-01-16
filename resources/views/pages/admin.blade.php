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

        <h2 class="mt-5 mb-4">Models</h2>
        <ul class="list-group">
            @foreach($models as $model)
                <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="item-text">{{ $model->name }}</span><br>
                        <small class="text-muted">{{ $model->description }}</small>
                    </div>
                    <form class="m-0 model-delete-form" action="{{ route('admin.destroy') }}" method="POST">
                        @csrf <!-- CSRF token for security (cross site request forgery) -->
                        @method('DELETE')
                        <input type="hidden" name="modelId" value="{{ $model->id }}">
                        <button type="submit" class="btn m-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</x-layout>
