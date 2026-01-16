<x-layout>
    <div class="container mt-5 mb-5 p-5">
        <div class="row gap-5">
        <div class="col-12 col-lg-6">
            <div class="d-flex justify-content-between align-items-center">
                <h2>What is on your mind?</h2>
                <div class="dropdown">
                    <a class="btn dropdown-toggle" id="displayed-model" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        {{ $usedModel }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="AIModelsDropdownButton">
                        @foreach($models as $model)
                            <li>
                                <button type="button" class="btn btn-sm w-100 js-model-toggle"
                                        aria-labelledby="AIModel {{ $model }}">{{ $model }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.newEntry') }}">
                @csrf <!-- CSRF token for security (cross site request forgery) -->
                <div class="input-group rounded-5 border px-3 py-2 flex-column">
                    <div class="d-flex align-items-center">
                        <input name="model" type="hidden" value="{{ $usedModel }}">
                        <textarea id="dashboard-input" name="content"
                                  class="form-control border-0 shadow-none auto-resize-textarea"
                                  placeholder="Start typing…" rows="1" required></textarea>
                    </div>
                    <div class="ms-1 d-flex">
                        <input type="hidden" name="tag" class="form-control form-control-sm w-auto"
                               id="selectedTagInput" value="{{ session('tag', 'Thoughts') }}">
                        <button type="button" class="btn btn-sm text-secondary" id="tagButton">
                            #{{ session('tag', 'Thoughts') }}
                        </button>
                        <button class="btn icon-btn ms-auto" type="submit" aria-labelledby="Submit entry">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                 class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            @php $newEntry = session('entry'); @endphp
            @if ($newEntry && $newEntry['tag'] == "Thoughts")
                <form method="POST" id="removableForm" action="{{ route('dashboard.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="item-box d-inline-flex gap-2 align-items-center p-2">
                        <input name="entry_id" value="{{ $newEntry['id'] }}"
                               class="form-check-input mt-0 submit-on-check" type="checkbox"
                               aria-label="Mark entry '{{ $newEntry['entry_title'] }}' as completed">
                        <span class="item-text">{{ $newEntry['entry_title'] }}</span>
                        <span class="item-text">•</span>
                        <span class="item-date">{{ $newEntry['created_at']->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="card item-box mt-1 p-0">
                        <div class="card-body">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>
                            <p class="card-text">{{ $newEntry['content'] }}</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-anthropic" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M9.218 2h2.402L16 12.987h-2.402zM4.379 2h2.512l4.38 10.987H8.82l-.895-2.308h-4.58l-.896 2.307H0L4.38 2.001zm2.755 6.64L5.635 4.777 4.137 8.64z"/>
                            </svg>
                            <p class="card-text mb-0">{{ $newEntry['aiQuestion'] }}</p>
                            <span class="text-muted float-end">{{ $newEntry['model'] }}</span>
                        </div>
                    </div>
                </form>
            @endif
        </div>

        <!-- Tags library -->
        <div class="col-12 col-lg-5">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Tags library</h2>
                <button type="button" class="btn btn-sm" aria-label="Filter tags">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                         class="bi bi-funnel" viewBox="0 0 16 16">
                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                    </svg>
                </button>
            </div>
            @foreach($data as $tag => $entries)
                <div class="tag mb-3">
                    <div class="category-header d-flex justify-content-between align-items-center"
                         data-bs-toggle="collapse" data-bs-target="#{{ $tag }}"
                         aria-expanded="{{ session('tag') === $tag ? 'true' : 'false' }}">
                        <div class="d-flex align-items-center">
                        <span class="arrow me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                              <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </span>
                            {{ $tag }}
                        </div>
                        <span class="badge rounded-pill">{{ count($entries) }}</span>
                    </div>
                    <div id="{{ $tag }}" class="collapse collapse-content fade-smooth mt-2 {{ session('tag') == $tag ? 'show' : '' }}">
                        @foreach($entries as $entry)
                            <form method="POST" class="tag-{{ $tag }}" action="{{ route('dashboard.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <div class="item-box mb-2 d-flex gap-3 align-items-center {{ session('tag') == "Thoughts" && optional(session('entry'))['id'] == $entry->id ? "border-primary-subtle" : "" }}">
                                    <input name="entry_id" value="{{ $entry->id }}"
                                           class="form-check-input mt-0 submit-on-check" type="checkbox"
                                           aria-label="Mark entry '{{ $entry->entry_title }}' as completed">
                                    <div class="flex-grow-1">
                                        <a href="{{ $tag == 'Thoughts' ? route('dashboard.showEntry', ['entry_id' => $entry->id]) : '#' }}"
                                           class="btn btn-link w-100 text-start p-0 text-decoration-none text-body"
                                           aria-label="Open entry: {{ $entry->entry_title }}">
                                            <span class="d-block">
                                                <span class="item-text d-block">{{ $entry->entry_title }}</span>
                                                <span class="item-date d-block">{{ $entry->created_at->format('d.m.Y H:i') }}</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </div>
</x-layout>
