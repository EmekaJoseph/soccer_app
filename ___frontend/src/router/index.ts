import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore, type Role } from '@/store/authStore'

import public_routes from './public_routes'
import user_routes from './user_routes'

declare module 'vue-router' {
  interface RouteMeta {
    /** Only signed-in accounts may open the route. */
    requiresAuth?: boolean
    /** Only signed-out visitors (login/register) may open the route. */
    guestOnly?: boolean
    /** Restrict to these roles; omitted = any signed-in account. */
    roles?: Role[]
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior: () => ({ top: 0, behavior: 'smooth' }),
  linkActiveClass: 'active',
  routes: [...user_routes, ...public_routes],
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.matched.some((r) => r.meta.requiresAuth) && !auth.isLoggedIn) {
    return { path: '/login', query: { redirect: to.fullPath } }
  }

  if (to.meta.guestOnly && auth.isLoggedIn) {
    return { path: '/user/dashboard' }
  }

  if (to.meta.roles && auth.user && !to.meta.roles.includes(auth.user.role)) {
    return { path: '/user/dashboard' }
  }
})

router.afterEach((to) => {
  document.title = `SOCC | ${String(to.name ?? '')}`
})

export default router
