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
