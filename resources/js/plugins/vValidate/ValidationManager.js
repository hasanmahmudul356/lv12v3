// src/utils/ValidationManager.js
import { validate } from 'vee-validate';

export const ValidationManager = {
    fields: [],

    addField(field) {
        this.fields.push(field);
    },
    removeField(el) {
        this.fields = this.fields.filter(field => field.el !== el);
    },
    async validateAll() {
        let isValid = true;

        for (const field of this.fields) {
            const valid = await this.validateField(field.el, field.rules);
            if (!valid) {
                isValid = false;
            }
        }
        return isValid;
    },

    // Validate a single field
    async validateField(el, rules) {
        for (const rule of rules) {
            const [ruleName, params] = rule.split(':');
            const paramArray = params ? params.split(',') : [];
            const { valid, errors } = await validate(el.value, ruleName, paramArray);

            el.setCustomValidity('');
            el.classList.remove("is-invalid");
            el.title = '';
            if (!valid) {
                el.classList.add("is-invalid");
                el.setCustomValidity(errors[0]);
                el.title = errors[0];
                el.reportValidity();
                return false;
            }
        }
        el.setCustomValidity('');

        return true;
    },
    reset() {
        this.fields.forEach(field => {
            field.el.setCustomValidity(''); // Clear custom error messages
            field.el.reportValidity(); // Reset browser UI
        });
    },
};
