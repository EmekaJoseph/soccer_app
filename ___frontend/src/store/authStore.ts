import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
import Cookies from 'js-cookie'

export type Role = 'admin' | 'sub'

export interface SessionUser {
    id: number
    email: string
    firstname: string | null
    lastname?: string | null
    role: Role
}

export const useAuthStore = defineStore('authStore', () => {
    const tokenName: string = import.meta.env.VITE_TOKEN_NAME
    const token = ref<string>(Cookies.get(tokenName) ?? '')
    const storedUser = useStorage<string>('socc_user', '')

    const user = computed<SessionUser | null>(() => {
        try {
            return storedUser.value ? (JSON.parse(storedUser.value) as SessionUser) : null
        } catch {
            return null
        }
    })

    const isLoggedIn = computed(() => Boolean(token.value) && user.value !== null)
    const isAdmin = computed(() => user.value?.role === 'admin')
    const displayName = computed(() =>
        [user.value?.firstname, user.value?.lastname].filter(Boolean).join(' ') || user.value?.email || '',
    )

    function login(data: SessionUser & { token: string }) {
        const { token: newToken, ...profile } = data
        Cookies.set(tokenName, newToken, { expires: 7, sameSite: 'Lax', secure: location.protocol === 'https:' })
        token.value = newToken
        storedUser.value = JSON.stringify(profile)
    }

    function setProfile(profile: SessionUser) {
        storedUser.value = JSON.stringify(profile)
    }

    function logout() {
        Cookies.remove(tokenName)
        token.value = ''
        storedUser.value = ''
    }


    return { tokenName, user, isLoggedIn, isAdmin, displayName, login, setProfile, logout }
})
