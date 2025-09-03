import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';

import router from './router';
import App from './App.vue';
import { store } from './store';
import { mapBackendRoutes } from './lib/mapRoutes';

const app = createApp(App);

// 🔹 Make sure these exist (can also come from env/config)
const baseUrl = window.baseUrl;
const locale = window.locale;

// Plugins
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

// 🔹 API loaders
async function loadLocaleMessages() {
    const response = await fetch(`${baseUrl}/locale.json`);
    if (!response.ok) {
        throw new Error(`Failed to load ${locale} locale`);
    }
    return await response.json(); // already parsed
}

async function loadBackendRoutes() {
    const response = await fetch(`${baseUrl}/routes.json`);
    if (!response.ok) {
        throw new Error(`Failed to load routes`);
    }
    return await response.json(); // already parsed
}

async function bootstrap() {
    try {
        const enMessages = await loadLocaleMessages();
        const backendRoutes = await loadBackendRoutes();

        const dynamicRoutes = mapBackendRoutes(backendRoutes);

        dynamicRoutes.forEach(route => {
            console.log(route);
            router.addRoute(route);
        });

        console.log(router);

        // i18n
        const i18n = createI18n({
            legacy: false,
            locale,
            fallbackLocale: locale,
            messages: {
                [locale]: enMessages
            }
        });

        app.use(i18n);
        app.use(router);
        app.use(store);

        app.mount('#app');
    } catch (err) {
        console.error('Failed to bootstrap app:', err);
    }
}

bootstrap();
