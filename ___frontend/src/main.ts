
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

// npm install vue-toast-notification@^3.0
import 'vue-toast-notification/dist/theme-sugar.css';

import internetErrorComponent from '@/components/InternetError.vue'
app.component('internetErrorComponent', internetErrorComponent);


import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
app.component('EasyDataTable', Vue3EasyDataTable);

app.use(createPinia())
app.use(router)

app.mount('#app')
