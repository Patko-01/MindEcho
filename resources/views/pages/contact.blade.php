<x-layout>
    <div class="container form-container p-3 mt-5 mb-5">
        <h2>Contact</h2>
        <form id="contact-form" action="#" method="POST">
            @csrf <!-- CSRF token for security (cross site request forgery) -->

            <div class="d-flex mb-3 mt-4 gap-3">
                <div class="flex-fill">
                    <label for="firstName" class="form-label">First name</label>
                    <input type="text" placeholder="First name" name="firstName" class="form-control" autocomplete="on" id="firstName" aria-describedby="personFirstNameHelp" required>
                    <div class="field-error text-danger small mt-1" data-for="firstName"></div>
                </div>
                <div class="flex-fill">
                    <label for="lastName" class="form-label">Last name</label>
                    <input type="text" placeholder="Last name" name="lastName" class="form-control" autocomplete="on" id="lastName" aria-describedby="personLastNameHelp" required>
                    <div class="field-error text-danger small mt-1" data-for="lastName"></div>
                </div>
            </div>

            <div class="mb-3">
                <label for="loginPersonEmail" class="form-label">Email</label>
                <input type="email" placeholder="Email" autocomplete="off" value="{{ old('email', auth()->user()->getEmailForPasswordReset()) }}" name="email" class="form-control" id="loginPersonEmail" aria-describedby="personEmailHelp" required>
                <div class="field-error text-danger small mt-1" data-for="email"></div>
            </div>
            <div class="mb-3">
                <label for="messageContent" class="form-label">Message</label>
                <textarea name="messageContent" class="form-control" id="messageContent" placeholder="Enter your question or message" aria-describedby="personMessageHelp" rows="5" required></textarea>
                <div class="field-error text-danger small mt-1" data-for="messageContent"></div>
            </div>

            <button id="contact-submit" type="submit" class="midBtn btn btn-dark w-100" aria-label="Submit contact form">Submit</button>
        </form>
    </div>
</x-layout>
