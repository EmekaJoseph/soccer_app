
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

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
app.component('VueDatePicker', VueDatePicker);


// https://swiperjs.com/element
import 'swiper/css';

// npm install vue-toast-notification@^3.0
import 'vue-toast-notification/dist/theme-sugar.css';

import internetErrorComponent from '@/components/InternetError.vue'
app.component('internetErrorComponent', internetErrorComponent);

import emptyDataComponent from '@/components/emptyData.vue'
app.component('emptyDataComponent', emptyDataComponent);

// import shareSite from '@/components/share.vue'
// app.component('shareSite', shareSite);


import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
app.component('EasyDataTable', Vue3EasyDataTable);

// @ts-ignore
import VueWriter from "vue-writer";

import Echo from "laravel-echo"
import Pusher from 'pusher-js';
import axiosManager from './store/axiosManager'
// @ts-ignore
window.Pusher = Pusher;
// @ts-ignore
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: axiosManager.webSocketKey(),
    cluster: "mt1",
    forceTLS: true, //true on production

    //############## ONLY for development ##################
    // wsHost: axiosManager.webSocketHost(),
    // wsPort: 6001,
    // disableStats: true,
    //################ ONLY for development #################

    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('dlam_cup_T'),
        }
    },

});


app.use(createPinia())
app.use(router)
app.use(VueWriter)

app.mount('#app')
