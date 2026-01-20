<x-layout>
    <div class="container form-container p-3 mt-5 mb-5">
        <h2 class="mb-3">Add model</h2>
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
                <div class="alert mt-3 p-0">
                    <ul class="p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li class="text-decoration-none list-unstyled text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>

        <div class="mt-5 mb-3 d-flex justify-content-between align-items-center">
            <h2>Manage models</h2>
            <input id="modelSearch" class="form-control w-50" type="search" placeholder="Search model" aria-label="SearchModel"/>
        </div>
        <ul class="list-group">
            @foreach($models as $model)
                <li class="list-group-item model p-3 border-1 d-flex justify-content-between align-items-center">
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

        <div class="mt-5 mb-3 d-flex justify-content-between align-items-center">
            <h2>Manage accounts</h2>
            <input id="userSearch" class="form-control w-50" type="search" placeholder="Search user" aria-label="SearchUser"/>
        </div>
        <ul class="list-group">
            @foreach($users as $user)
                <li class="list-group-item user p-3 border-1 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="item-text">{{ $user->name }}</span><br>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    <a class="m-0" href="{{ route('show.profile.edit', $user->id) }}">
                        <button type="submit" class="btn m-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </button>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-layout>
