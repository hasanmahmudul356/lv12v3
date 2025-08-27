// src/directives/v-validate.js
import { validationManager } from './manager';
import "@/plugins/validator/rules";

 const vValidate = {
    mounted(el, binding) {
        const rules = binding.value.split('|');
        validationManager.addField({ el, rules });

        el.addEventListener('input', async () => {
            await validationManager.validateField(el, rules);
        });
    },

    unmounted(el) {
        validationManager.removeField(el);
    },
};

 export {
     vValidate
 };
