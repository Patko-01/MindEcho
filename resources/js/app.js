import './bootstrap';

const FormUtils = {
    getCsrfToken(form) {
        const tokenInput = form.querySelector('input[name="_token"]');
        return tokenInput ? tokenInput.value : '';
    },

    clearErrors(form) {
        form.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
    },

    showFieldErrors(form, errors) {
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
    },

    updateFieldUI(form, name, messages) {
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
};

class FormHandler {
    // options: { formId, submitBtnId, validators: { fieldName: fn(formData|string) => [msgs] }, preparePayload(formData) }
    constructor(options) {
        this.form = document.getElementById(options.formId);
        if (!this.form) {
            return;
        }

        this.submitBtn = options.submitBtnId ? document.getElementById(options.submitBtnId) : null;
        this._validators = options.validators || {};
        this._preparePayload = options.preparePayload;
        this._origBtnText = this.submitBtn ? this.submitBtn.textContent : '';

        this._attachInputListeners();
        this.form.addEventListener('submit', this._onSubmit.bind(this));
    }

    _attachInputListeners() {
        const names = Object.keys(this._validators);
        if (!names.length) {
            return;
        }

        const selector = names.map(n => 'input[name="' + n + '"]').join(', ');
        const inputs = this.form.querySelectorAll(selector);

        inputs.forEach(inp => {
            inp.addEventListener('input', this._onInputChange.bind(this));
            inp.addEventListener('blur', this._onInputChange.bind(this));
        });
    }

    _onInputChange(e) {
        const fd = new FormData(this.form);
        const name = e.target.name;
        const msgs = this._validateField(name, fd);

        FormUtils.updateFieldUI(this.form, name, msgs);

        // if password changes, re-validate confirmation when present
        if (name === 'password' && this._validators['password_confirmation']) {
            const confirmMsgs = this._validateField('password_confirmation', fd);
            FormUtils.updateFieldUI(this.form, 'password_confirmation', confirmMsgs);
        }
    }

    _validateField(name, formData) {
        const validator = this._validators[name];

        if (typeof validator === 'function') {
            try {
                return validator(formData);
            } catch (err) {
                // If validator expects a string value instead of FormData, try that
                const val = formData instanceof FormData ? (formData.get(name) || '') : (formData || '');
                return validator(val) || [];
            }
        }
        return [];
    }

    _validateAll(formData) {
        const errors = {};
        for (const name in this._validators) {
            const msgs = this._validateField(name, formData);
            if (msgs && msgs.length) {
                errors[name] = msgs;
            }
        }
        return errors;
    }

    async _onSubmit(e) {
        e.preventDefault();
        FormUtils.clearErrors(this.form);

        const formData = new FormData(this.form);
        const clientErrors = this._validateAll(formData);

        if (Object.keys(clientErrors).length > 0) {
            FormUtils.showFieldErrors(this.form, clientErrors);
            return;
        }

        const payload = this._preparePayload ? this._preparePayload(formData) : this._defaultPayload(formData);

        if (this.submitBtn) {
            this.submitBtn.disabled = true;
        }

        try {
            const res = await fetch(this.form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': FormUtils.getCsrfToken(this.form)
                },
                body: JSON.stringify(payload)
            });

            const ct = res.headers.get('content-type') || '';
            if (res.status === 422 || ct.indexOf('application/json') !== -1) {
                const data = await res.json().catch(() => ({}));
                if (res.status === 422 && data.errors) {
                    FormUtils.showFieldErrors(this.form, data.errors);
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
        } catch (err) {
            console.error('Form submit failed', err);
        } finally {
            if (this.submitBtn) {
                this.submitBtn.disabled = false;
                this.submitBtn.textContent = this._origBtnText;
            }
        }
    }

    _defaultPayload(formData) {
        // include all form fields
        const obj = {};
        for (const pair of formData.entries()) {
            obj[pair[0]] = pair[1];
        }
        return obj;
    }
}

// --- Instantiate handlers for the two forms ---

// Registration form
new FormHandler({
    formId: 'register-form',
    submitBtnId: 'register-submit',
    validators: {
        name(formData) {
            const v = (formData instanceof FormData ? (formData.get('name') || '') : formData).trim ? (formData.get('name') || '').trim() : (formData.name || '');
            const msgs = [];
            if (!v) msgs.push('Name is required.');
            return msgs;
        },
        email(formData) {
            const v = (formData instanceof FormData ? (formData.get('email') || '') : formData).trim ? (formData.get('email') || '').trim() : (formData.email || '');
            const msgs = [];
            if (!v) {
                msgs.push('Email is required.');
            } else if (!/^\S+@\S+\.\S+$/.test(v)) {
                msgs.push('Please enter a valid email address.');
            }
            return msgs;
        },
        password(formData) {
            const v = formData instanceof FormData ? (formData.get('password') || '') : (formData.password || '');
            const msgs = [];
            if (!v) {
                msgs.push('Password is required.');
            } else if (String(v).length < 8) {
                msgs.push('Password must be at least 8 characters.');
            }
            return msgs;
        },
        password_confirmation(formData) {
            const pw = formData instanceof FormData ? (formData.get('password') || '') : (formData.password || '');
            const val = formData instanceof FormData ? (formData.get('password_confirmation') || '') : (formData.password_confirmation || '');
            const msgs = [];
            if (val !== pw) msgs.push('Passwords do not match.');
            return msgs;
        }
    },
    preparePayload(formData) {
        return {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation')
        };
    }
});

// Profile edit form
new FormHandler({
    formId: 'profile-edit-form',
    submitBtnId: 'profile-submit',
    validators: {
        name(formData) {
            const v = formData instanceof FormData ? (formData.get('name') || '') : (formData.name || '');
            const msgs = [];
            if (!v || !String(v).trim()) {
                msgs.push('Name is required.');
            }
            return msgs;
        },
        password(formData) {
            const v = formData instanceof FormData ? (formData.get('password') || '') : (formData.password || '');
            const msgs = [];
            if (v && String(v).length < 8) {
                msgs.push('Password must be at least 8 characters.');
            }
            return msgs;
        }
    },
    preparePayload(formData) {
        const payload = { name: formData.get('name') };
        const pw = formData.get('password');
        if (pw) payload.password = pw;
        return payload;
    }
});

// Contact form handler using EmailJS
(function() {
    const EMAILJS_SERVICE_ID = 'service_bl4y65j';
    const EMAILJS_TEMPLATE_ID = 'template_3bdyys8';
    const EMAILJS_USER_ID = 'f6honLRkEsay40gZ0';

    // Single, promise-based loader + initializer for EmailJS.
    // Usage: await ensureEmailjs(); // resolves when window.emailjs is available and initialized (if key provided)
    let _emailjsReady = null;

    function ensureEmailjs() {
        if (typeof window.emailjs !== 'undefined' && window.emailjs.send) {
            // already present - ensure init was called if user provided a key
            try {
                if (EMAILJS_USER_ID && window.emailjs.init && !window.__emailjs_inited) {
                    window.emailjs.init(EMAILJS_USER_ID);
                    window.__emailjs_inited = true;
                }
            } catch (e) {
                console.warn('EmailJS init failed or already initialized', e);
            }
            return Promise.resolve(window.emailjs);
        }

        if (_emailjsReady) {
            return _emailjsReady;
        }

        _emailjsReady = new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js';
            s.async = true;
            s.onload = () => {
                try {
                    if (EMAILJS_USER_ID && window.emailjs && window.emailjs.init) {
                        window.emailjs.init(EMAILJS_USER_ID);
                        window.__emailjs_inited = true;
                    }
                } catch (e) {
                    console.warn('EmailJS init failed', e);
                }
                if (typeof window.emailjs !== 'undefined' && window.emailjs.send) {
                    resolve(window.emailjs);
                } else {
                    reject(new Error('EmailJS loaded but `emailjs` object missing'));
                }
            };
            s.onerror = () => reject(new Error('Failed to load EmailJS SDK'));
            document.head.appendChild(s);
        });

        return _emailjsReady;
    }

    // Wait for DOM ready to attach handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Start loading EmailJS in the background so it's ready by the time the user submits.
         const form = document.getElementById('contact-form');
         const submit = document.getElementById('contact-submit');
         if (!form) {
             return;
         }

        function getFormDataObj() {
            const fd = new FormData(form);
            const obj = {};

            for (const [k, v] of fd.entries()) {
                obj[k] = v;
            }

            return obj;
        }

        function validateContactForm(fdObj) {
            const errors = {};

            if (!fdObj.firstName || !String(fdObj.firstName).trim()){
                errors.firstName = ['First name is required.'];
            }
            if (!fdObj.lastName || !String(fdObj.lastName).trim()) {
                errors.lastName = ['Last name is required.'];
            }
            if (!fdObj.email || !/^\S+@\S+\.\S+$/.test(fdObj.email)) {
                errors.email = ['Please enter a valid email address.'];
            }
            if (!fdObj.messageContent || !String(fdObj.messageContent).trim()) {
                errors.messageContent = ['Message is required.'];
            }

            return errors;
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            FormUtils.clearErrors(form);

            const data = getFormDataObj();
            const clientErrors = validateContactForm(data);
            if (Object.keys(clientErrors).length) {
                FormUtils.showFieldErrors(form, clientErrors);
                return;
            }

            if (submit) {
                submit.disabled = true;
                const originalText = submit.textContent;
                submit.textContent = 'Sending...';

                try {
                    // Ensure EmailJS is loaded and initialized (will throw if loading fails)
                    await ensureEmailjs();

                    // Prepare template params
                    const templateParams = {
                        to_email: 'patriksam258@gmail.com',
                        subject: 'New contact form submission from ' + (data.firstName || '') + ' ' + (data.lastName || ''),
                        first_name: data.firstName,
                        last_name: data.lastName,
                        name: (data.firstName || '') + (data.lastName ? (' ' + data.lastName) : ''),
                        from_email: data.email,
                        message: data.messageContent,
                        time: new Date().toLocaleString(),
                    };

                    // Send via EmailJS
                    await window.emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, templateParams);

                    alert('Message sent — thank you!');
                    form.reset();
                } catch (err) {
                    console.error('Failed to send contact email', err);
                    alert('Failed to send message. Please try again later.');
                } finally {
                    if (submit) {
                        submit.disabled = false;
                        submit.textContent = originalText;
                    }
                }
            }
        });
     });
 })();
