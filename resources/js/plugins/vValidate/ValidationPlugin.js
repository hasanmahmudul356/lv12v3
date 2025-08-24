// src/plugins/ValidationPlugin.js
import { ValidationManager } from './ValidationManager';

export default {
    install(app) {
        app.config.globalProperties.$validator = {
            validate() {
                return ValidationManager.validateAll();
            },
            reset() {
                ValidationManager.reset();
            },
        };
    },
};
