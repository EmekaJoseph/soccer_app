import { createRouter, createWebHistory } from 'vue-router'
import { useAccount } from '@/store/accountStore';


import HomeView from '../views/HomeView.vue'
import GeneralLayout from '../views/_layout.vue'
// import PageNotFound from '../views/PageNotFound.vue'
// import StatsView from '../views/StatsView/index.vue'

import UserLogin from '../views/Admin/UserLogin.vue'
import userLayout from '../views/Admin/_Layout.vue'
import userDashboard from '../views/Admin/UserDashboard.vue'
import userTeams from '../views/Admin/UserTeams.vue'
import userResults from '../views/Admin/UserResults.vue'
import userSchedules from '../views/Admin/UserSchedules.vue'
import userLive from '../views/Admin/UserLive.vue'
import userPredictions from '../views/Admin/UserPredictions.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior() {
    return {
      top: 0,
      behavior: 'smooth',
    }
  },
  linkActiveClass: 'active',
  routes: [
    {
      path: '/',
      component: GeneralLayout,
      children: [
        { path: '', name: 'Home', component: HomeView },
        { path: 'stats/:tour_id', name: 'Tournament Stats', component: () => import('../views/StatsView/index.vue') },
        { path: 'anthem', name: 'Diocesan Anthem', component: () => import('../views/anthem.vue') },
      ],
    },


    {
      path: '/user',
      name: 'User',
      component: UserLogin
    },


    {
      path: '/user',
      beforeEnter: (to, from, next) => {
        const account = useAccount()
        if (!account.state.id) {
          next({ path: '/user' });
        }
        else {
          next();
        }
      },
      component: userLayout,
      children: [
        { path: 'dashboard', name: 'Dashboard', component: userDashboard },
        { path: 'teams', name: 'Teams', component: userTeams },
        { path: 'schedules', name: 'Schedules', component: userSchedules },
        { path: 'results', name: 'Results', component: userResults },
        { path: 'live', name: 'Live', component: userLive },
        { path: 'predictions', name: 'Predictions', component: userPredictions },

      ],
    },

    {
      path: '/:pathMatch(.*)*',
      name: 'Invalid',
      component: () => import('../views/PageNotFound.vue')
    },
  ],
})

router.afterEach((to, from) => {
  document.title = "DLAM FOOTBALL ACADEMY | " + to.name?.toString()
})

export default router
