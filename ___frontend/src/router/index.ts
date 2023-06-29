import { createRouter, createWebHistory } from 'vue-router'
import { useAccount } from '@/store/accountStore';


import HomeView from '../views/HomeView.vue'
import AboutView from '../views/AboutView.vue'
import GeneralLayout from '../views/_layout.vue'
import PageNotFound from '../views/PageNotFound.vue'
import StatsView from '../views/StatsView/index.vue'

import UserLogin from '../views/Admin/LoginView.vue'
import userLayout from '../views/Admin/_Layout.vue'
import userDashboard from '../views/Admin/DashboardView.vue'
import userTeams from '../views/Admin/TeamsView.vue'
import userResults from '../views/Admin/ResultsView.vue'
import userSchedules from '../views/Admin/SchedulesView.vue'

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
        { path: 'about', name: 'About', component: AboutView },
        { path: 'stats/:tour_id', name: 'Tournament Stats', component: StatsView },
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

      ],
    },




    {
      path: '/:pathMatch(.*)*',
      name: 'Invalid',
      component: PageNotFound
    },
  ],
})

router.afterEach((to, from) => {
  document.title = "DLAM BISHOP'S CUP | " + to.name?.toString()
})

export default router