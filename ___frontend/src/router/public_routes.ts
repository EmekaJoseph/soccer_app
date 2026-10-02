import type { RouteRecordRaw } from 'vue-router'
import HomeView from '../views/HomePage/index.vue'
import GeneralLayout from '../views/GeneralLayout.vue'

export default [
    {
        path: '/',
        component: GeneralLayout,
        children: [
            { path: '', name: 'Home', component: HomeView },
            { path: 'login', name: 'Account Login', component: HomeView, meta: { guestOnly: true } },
            { path: 'register', name: 'Account Register', component: HomeView, meta: { guestOnly: true } },
            { path: 'forgot-password', name: 'Forgot Password', component: HomeView, meta: { guestOnly: true } },
            { path: 'reset-password', name: 'Reset Password', component: HomeView, meta: { guestOnly: true } },
            {
                path: 'stats/:tour_id', name: 'Tournament Stats',
                component: () => import('../views/StatsView/index.vue'),
            },
            {
                path: 'anthem', name: 'Diocesan Anthem',
                component: () => import('../views/anthem.vue'),
            },
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'Page Not Found',
        component: () => import('../views/PageNotFound.vue'),
    },
] satisfies RouteRecordRaw[]
