import { createRouter, createWebHistory } from 'vue-router'


import public_routes from './public_routes';
import user_routes from './user_routes';

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
    ...public_routes,
    ...user_routes


  ],
})


router.afterEach((to, from) => {
  document.title = "SOCC | " + to.name?.toString()
})

export default router
