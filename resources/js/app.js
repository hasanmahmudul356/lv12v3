import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';

import router from './router';
import App from './App.vue';
import { store } from './store';

const app = createApp(App);

import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
app.use(ToastPlugin);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
app.use(VueSweetalert2);

import { vValidate } from "@/plugins/validator/v-validate";
app.directive('validate', vValidate);

import datepicker from "@/plugins/datepicker.vue";
app.component('datepicker', datepicker);

async function loadLocaleMessages() {
    const response = await fetch(`${baseUrl}/locale.json`);
    if (!response.ok) {
        throw new Error(`Failed to load ${locale} locale`);
    }
    return await response.json();
}

async function bootstrap() {
    const enMessages = await loadLocaleMessages();

    // Create i18n instance
    const i18n = createI18n({
        legacy: false,
        locale: locale,
        fallbackLocale: locale,
        messages: JSON.parse(enMessages),
    });

    app.use(i18n);
    app.use(router);
    app.use(store);

    app.mount('#app');
}

bootstrap().catch(err => {
    console.error('Failed to bootstrap app:', err);
});
