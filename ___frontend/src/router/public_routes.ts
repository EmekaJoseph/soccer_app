// import HomeView from '../views/HomeView.vue'
import HomeView from '../views/HomePage/index.vue'
import GeneralLayout from '../views/GeneralLayout.vue'
import { useAuthStore } from '@/store/authStore';

// @ts-ignore
const GUARD = (to, from, next) => {
    const authStore = useAuthStore()
    if (authStore.isLoggedIn) {
        next({ path: '/user/dashboard' });
    }
    else {
        next();
    }
}

export default [
    {
        path: '/',
        component: GeneralLayout,
        children: [
            {
                path: '', name: 'Home',
                component: HomeView
            },
            {
                path: 'stats/:tour_id', name: 'Tournament Stats',
                component: () => import('../views/StatsView/index.vue')
            },
            {
                path: 'anthem', name: 'Diocesan Anthem',
                component: () => import('../views/anthem.vue')
            },
            {
                path: 'login', beforeEnter: GUARD,
                name: 'Account Login', component: HomeView
            },

            {
                path: 'register', beforeEnter: GUARD,
                name: 'Account Register', component: HomeView
            },
        ],
    },


    {
        path: '/:pathMatch(.*)*',
        name: 'Invalid',
        component: () => import('../views/PageNotFound.vue')
    },
]