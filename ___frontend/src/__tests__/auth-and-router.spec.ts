import { beforeEach, describe, expect, it } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import Cookies from 'js-cookie'
import { useAuthStore } from '@/store/authStore'
import router from '@/router'

const owner = { id: 1, email: 'owner@example.com', firstname: 'Ada', lastname: 'Lovelace', role: 'admin' as const, token: 'owner-token' }
const scorer = { ...owner, email: 'scorer@example.com', firstname: null, lastname: null, role: 'sub' as const, token: 'sub-token' }

describe('auth store', () => {
    beforeEach(() => {
        localStorage.clear()
        Cookies.remove(import.meta.env.VITE_TOKEN_NAME)
        setActivePinia(createPinia())
    })

    it('logs in and out', () => {
        const auth = useAuthStore()
        expect(auth.isLoggedIn).toBe(false)

        auth.login(owner)
        expect(auth.isLoggedIn).toBe(true)
        expect(auth.isAdmin).toBe(true)
        expect(auth.displayName).toBe('Ada Lovelace')
        expect(auth.user).not.toHaveProperty('token') // the token lives only in the cookie

        auth.logout()
        expect(auth.isLoggedIn).toBe(false)
        expect(auth.user).toBeNull()
    })

    it('treats sub-users as non-admins and falls back to e-mail for the name', () => {
        const auth = useAuthStore()
        auth.login(scorer)
        expect(auth.isAdmin).toBe(false)
        expect(auth.displayName).toBe('scorer@example.com')
    })

    it('survives corrupt stored profile data', () => {
        localStorage.setItem('socc_user', '{not json')
        expect(useAuthStore().user).toBeNull()
    })
})

describe('router guards', () => {
    beforeEach(async () => {
        localStorage.clear()
        Cookies.remove(import.meta.env.VITE_TOKEN_NAME)
        setActivePinia(createPinia())
    })

    it('sends guests to login with a redirect back', async () => {
        await router.push('/user/teams')
        expect(router.currentRoute.value.path).toBe('/login')
        expect(router.currentRoute.value.query.redirect).toBe('/user/teams')
    })

    it('keeps scorers out of owner-only pages', async () => {
        useAuthStore().login(scorer)
        await router.push('/user/teams')
        expect(router.currentRoute.value.path).toBe('/user/dashboard')

        await router.push('/user/live')
        expect(router.currentRoute.value.path).toBe('/user/live')
    })

    it('lets owners in and keeps signed-in users off the login page', async () => {
        useAuthStore().login(owner)
        await router.push('/user/predictions')
        expect(router.currentRoute.value.path).toBe('/user/predictions')

        await router.push('/login')
        expect(router.currentRoute.value.path).toBe('/user/dashboard')
    })
})
