<x-layout>
    <div class="container mt-5 mb-5 p-5">
        <div class="dashboard-container-part float-start">
            <div class="d-flex justify-content-between align-items-center">
                <h2>What is on your mind?</h2>
                <div class="dropdown">
                    <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        llama3.2:latest
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="AIModelsDropdownButton">
                        <li><a class="dropdown-item" href="#">llama3.2:latest</a></li>
                        <li><a class="dropdown-item" href="#">llama3.1:old</a></li>
                        <li><a class="dropdown-item" href="#">helpingai2-9b</a></li>
                    </ul>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.newEntry') }}">
                @csrf <!-- CSRF token for security (cross site request forgery) -->
                <div class="input-group rounded-pill border px-3">
                    <div class="dropdown align-content-center">
                        <button class="p-0 border-0 bg-transparent text-reset shadow-none me-2" type="button" id="tagDropdownButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Open tags">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16" role="img" aria-hidden="true">
                              <path d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z"/>
                            </svg>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="tagDropdownButton">
                            <li>
                                <div class="dropdown-item d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm item-link text-decoration-none text-reset js-tag-toggle">Thoughts</button>
                                    <button type="button" class="btn btn-sm p-0 ms-2" aria-label="Add tag">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <div class="dropdown-item d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm item-link text-decoration-none text-reset js-tag-toggle">Tasks</button>
                                    <button type="button" class="btn btn-sm p-0 ms-2" aria-label="Remove tag">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-item d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm item-link text-decoration-none text-reset js-tag-toggle">Shopping</button>
                                    <button type="button" class="btn btn-sm p-0 ms-2" aria-label="Remove tag">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-item d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm item-link text-decoration-none text-reset js-tag-toggle">Garden</button>
                                    <button type="button" class="btn btn-sm p-0 ms-2" aria-label="Remove tag">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <input name="id" type="hidden" value="{{ auth()->id() }}">
                    <input name="tag" type="hidden" value="Thoughts">
                    <input type="text" name="content" class="form-control border-0 shadow-none" placeholder="Start typing…" required>
                </div>
            </form>

            @if(session('ai_title'))
                <pre>{{ session('ai_title') }}</pre>
            @endif
            @if(session('ai_response'))
                <pre>{{ session('ai_response') }}</pre>
            @endif
        </div>

        <div class="dashboard-container-part float-end">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Tags library</h2>
                <button type="button" class="btn btn-sm" aria-label="Filter tags">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                    </svg>
                </button>
            </div>

            <!-- Thoughts -->
            <div class="mb-3">
                <div class="category-header d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse" data-bs-target="#thoughts"
                     aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <span class="arrow me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                              <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </span>
                        Thoughts
                    </div>
                    <span class="badge rounded-pill">2</span>
                </div>
                <div id="thoughts" class="collapse collapse-content fade-smooth mt-2">
                    <div class="item-box mb-2 d-flex gap-3 align-items-center">
                        <input class="form-check-input" type="checkbox" id="thought-1" aria-label="Waking up with a good mood">
                        <div class="flex-grow-1">
                            <button type="button" class="btn btn-link w-100 text-start p-0 text-decoration-none text-body" aria-label="Open task: Waking up with a good mood">
                                <span class="d-block">
                                    <span class="item-text d-block">Waking up with a good mood</span>
                                    <span class="item-date d-block">08.11.2025 18:37</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="item-box d-flex gap-3 align-items-center mb-2">
                        <input class="form-check-input" type="checkbox" id="thought-2" aria-label="Forgetting too quickly">
                        <div class="flex-grow-1">
                            <button type="button" class="btn btn-link w-100 text-start p-0 text-decoration-none text-body" aria-label="Open task: Forgetting too quickly">
                                <span class="d-block">
                                    <span class="item-text d-block">Forgetting too quickly</span>
                                    <span class="item-date d-block">09.11.2025 13:02</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div class="mb-3">
                <div class="category-header d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse" data-bs-target="#tasks"
                     aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <span class="arrow me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                              <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </span>
                        Tasks
                    </div>
                    <span class="badge rounded-pill">1</span>
                </div>
                <div id="tasks" class="collapse collapse-content fade-smooth mt-2">
                    <div class="item-box d-flex gap-3 align-items-center mb-2">
                        <input class="form-check-input" type="checkbox" id="task-1" aria-label="Example task">
                        <div class="flex-grow-1">
                            <button type="button" class="btn btn-link w-100 text-start p-0 text-decoration-none text-body" aria-label="Open task: Example task">
                                <span class="d-block">
                                    <span class="item-text d-block">Example task</span>
                                    <span class="item-date d-block">10.11.2025 11:00</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shopping -->
            <div class="mb-3">
                <div class="category-header d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse" data-bs-target="#shopping"
                     aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <span class="arrow me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                              <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </span>
                        Shopping
                    </div>
                    <span class="badge rounded-pill">0</span>
                </div>
                <div id="shopping" class="collapse collapse-content fade-smooth mt-2">
                    <!-- empty -->
                </div>
            </div>

            <!-- Garden -->
            <div class="mb-3">
                <div class="category-header d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse" data-bs-target="#garden"
                     aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <span class="arrow me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                              <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </span>
                        Garden
                    </div>
                    <span class="badge rounded-pill">0</span>
                </div>
                <div id="garden" class="collapse collapse-content fade-smooth mt-2">
                    <!-- empty -->
                </div>
            </div>
        </div>
    </div>
</x-layout>
