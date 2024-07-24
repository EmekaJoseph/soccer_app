import { useAuthStore } from '@/store/authStore';
import userLayout from '../views/User/UserLayout.vue'

// @ts-ignore
const GUARD = (to, from, next) => {

    const authStore = useAuthStore()
    if (!authStore.isLoggedIn) {
        next({ path: '/login' });
    }
    else {
        next();
    }
}

export default [
    {
        path: '/user',
        beforeEnter: GUARD,
        component: userLayout,
        children: [
            {
                path: 'dashboard', name: 'Dashboard',
                component: () => import('../views/User/UserDashboard.vue'),
            },
            {
                path: 'teams', name: 'Teams',
                component: () => import('../views/User/UserTeams.vue'),
            },
            {
                path: 'matches', name: 'Matches',
                component: () => import('../views/User/UserMatches.vue'),
            },
            {
                path: 'results', name: 'Results',
                component: () => import('../views/User/UserResults.vue'),
            },
            {
                path: 'live', name: 'Live',
                component: () => import('../views/User/UserLive.vue'),
            },
            {
                path: 'predictions', name: 'Predictions',
                component: () => import('../views/User/UserPredictions.vue'),
            },

        ],
    },
]