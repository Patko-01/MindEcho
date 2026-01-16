import './bootstrap';

// dashboard functionality
document.addEventListener('DOMContentLoaded', () => {
    (function(){
        const tagButton = document.getElementById('tagButton');
        const hiddenInput = document.getElementById('selectedTagInput');
        const textarea = document.getElementById('dashboard-input');

        if (!tagButton || !hiddenInput || !textarea) {
            return;
        }

        textarea.focus();

        function resize() {
            textarea.style.height = 'auto';
            const taHeight = textarea.scrollHeight;
            textarea.style.height = taHeight + 'px';
        }

        function saveTag() {
            const val = hiddenInput.value.trim() || 'Thoughts';
            tagButton.textContent = '#' + val;
            hiddenInput.type = 'hidden';
            tagButton.style.display = 'inline-block';
            textarea.focus();
        }

        tagButton.addEventListener('click', () => {
            hiddenInput.value = tagButton.textContent.trim().slice(1);
            tagButton.style.display = 'none';
            hiddenInput.type = 'text';
            hiddenInput.focus();
            hiddenInput.setSelectionRange(hiddenInput.value.length, hiddenInput.value.length);
        });

        hiddenInput.addEventListener('blur', saveTag);
        hiddenInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveTag();
            }
        });

        resize();
        textarea.addEventListener('input', resize);
        window.addEventListener('resize', resize);

        textarea.addEventListener('keydown', function(e){
            // Ignore if user is composing IME input
            if (e.isComposing) {
                return;
            }

            if (e.key === 'Enter' && !e.shiftKey && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
                const form = textarea.closest('form');
                if (!form) {
                    return;
                }
                form.submit();
                textarea.disabled = true;
            }
        });

        document.querySelectorAll('.js-tag-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const tagInput = document.querySelector('input[name="tag"]');
                if (!tagInput) {
                    return;
                }
                tagInput.value = btn.textContent.trim();
            });
        });

        document.querySelectorAll('.js-model-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const displayModel = document.getElementById('displayed-model');
                const modelInput = document.querySelector('input[name="model"]');
                if (!displayModel) {
                    return;
                }
                if (!modelInput) {
                    return;
                }
                displayModel.textContent = btn.textContent.trim();
                modelInput.value = btn.textContent.trim();
            });
        });

        document.querySelectorAll('.submit-on-check').forEach(checkbox => {
            checkbox.addEventListener('change', async function () {
                const closestForm = this.closest('form');
                if (!closestForm) {
                    return;
                }

                const payload = {
                    entry_id: this.value
                };

                const token = getCsrfToken(closestForm);

                try {
                    const res = await fetch(closestForm.action, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(payload)
                    });

                    if (res.ok) {
                        const removableForm = document.getElementById("removableForm");
                        let section = closestForm.closest('.tag');

                        if (removableForm) {
                            const entryId = closestForm.querySelector('input[name="entry_id"]').value;
                            if (removableForm === closestForm) { // remove the display message only if the checkbox inside display message was clicked
                                removableForm.remove();

                                const forms = document.querySelectorAll('.tag-Thoughts');
                                let targetForm = null;

                                forms.forEach(form => {
                                    const input = form.querySelector('input[name="entry_id"]');
                                    if (input) {
                                        if (input.value === entryId) {
                                            targetForm = form;
                                        }
                                    }
                                });

                                if (targetForm) {
                                    section = targetForm.closest('.tag');
                                    targetForm.remove();
                                }
                            } else {
                                if (entryId && removableForm.querySelector('input[name="entry_id"]').value === entryId) {
                                    removableForm.remove();
                                }
                            }
                        }

                        if (closestForm) {
                            closestForm.remove();
                        }
                        if (section) {
                            const badge = section.querySelector('.badge.rounded-pill');
                            if (badge) {
                                const remainingItems = section.querySelectorAll('.item-box').length;
                                if (remainingItems === 0) {
                                    section.remove();
                                }
                                badge.textContent = remainingItems;
                            }
                        }
                    } else if (res.status === 422) {
                        console.error('Validation failed');
                    }
                } catch (err) {
                    console.error('Delete request failed', err);
                }
            });
        });
    })();
});

// admin model search functionality
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('modelSearch');
    if (!search) {
        return;
    }
    const items = Array.from(document.querySelectorAll('.list-group .list-group-item'));
    if (items.length === 0) {
        return;
    }

    search.addEventListener('input', function () {
        const searchValue = search.value.trim().toLowerCase();

        items.forEach(li => {
            const name = li.querySelector('.item-text')?.textContent.toLowerCase() || '';
            if (!name.includes(searchValue)) {
                li.classList.add("visually-hidden");
            } else {
                li.classList.remove("visually-hidden");
            }
        });
    });
});

function getCsrfToken(form) {
    const input = form.querySelector('input[name="_token"]');
    return input ? input.value : '';
}

function clearErrors(form) {
    form.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
}

function showError(form, fieldName, message) {
    const errorEl = form.querySelector('.field-error[data-for="' + fieldName + '"]');
    const field = form.querySelector('[name="' + fieldName + '"]');
    if (errorEl) {
        errorEl.textContent = message;
    }
    if (field) {
        field.setAttribute('aria-invalid', 'true');
    }
}

function showErrors(form, errors) {
    for (const key in errors) {
        const msgs = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
        showError(form, key, msgs.join(' '));
    }
}

function clearFieldError(form, fieldName) {
    const errorEl = form.querySelector('.field-error[data-for="' + fieldName + '"]');
    const field = form.querySelector('[name="' + fieldName + '"]');
    if (errorEl) {
        errorEl.textContent = '';
    }
    if (field) {
        field.removeAttribute('aria-invalid');
    }
}

function formToObject(form) {
    const obj = {};
    new FormData(form).forEach((val, key) => obj[key] = val);
    return obj;
}

function validateRequired(value, fieldLabel) {
    if (!value || !value.trim()) {
        return fieldLabel + ' is required.';
    }
    return '';
}

function validateEmail(value) {
    if (!value || !value.trim()) {
        return 'Email is required.';
    }
    if (!/^\S+@\S+\.\S+$/.test(value)) {
        return 'Please enter a valid email address.';
    }
    return '';
}

function validatePassword(value, required = true) {
    if (!value) {
        return required ? 'Password is required.' : '';
    }
    if (value.length < 8) {
        return 'Password must be at least 8 characters.';
    }
    return '';
}

function validatePasswordConfirmation(password, confirmation) {
    if (confirmation !== password) {
        return 'Passwords do not match.';
    }
    return '';
}

document.addEventListener('DOMContentLoaded', () => {
    (function() {
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            const submitBtn = document.getElementById('register-submit');

            function validateRegisterForm(data) {
                const errors = {};
                const nameErr = validateRequired(data.name || '', 'Name');
                const emailErr = validateEmail(data.email || '');
                const passwordErr = validatePassword(data.password || '', true);
                const confirmErr = validatePasswordConfirmation(data.password || '', data.password_confirmation || '');
                if (nameErr) {
                    errors.name = nameErr;
                }
                if (emailErr) {
                    errors.email = emailErr;
                }
                if (passwordErr) {
                    errors.password = passwordErr;
                }
                if (confirmErr) {
                    errors.password_confirmation = confirmErr;
                }
                return errors;
            }

            // Real-time validation on input
            registerForm.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => {
                    const data = formToObject(registerForm);
                    const name = input.name;
                    let error = '';

                    if (name === 'name') {
                        error = validateRequired(data.name || '', 'Name');
                    }
                    if (name === 'email') {
                        error = validateEmail(data.email || '');
                    }
                    if (name === 'password') {
                        error = validatePassword(data.password || '', true);
                        const confirmErr = validatePasswordConfirmation(data.password || '', data.password_confirmation || '');
                        if (confirmErr) {
                            showError(registerForm, 'password_confirmation', confirmErr);
                        } else {
                            clearFieldError(registerForm, 'password_confirmation');
                        }
                    }
                    if (name === 'password_confirmation') {
                        error = validatePasswordConfirmation(data.password || '', data.password_confirmation || '');
                    }

                    if (error) {
                        showError(registerForm, name, error);
                    } else {
                        clearFieldError(registerForm, name);
                    }
                });
            });

            // Form submit
            registerForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                clearErrors(registerForm);

                const data = formToObject(registerForm);
                const errors = validateRegisterForm(data);

                if (Object.keys(errors).length) {
                    showErrors(registerForm, errors);
                    return;
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                try {
                    const res = await fetch(registerForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': getCsrfToken(registerForm)
                        },
                        body: JSON.stringify(data)
                    });

                    if (res.status === 422) {
                        const json = await res.json().catch(() => ({}));
                        if (json.errors) {
                            showErrors(registerForm, json.errors);
                        }
                    } else if (res.redirected) {
                        window.location.href = res.url;
                    } else if (res.ok) {
                        window.location.reload();
                    }
                } catch (err) {
                    console.error('Form submit failed', err);
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }
            });
        }

        const profileForm = document.getElementById('profile-edit-form');
        if (profileForm) {
            const submitBtn = document.getElementById('profile-submit');

            function validateProfileForm(data) {
                const errors = {};
                const nameErr = validateRequired(data.name || '', 'Name');
                const passwordErr = validatePassword(data.password || '', false);
                if (nameErr) {
                    errors.name = nameErr;
                }
                if (passwordErr) {
                    errors.password = passwordErr;
                }
                return errors;
            }

            // Real-time validation
            profileForm.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => {
                    const data = formToObject(profileForm);
                    const name = input.name;
                    let error = '';

                    if (name === 'name') {
                        error = validateRequired(data.name || '', 'Name');
                    }
                    if (name === 'password') {
                        error = validatePassword(data.password || '', false);
                    }

                    if (error) {
                        showError(profileForm, name, error);
                    } else {
                        clearFieldError(profileForm, name);
                    }
                });
            });

            // Form submit
            profileForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                clearErrors(profileForm);

                const data = formToObject(profileForm);
                const errors = validateProfileForm(data);

                if (Object.keys(errors).length) {
                    showErrors(profileForm, errors);
                    return;
                }

                // Only send password if provided
                const payload = {name: data.name};
                if (data.password) {
                    payload.password = data.password;
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                try {
                    const res = await fetch(profileForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': getCsrfToken(profileForm)
                        },
                        body: JSON.stringify(payload)
                    });

                    if (res.status === 422) {
                        const json = await res.json().catch(() => ({}));
                        if (json.errors) {
                            showErrors(profileForm, json.errors);
                        }
                    } else if (res.redirected) {
                        window.location.href = res.url;
                    } else if (res.ok) {
                        window.location.reload();
                    }
                } catch (err) {
                    console.error('Form submit failed', err);
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }
            });
        }

        const deleteAccountForm = document.getElementById('profile-delete-form');
        if (deleteAccountForm) {
            deleteAccountForm.addEventListener('submit', function (e) {
                const confirmation = confirm('Are you sure you want to delete your account? This action cannot be undone.');
                if (!confirmation) {
                    e.preventDefault();
                }
            });
        }

        document.querySelectorAll(".model-delete-form").forEach(form => {
            form.addEventListener('submit', function (e) {
                const confirmation = confirm('Are you sure you want to delete this model? This action cannot be undone.');
                if (!confirmation) {
                    e.preventDefault();
                }
            });
        });

        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            // Read EmailJS credentials from Vite env
            const EMAILJS_SERVICE_ID = import.meta.env.VITE_EMAILJS_SERVICE_ID;
            const EMAILJS_TEMPLATE_ID = import.meta.env.VITE_EMAILJS_TEMPLATE_ID;
            const EMAILJS_USER_ID = import.meta.env.VITE_EMAILJS_USER_ID;

            const submitBtn = document.getElementById('contact-submit');
            let emailjsLoaded = false;

            function missingEnv() {
                return !EMAILJS_SERVICE_ID || !EMAILJS_TEMPLATE_ID || !EMAILJS_USER_ID;
            }

            // Load EmailJS script
            function loadEmailJS() {
                return new Promise((resolve, reject) => {
                    if (missingEnv()) {
                        alert('EmailJS is not configured properly. Please contact the site administrator.');
                        reject(new Error('EmailJS env variables are not configured'));
                        return;
                    }
                    if (window.emailjs) {
                        if (!emailjsLoaded) {
                            window.emailjs.init(EMAILJS_USER_ID);
                            emailjsLoaded = true;
                        }
                        resolve();
                        return;
                    }

                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js';
                    script.async = true;
                    script.onload = () => {
                        window.emailjs.init(EMAILJS_USER_ID);
                        emailjsLoaded = true;
                        resolve();
                    };
                    script.onerror = () => reject(new Error('Failed to load EmailJS'));
                    document.head.appendChild(script);
                });
            }

            function validateContactForm(data) {
                const errors = {};
                const firstNameErr = validateRequired(data.firstName || '', 'First name');
                const lastNameErr = validateRequired(data.lastName || '', 'Last name');
                const emailErr = validateEmail(data.email || '');
                const messageErr = validateRequired(data.messageContent || '', 'Message');
                if (firstNameErr) {
                    errors.firstName = firstNameErr;
                }
                if (lastNameErr) {
                    errors.lastName = lastNameErr;
                }
                if (emailErr) {
                    errors.email = emailErr;
                }
                if (messageErr) {
                    errors.messageContent = messageErr;
                }
                return errors;
            }

            contactForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                clearErrors(contactForm);

                const data = formToObject(contactForm);
                const errors = validateContactForm(data);

                if (Object.keys(errors).length) {
                    showErrors(contactForm, errors);
                    return;
                }

                const originalText = submitBtn ? submitBtn.textContent : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';
                }

                try {
                    await loadEmailJS();

                    await window.emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, {
                        to_email: 'patriksam258@gmail.com',
                        subject: 'New contact form submission from ' + data.firstName + ' ' + data.lastName,
                        first_name: data.firstName,
                        last_name: data.lastName,
                        name: data.firstName + ' ' + data.lastName,
                        from_email: data.email,
                        message: data.messageContent,
                        time: new Date().toLocaleString()
                    });

                    alert('Message sent — thank you!');
                    contactForm.reset();
                } catch (err) {
                    console.error('Failed to send contact email', err);
                    alert('Failed to send message. Please try again later.');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                }
            });
        }
    })()
});
