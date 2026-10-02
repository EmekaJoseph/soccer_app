import { createApp } from 'vue'
import { createPinia } from 'pinia'
// @ts-expect-error -- the package's typings omit its (real) default export
import Vue3EasyDataTable from 'vue3-easy-data-table'

import App from './App.vue'
import router from './router'

import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'animate.css'
import 'sweetalert2/dist/sweetalert2.min.css'
import 'vue-toast-notification/dist/theme-sugar.css'
import 'vue3-easy-data-table/dist/style.css'
import './assets/custom.css'
import './assets/admin.css'

import internetErrorComponent from '@/components/InternetError.vue'
import emptyDataComponent from '@/components/emptyData.vue'
import tourDropdownSelect from '@/components/tourDropdownSelect.vue'
import componentLoadingSpinner from '@/components/componentLoadingSpinner.vue'

const app = createApp(App)

app.component('internetErrorComponent', internetErrorComponent)
app.component('emptyDataComponent', emptyDataComponent)
app.component('tourDropdownSelect', tourDropdownSelect)
app.component('componentLoadingSpinner', componentLoadingSpinner)
app.component('EasyDataTable', Vue3EasyDataTable)

app.use(createPinia())
app.use(router)

app.mount('#app')
