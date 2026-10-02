import type { RouteRecordRaw } from 'vue-router'
import type { Role } from '@/store/authStore'
import userLayout from '../views/User/UserLayout.vue'

export interface MenuItem {
    name: string
    icon: string
    link: string
    roles: Role[]
}

const everyone: Role[] = ['admin', 'sub']
const ownerOnly: Role[] = ['admin']

/** Sidebar / offcanvas menu, in display order. Roles mirror the API's "admin" middleware. */
export const menuItems: MenuItem[] = [
    { name: 'Dashboard', icon: 'bi bi-view-stacked', link: '/user/dashboard', roles: everyone },
    { name: 'Teams', icon: 'bi bi-people', link: '/user/teams', roles: ownerOnly },
    { name: 'Players', icon: 'bi bi-person-badge', link: '/user/players', roles: ownerOnly },
    { name: 'Matches', icon: 'bi bi-calendar2-event', link: '/user/matches', roles: everyone },
    { name: 'Results', icon: 'bi bi-list-check', link: '/user/results', roles: everyone },
    { name: 'Live', icon: 'bi bi-broadcast', link: '/user/live', roles: everyone },
    { name: 'Predictions', icon: 'bi bi-command', link: '/user/predictions', roles: ownerOnly },
    { name: 'Account', icon: 'bi bi-person-gear', link: '/user/account', roles: everyone },
]

const page = (name: string, path: string, roles: Role[], component: RouteRecordRaw['component']) =>
    ({ path, name, component, meta: { roles } }) as RouteRecordRaw

export default [
    {
        path: '/user',
        component: userLayout,
        meta: { requiresAuth: true },
        redirect: '/user/dashboard',
        children: [
            page('Dashboard', 'dashboard', everyone, () => import('../views/User/UserDashboard.vue')),
            page('Teams', 'teams', ownerOnly, () => import('../views/User/UserTeams.vue')),
            page('Players', 'players', ownerOnly, () => import('../views/User/UserPlayers.vue')),
            page('Matches', 'matches', everyone, () => import('../views/User/UserMatches.vue')),
            page('Results', 'results', everyone, () => import('../views/User/UserResults.vue')),
            page('Live', 'live', everyone, () => import('../views/User/UserLive.vue')),
            page('Predictions', 'predictions', ownerOnly, () => import('../views/User/UserPredictions.vue')),
            page('Account', 'account', everyone, () => import('../views/User/UserAccount.vue')),
        ],
    },
] satisfies RouteRecordRaw[]
