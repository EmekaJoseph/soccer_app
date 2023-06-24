import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from '../views/AboutView.vue'
import GeneralLayout from '../views/_layout.vue'
import PageNotFound from '../views/PageNotFound.vue'
import StatsView from '../views/StatsView/index.vue'

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
