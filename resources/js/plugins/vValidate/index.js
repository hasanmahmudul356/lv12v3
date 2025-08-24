// src/directives/v-validate.js
import { ValidationManager } from './ValidationManager';
import {defineRule} from "vee-validate";

defineRule("required", (value) => (value ? true : "This field is required"));
defineRule("email", (value) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(value) || "This must be a valid email";
});
defineRule("min", (value, [minLength]) => {
    if (!minLength) return "Minimum length parameter is missing";
    return value.length >= minLength ? true : `Minimum length is ${minLength}`;
});

export const index = {
    mounted(el, binding) {
        const rules = binding.value.split('|');
        ValidationManager.addField({ el, rules });

        el.addEventListener('input', async () => {
            await ValidationManager.validateField(el, rules);
        });
    },

    unmounted(el) {
        ValidationManager.removeField(el);
    },
};
