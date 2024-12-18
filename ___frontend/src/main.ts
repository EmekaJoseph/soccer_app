
import { createApp } from 'vue'
import { createPinia } from 'pinia'

// @ts-ignore
import App from './App.vue'
import router from './router'

const app = createApp(App)

import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'
import 'animate.css';
import './assets/custom.css'

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
app.component('VueDatePicker', VueDatePicker);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// https://primevue.org/installation/
// import PrimeVue from 'primevue/config';
// import 'primevue/resources/themes/lara-light-teal/theme.css'


// https://vue3paystack.netlify.app/guide/

// https://swiperjs.com/element
// import 'swiper/css';

//https://aesoper101.github.io/vue3-colorpicker/?path=/docs/example-introduction--docs

// npm install vue-toast-notification@^3.0
import 'vue-toast-notification/dist/theme-sugar.css';

import internetErrorComponent from '@/components/InternetError.vue'
app.component('internetErrorComponent', internetErrorComponent);

import emptyDataComponent from '@/components/emptyData.vue'
app.component('emptyDataComponent', emptyDataComponent);

import tourDropdownSelect from '@/components/tourDropdownSelect.vue';
app.component('tourDropdownSelect', tourDropdownSelect);

import componentLoadingSpinner from '@/components/componentLoadingSpinner.vue';
app.component('componentLoadingSpinner', componentLoadingSpinner);

// import shareSite from '@/components/share.vue'
// app.component('shareSite', shareSite);

// @ts-ignore
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
app.component('EasyDataTable', Vue3EasyDataTable);

// @ts-ignore
import VueWriter from "vue-writer";
// @ts-ignore
import Cookies from 'js-cookie';

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
            Authorization: 'Bearer ' + Cookies.get(import.meta.env.VITE_TOKEN_NAME),
        }
    },

});


app.use(createPinia())
app.use(router)
app.use(VueSweetalert2)
// app.use(PrimeVue);
app.use(VueWriter)

app.mount('#app')
