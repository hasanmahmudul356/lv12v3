import {createApp} from 'vue';

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

app.use(router);
app.use(store);
app.mount('#app');
