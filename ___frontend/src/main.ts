
import { createApp } from 'vue'
import { createPinia } from 'pinia'

// @ts-ignore
import App from './App.vue'
import router from './router'

const app = createApp(App)

import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'

import './assets/custom.css'
import 'animate.css';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
app.component('VueDatePicker', VueDatePicker);

// npm install vue-toast-notification@^3.0
import 'vue-toast-notification/dist/theme-sugar.css';

import internetErrorComponent from '@/components/InternetError.vue'
app.component('internetErrorComponent', internetErrorComponent);

import emptyDataComponent from '@/components/emptyData.vue'
app.component('emptyDataComponent', emptyDataComponent);

import shareSite from '@/components/share.vue'
app.component('shareSite', shareSite);


import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
app.component('EasyDataTable', Vue3EasyDataTable);

// @ts-ignore
import VueWriter from "vue-writer";

// npm install vue3-touch-events
// import Vue3TouchEvents from "vue3-touch-events";
// app.use(Vue3TouchEvents);

// import Echo from "laravel-echo"
// import Pusher from 'pusher-js';
// import axiosManager from './store/axiosManager'
// // @ts-ignore
// window.Pusher = Pusher;
// // @ts-ignore
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'dlamfa',
//     wsHost: axiosManager.webSocketHost(),
//     wsPort: 6001,
//     cluster: "mt1",
//     forceTLS: false,
//     disableStats: true,
//     // authEndpoint: 'http://127.0.0.1:8000/api/broadcasting/auth',
//     auth: {
//         headers: {
//             Authorization: 'Bearer ' + localStorage.getItem('dlam_cup_T'),
//         }
//     },

// });


app.use(createPinia())
app.use(router)
app.use(VueWriter)

app.mount('#app')
