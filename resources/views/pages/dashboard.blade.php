<x-layout>
    <div class="container mt-5 mb-5 p-5">
        <div class="dashboard-container-part float-start">
            <h2>What is on your mind?</h2>
            <div class="input-group rounded-pill border px-3">
                <div class="dropdown align-content-center">
                    <button class="p-0 border-0 bg-transparent text-reset shadow-none me-2" type="button" id="tagDropdownButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Open tags">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16" role="img" aria-hidden="true">
                          <path d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="tagDropdownButton">
                        <li><a class="dropdown-item" href="#">Tag 1</a></li>
                        <li><a class="dropdown-item" href="#">Tag 2</a></li>
                        <li><a class="dropdown-item" href="#">Tag 3</a></li>
                    </ul>
                </div>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Start typing…">
            </div>
        </div>

        <div class="dashboard-container-part float-end">
            <h2>Tags library</h2>

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
        </div>
    </div>
</x-layout>
