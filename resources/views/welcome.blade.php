<x-layout>
    <div class="container mt-5 p-5">
        <div class="float-start intro">
            <h1 class="heading mb-3"><strong>Your all in one thought diary.</strong></h1>
            <h5 class="subheading text-muted mb-3">Track your mood, journal your thoughts, practice mindfulness, set daily goals, and build healthier habits with private, secure entries.</h5>
            <hr class="line my-4"/>

            @guest
                <a href="{{ route('show.login') }}" class="btn btnStart btn-lg d-inline-flex align-items-center">
                    Get started<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-arrow-right-short ms-2" viewBox="0 0 16 16" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/>
                    </svg>
                </a>
            @endguest

            <ul class="custom-bullets mt-5">
                <li>
                    <img src="{{ Vite::asset('resources/assets/check2-circle.svg') }}" class="svg-icon me-3" alt="check">
                    Free to start.
                </li>
                <li>
                    <img src="{{ Vite::asset('resources/assets/check2-circle.svg') }}" class="svg-icon me-3" alt="check">
                    100% Private & Safe.
                </li>
                <li>
                    <img src="{{ Vite::asset('resources/assets/check2-circle.svg') }}" class="svg-icon me-3" alt="check">
                    Your data is never used to train AI.
                </li>
            </ul>
        </div>

        <img src="{{ Vite::asset('resources/assets/integral.png') }}" class="intro-image" alt="decorative">
    </div>

    <div class="container second p-5">
        <h1>About MindEcho - Your Daily Companion</h1>
        <div class="row about-section">
            <div class="col-md-4 mb-5">
                <h5>Manage Stress Effectively</h5>
                <p class="heading-text lead">
                    It is a long established fact that a reader will be distracted by the
                    readable content of a page when looking at its layout. The point of
                    using Lorem Ipsum is that it has a more-or-less normal distribution
                    of letters.
                </p>
                <img src="{{ Vite::asset('resources/assets/icon1.png') }}" class="about-icon" alt="icon">
            </div>
            <div class="col-md-4 mb-5">
                <h5>Keep Your Thoughts Nearby</h5>
                <p class="heading-text lead">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                    efficitur consectetur auctor. Maecenas sed tellus enim. Ut eu metus
                    ut ipsum molestie mattis. Sed tempor et neque eget luctus.
                    Suspendisse. asdfasdfasd a
                </p>
                <img src="{{ Vite::asset('resources/assets/icon2.png') }}" class="about-icon" alt="icon">
            </div>
            <div class="col-md-4 mb-5">
                <h5>Brainstorm Your Ideas</h5>
                <p class="heading-text lead">
                    Curabitur id sagittis metus. Nunc feugiat, ligula nec gravida
                    commodo, felis lorem auctor magna, a iaculis tellus elit sit amet
                    elit. Pellentesque pharetra lectus consectetur nulla feugiat,
                    non mattis urna fermentum.
                </p>
                <img src="{{ Vite::asset('resources/assets/icon3.png') }}" class="about-icon" alt="icon">
            </div>
        </div>
    </div>

    <div class="container p-5 mb-5">
        <h1>Racing Thoughts?</h1>
        <div class="row mt-5">
            <div class="col-md-6">
                <h5>Focus</h5>
                <p class="heading-text lead mw-text">
                    Focus isn’t about forcing your attention to stay in one place.
                    It’s about gently returning to what matters when your mind inevitably drifts.
                    Each return strengthens your ability to stay present without tension or frustration.
                </p>
                <h5>Clarity</h5>
                <p class="heading-text lead mw-text">
                    When thoughts slow down, patterns begin to emerge. You start to notice which ideas deserve energy and which can be released.
                    Clarity comes from creating space, not from trying to think harder or faster.
                </p>
                <h5>Mindfulness</h5>
                <p class="heading-text lead mw-text">
                    Mindfulness invites you to observe your thoughts without judgment.
                    Instead of reacting automatically, you learn to pause, breathe, and respond with intention.
                    Over time, this practice builds a calmer and more grounded perspective.
                </p>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ Vite::asset('resources/assets/head.jpg') }}" alt="decorative image">
            </div>
        </div>
    </div>
</x-layout>
