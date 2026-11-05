import type { RouteRecordRaw } from 'vue-router'
import type { Role } from '@/store/authStore'
import userLayout from '../views/User/UserLayout.vue'

export interface MenuItem {
    name: string
    icon: string
    link: string
    roles: Role[]
    /** Sidebar group; items without one sit at the top. */
    section?: string
    /** One line under the page title saying what the page is for. */
    hint: string
}

const everyone: Role[] = ['admin', 'sub']
const ownerOnly: Role[] = ['admin']

/** Sidebar / offcanvas menu, in display order. Roles mirror the API's "admin" middleware. */
export const menuItems: MenuItem[] = [
    { name: 'Dashboard', icon: 'bi bi-view-stacked', link: '/user/dashboard', roles: everyone, hint: 'Your tournaments at a glance' },
    { name: 'Teams', icon: 'bi bi-people', link: '/user/teams', roles: ownerOnly, section: 'Set up', hint: 'The teams taking part in a tournament' },
    { name: 'Players', icon: 'bi bi-person-badge', link: '/user/players', roles: ownerOnly, section: 'Set up', hint: 'Squads for each team' },
    { name: 'Matches', icon: 'bi bi-calendar2-event', link: '/user/matches', roles: everyone, section: 'Set up', hint: 'Schedule fixtures fans can follow' },
    { name: 'Live', icon: 'bi bi-broadcast', link: '/user/live', roles: everyone, section: 'Matchday', hint: 'Run the clock and score for fans in real time' },
    { name: 'Results', icon: 'bi bi-list-check', link: '/user/results', roles: everyone, section: 'Matchday', hint: 'Final scores, which update the standings' },
    { name: 'Predictions', icon: 'bi bi-trophy', link: '/user/predictions', roles: ownerOnly, section: 'Fans', hint: 'What fans predicted, and who got it right' },
    { name: 'Account', icon: 'bi bi-person-gear', link: '/user/account', roles: everyone, section: 'Settings', hint: 'Your profile and password' },
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
