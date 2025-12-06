import './bootstrap';

// Client-side validation + AJAX - registration
(function () {
    const form = document.getElementById('register-form');
    if (!form) {
        return;
    }

    const submitBtn = document.getElementById('register-submit');

    function getCsrfToken() {
        // Prefer meta tag if present, otherwise read the hidden input
        const meta = document.querySelector('meta[name="csrf-token"]');

        if (meta) {
            return meta.getAttribute('content');
        }

        const tokenInput = form.querySelector('input[name="_token"]');

        return tokenInput ? tokenInput.value : '';
    }

    function clearErrors() {
        form.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
    }

    function showFieldErrors(errors) {
        for (const key in errors) {
            const fieldErr = form.querySelector('.field-error[data-for="' + key + '"]');
            const field = form.querySelector('[name="' + key + '"]');

            const msgs = Array.isArray(errors[key]) ? errors[key] : [errors[key]];

            if (fieldErr) {
                fieldErr.textContent = msgs.join(' ');
            }
            if (field) {
                field.setAttribute('aria-invalid', 'true');
            }
        }
    }

    function validateClient(formData) {
        const errors = {};

        const name = formData.get('name')?.trim();
        const email = formData.get('email')?.trim();
        const password = formData.get('password') || '';
        const passwordConfirmation = formData.get('password_confirmation') || '';

        if (!name) {
            errors.name = ['Name is required.'];
        }
        if (!email) {
            errors.email = ['Email is required.'];
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errors.email = ['Please enter a valid email address.'];
        }
        if (!password) {
            errors.password = ['Password is required.'];
        } else if (password.length < 8) {
            errors.password = ['Password must be at least 8 characters.'];
        }
        if (password !== passwordConfirmation) {
            errors.password_confirmation = ['Passwords do not match.'];
        }

        return errors;
    }

    function validateField(name, formData) {
        const msgs = [];
        const value = formData.get(name) || '';

        if (name === 'name') {
            if (!value.trim()) {
                msgs.push('Name is required.');
            }
        }
        if (name === 'email') {
            if (!value.trim()) {
                msgs.push('Email is required.')
            } else if (!/^\S+@\S+\.\S+$/.test(value.trim())) {
                msgs.push('Please enter a valid email address.');
            }
        }
        if (name === 'password') {
            if (!value) {
                msgs.push('Password is required.');
            } else if (value.length < 8) {
                msgs.push('Password must be at least 8 characters.');
            }
        }
        if (name === 'password_confirmation') {
            const pw = formData.get('password') || '';

            if (value !== pw) {
                msgs.push('Passwords do not match.');
            }
        }

        return msgs;
    }

    const inputs = form.querySelectorAll('input[name="name"], input[name="email"], input[name="password"], input[name="password_confirmation"]');

    function updateFieldUI(name, messages) {
        const fieldErr = form.querySelector('.field-error[data-for="' + name + '"]');
        const field = form.querySelector('[name="' + name + '"]');

        if (fieldErr) {
            fieldErr.textContent = messages.length ? messages.join(' ') : '';
        }
        if (field) {
            if (messages.length) {
                field.setAttribute('aria-invalid', 'true');
            } else {
                field.removeAttribute('aria-invalid');
            }
        }
    }

    function onInputChange(e) {
        const fd = new FormData(form);
        const name = e.target.name;

        const msgs = validateField(name, fd);

        updateFieldUI(name, msgs);

        // if password changes, re-validate confirmation
        if (name === 'password') {
            const confirmMsgs = validateField('password_confirmation', fd);
            updateFieldUI('password_confirmation', confirmMsgs);
        }
    }

    inputs.forEach(inp => {
        inp.addEventListener('input', onInputChange);
        inp.addEventListener('blur', onInputChange);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

        const formData = new FormData(form);
        const clientErrors = validateClient(formData);

        if (Object.keys(clientErrors).length > 0) {
            showFieldErrors(clientErrors);
            return;
        }

        // Prepare payload
        const payload = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation')
        };

        // Disable submit
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(payload)
        }).then(async res => {
            const ct = res.headers.get('content-type') || '';
            if (res.status === 422 || ct.indexOf('application/json') !== -1) {
                const data = await res.json().catch(() => ({}));
                if (res.status === 422 && data.errors) {
                    showFieldErrors(data.errors);
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (res.ok) {
                    window.location.reload(); // success with json
                }
            } else if (res.redirected) {
                window.location.href = res.url;
            } else if (res.ok) {
                window.location.reload(); // fallback
            }
        }).catch(err => {
            console.error('Registration request failed', err);
        }).finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Register';
            }
        });
    });
})();

// Client-side validation + AJAX - edit profile
(function () {
    const pForm = document.getElementById('profile-edit-form');
    if (!pForm) {
        return;
    }

    const submitBtn = document.getElementById('profile-submit');

    function getCsrfTokenProfile() {
        const meta = document.querySelector('meta[name="csrf-token"]');

        if (meta) {
            return meta.getAttribute('content');
        }

        const tokenInput = pForm.querySelector('input[name="_token"]');

        return tokenInput ? tokenInput.value : '';
    }

    function clearErrorsProfile() {
        pForm.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        pForm.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
    }

    function showFieldErrorsProfile(errors) {
        for (const key in errors) {
            const fieldErr = pForm.querySelector('.field-error[data-for="' + key + '"]');
            const field = pForm.querySelector('[name="' + key + '"]');

            const msgs = Array.isArray(errors[key]) ? errors[key] : [errors[key]];

            if (fieldErr) {
                fieldErr.textContent = msgs.join(' ');
            }
            if (field) {
                field.setAttribute('aria-invalid', 'true');
            }
        }
    }

    function validateName(value) {
        const msgs = [];

        if (!value || !String(value).trim()) {
            msgs.push('Name is required.');
        }

        return msgs;
    }

    const nameInput = pForm.querySelector('input[name="name"]');

    if (nameInput) {
        const onNameChange = function (e) {
            const msgs = validateName(e.target.value);
            const fieldErr = pForm.querySelector('.field-error[data-for="name"]');

            if (fieldErr) {
                fieldErr.textContent = msgs.length ? msgs.join(' ') : '';
            }
            if (e.target) {
                if (msgs.length) {
                    e.target.setAttribute('aria-invalid', 'true');
                }
                else {
                    e.target.removeAttribute('aria-invalid');
                }
            }
        };

        nameInput.addEventListener('input', onNameChange);
        nameInput.addEventListener('blur', onNameChange);
    }

    // Password is optional on profile edit, but if provided it must be >= 8 chars.
    const passwordInput = pForm.querySelector('input[name="password"]');

    function validatePassword(value) {
        const msgs = [];
        if (value) {
            if (String(value).length < 8) {
                msgs.push('Password must be at least 8 characters.');
            }
        }
        return msgs;
    }

    if (passwordInput) {
        const onPasswordChange = function (e) {
            const msgs = validatePassword(e.target.value);
            const fieldErr = pForm.querySelector('.field-error[data-for="password"]');

            if (fieldErr) {
                fieldErr.textContent = msgs.length ? msgs.join(' ') : '';
            }
            if (e.target) {
                if (msgs.length) {
                    e.target.setAttribute('aria-invalid', 'true');
                } else {
                    e.target.removeAttribute('aria-invalid');
                }
            }
        };

        passwordInput.addEventListener('input', onPasswordChange);
        passwordInput.addEventListener('blur', onPasswordChange);
    }

    pForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrorsProfile();

        const nameVal = nameInput ? nameInput.value : '';
        const clientMsgs = validateName(nameVal);

        if (clientMsgs.length) {
            showFieldErrorsProfile({ name: clientMsgs });
            return;
        }

        // Validate password (optional) and build payload. Only include password when provided.
        const passwordVal = passwordInput ? passwordInput.value : '';
        const passwordMsgs = validatePassword(passwordVal);

        if (passwordMsgs.length) {
            showFieldErrorsProfile({ password: passwordMsgs });
            return;
        }

        const payload = { name: nameVal };
        if (passwordVal) {
            payload.password = passwordVal;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
        }

        fetch(pForm.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfTokenProfile()
            },
            body: JSON.stringify(payload)
        }).then(async res => {
            const ct = res.headers.get('content-type') || '';
            if (res.status === 422 || ct.indexOf('application/json') !== -1) {
                const data = await res.json().catch(() => ({}));
                if (res.status === 422 && data.errors) {
                    showFieldErrorsProfile(data.errors);
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (res.ok) {
                    window.location.reload();
                }
            } else if (res.redirected) {
                window.location.href = res.url;
            } else if (res.ok) {
                window.location.reload();
            }
        }).catch(err => {
            console.error('Profile update request failed', err);
        }).finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Changes';
            }
        });
    });
})();
