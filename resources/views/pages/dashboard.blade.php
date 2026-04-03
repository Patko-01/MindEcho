@php use Carbon\Carbon; @endphp
<x-layout>
    <div class="container mt-5 mb-5 p-5">
        <div class="row gap-5">
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 id="yourMind"></h2>
                    <div class="dropdown">
                        <a class="btn dropdown-toggle" id="displayed-model" href="#" role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            {{ $usedModel }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="Displayed model">
                            @foreach($models as $model)
                                <li>
                                    <button type="button" class="btn btn-sm w-100 js-model-toggle"
                                            aria-labelledby="Select AI model {{ $model }}">{{ $model }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @php $newEntry = session('entry'); @endphp
                @if ($newEntry && $newEntry['tag'] == "Thoughts")
                    <div class="mt-3 mb-3">
                        <form method="POST" id="removableForm" action="{{ route('dashboard.delete') }}">
                            @csrf
                            @method('DELETE')
                            <div class="item-box d-inline-flex gap-2 align-items-center p-2">
                                <input name="entry_id" value="{{ $newEntry['id'] }}"
                                       class="form-check-input mt-0 submit-on-check" type="checkbox"
                                       aria-label="Mark entry '{{ $newEntry['entry_title'] }}' as completed">
                                <span id="openedEntryTitle" class="item-text">{{ $newEntry['entry_title'] }}</span>
                                <span class="item-text">•</span>
                                <span class="item-date">{{ $newEntry['created_at']->format('d.m.Y H:i') }}</span>
                                <button class="btn btn-sm p-0 m-0 border-0" id="toggle-visibility-button" type="button"
                                        aria-label="Toggle visibility of conversation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                         class="bi bi-eye-slash" viewBox="0 0 16 16">
                                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                        <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="card item-box mt-1 p-0">
                                @foreach($newEntry['conversation'] as $item)
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                                <path fill-rule="evenodd"
                                                      d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                            </svg>
                                            <span class="item-text ms-2 me-2">•</span>
                                            <p class="m-0">{{ Carbon::parse($item['date'])->format('M d, Y \a\t H:i') }}</p>
                                        </div>
                                        <p class="card-text">{{ $item['note'] }}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-anthropic" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M9.218 2h2.402L16 12.987h-2.402zM4.379 2h2.512l4.38 10.987H8.82l-.895-2.308h-4.58l-.896 2.307H0L4.38 2.001zm2.755 6.64L5.635 4.777 4.137 8.64z"/>
                                        </svg>
                                        <p class="card-text mb-0">{{ $item['response'] }}</p>
                                        <span class="float-end text-muted text-end">{{ $item['model_name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.newEntry') }}">
                    @csrf <!-- CSRF token for security (cross site request forgery) -->
                    <div class="input-group mainInput rounded-5 border px-3 py-2 flex-column">
                        <div class="d-flex align-items-center">
                            <input name="model" type="hidden" value="{{ $usedModel }}">
                            <input name="old_entry_id" id="old_entry_id" type="hidden"
                                   value="{{ session('entry') && session('tag') && session('tag') == 'Thoughts' ? session('entry')['id'] : '' }}">
                            <textarea id="dashboard-input" name="content"
                                      class="form-control border-0 shadow-none auto-resize-textarea"
                                      placeholder="Start typing…" rows="1" required></textarea>
                        </div>
                        <div class="ms-1 d-flex align-items-center">
                            <input type="hidden" name="tag" class="form-control form-control-sm w-auto"
                                   id="selectedTagInput" value="{{ session('tag', 'Thoughts') }}">
                            <button type="button" class="btn btn-sm text-secondary" id="tagButton"
                                    aria-label="Select tag">
                                #{{ session('tag', 'Thoughts') }}
                            </button>

                            <div class="ms-auto d-flex gap-2">
                                <button class="btn icon-btn p-0" id="temperatureButton" type="button" data-bs-toggle="modal" data-bs-target="#temperatureSettingsModal" aria-labelledby="Change model temperature" title="Temperature settings">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                                    </svg>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="temperatureSettingsModal" tabindex="-1" aria-labelledby="Temperature Settings Modal Window" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Temperature Settings</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label for="rangeTemperature" class="form-label mb-0 d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-thermometer-half" viewBox="0 0 16 16">
                                                            <path d="M9.5 12.5a1.5 1.5 0 1 1-2-1.415V6.5a.5.5 0 0 1 1 0v4.585a1.5 1.5 0 0 1 1 1.415"/>
                                                            <path d="M5.5 2.5a2.5 2.5 0 0 1 5 0v7.55a3.5 3.5 0 1 1-5 0zM8 1a1.5 1.5 0 0 0-1.5 1.5v7.987l-.167.15a2.5 2.5 0 1 0 3.333 0l-.166-.15V2.5A1.5 1.5 0 0 0 8 1"/>
                                                        </svg>
                                                        Temperature
                                                    </label>

                                                    <span class="badge bg-primary fs-6 px-3 py-2" id="spanForTemperature"></span>
                                                </div>

                                                <!-- Slider -->
                                                <input
                                                    id="rangeTemperature"
                                                    name="temperature"
                                                    type="range"
                                                    class="form-range mb-1"
                                                    min="0"
                                                    max="1"
                                                    step="0.1"
                                                    value="0.8"
                                                >

                                                <div class="d-flex justify-content-between small text-muted">
                                                    <span>Focused</span>
                                                    <span>Creative</span>
                                                </div>
                                                <div class="form-text mt-4">
                                                    Lower values = more precise. Higher values = more creative.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn icon-btn p-0" type="submit" aria-labelledby="Submit entry">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                         class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tags library -->
            <div class="col-12 col-lg-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Tags library</h2>
                    <div class="input-group filter pb-2">
                        <input type="text" class="form-control rounded-start-3" id="entrySearch" placeholder="Filter"
                               aria-label="Filter entries">
                        <button class="btn btn-sm borderBtn rounded-end-3" type="button" data-bs-toggle="dropdown"
                                aria-label="Filter tags" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                 class="bi bi-funnel" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                            </svg>
                        </button>
                        <button class="btn btn-sm rounded-3 borderBtn ms-1" id="sortBtn" type="button">
                            <span id="firstSpanIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16">
                                    <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                                </svg>
                            </span>
                            <span id="secondSpanIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                                  <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                                </svg>
                            </span>
                        </button>
                        <button class="btn btn-sm rounded-3 borderBtn position-absolute visually-hidden" id="hideSortBtn" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-ban" viewBox="0 0 18 18">
                                <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0"/>
                            </svg>
                        </button>
                        <div class="dropdown">
                            <ul class="dropdown-menu ps-2">
                                @foreach($dataGrouped as $tag => $entries)
                                    <li class="filterTag">
                                        <div class="form-check">
                                            <input class="form-check-input filterCheckbox" name="filterCheckbox"
                                                   type="checkbox" checked>
                                            <span class="form-check-label">{{ $tag }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="allEntriesContainer"></div>
                @foreach($dataGrouped as $tag => $entries)
                    <div class="tag mb-3" id="header-{{ $tag }}">
                        <div class="category-header d-flex justify-content-between align-items-center"
                             data-bs-toggle="collapse" data-bs-target="#{{ $tag }}"
                             aria-expanded="{{ session('tag') === $tag ? 'true' : 'false' }}"
                             aria-label="Toggle {{ $tag }} entries">
                            <div class="d-flex align-items-center">
                            <span class="arrow me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                  <path
                                      d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                </svg>
                            </span>
                                <span class="tagName">{{ $tag }}</span>
                            </div>
                            <span class="badge rounded-pill">{{ count($entries) }}</span>
                        </div>
                        <div id="{{ $tag }}" class="collapse collapse-content fade-smooth mt-2 {{ session('tag') == $tag ? 'show' : '' }}">
                            @foreach($entries as $entry)
                                <form method="POST" class="tag-{{ $tag }} entry"
                                      action="{{ route('dashboard.delete') }}">
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
                                                    <span class="item-date d-block" data-date="{{ $entry->updated_at }}">{{ Carbon::parse($entry->updated_at)->diffForHumans() }}</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <p class="text-center text-muted {{ count($deletedEntries) > 0 ? '' : 'visually-hidden' }}" id="deletedEntriesLine">deleted</p>
                <div id="divWithDeletedEntries" data-destroy-url="{{ route('dashboard.destroy') }}" data-restore-url="{{ route('dashboard.restore') }}">
                    @foreach($deletedEntries as $entry)
                        <form method="POST" action="{{ route('dashboard.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <div class="item-box mb-2 d-flex gap-3 align-items-center bg-secondary-subtle">
                                <input name="entry_id" value="{{ $entry->id }}"
                                       class="form-check-input mt-0 submit-on-check" type="checkbox"
                                       aria-label="Mark entry '{{ $entry->entry_title }}' as completed">
                                <div class="flex-grow-1">
                                    <a href="{{ route('dashboard.restore', ['entry_id' => $entry->id]) }}"
                                       class="btn btn-link w-100 text-start p-0 text-decoration-none text-body"
                                       aria-label="Restore entry: {{ $entry->entry_title }}">
                                        <span class="d-block">
                                            <span class="item-text d-block">{{ $entry->entry_title }}</span>
                                            <span class="item-date d-block">{{ Carbon::parse($entry->updated_at)->diffForHumans() }}</span>
                                        </span>
                                    </a>
                                </div>
                                @if($entry->tag == 'Thoughts')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="currentColor" class="bi bi-anthropic" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M9.218 2h2.402L16 12.987h-2.402zM4.379 2h2.512l4.38 10.987H8.82l-.895-2.308h-4.58l-.896 2.307H0L4.38 2.001zm2.755 6.64L5.635 4.777 4.137 8.64z"/>
                                    </svg>
                                @endif
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>
