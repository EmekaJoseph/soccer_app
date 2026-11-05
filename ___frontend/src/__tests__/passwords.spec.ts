import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import Cookies from 'js-cookie'
import api from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import router from '@/router'
import { useAuthStore } from '@/store/authStore'
import { useUserDataStore } from '@/store/userDataStore'
import PasswordField from '@/components/PasswordField.vue'
import PasswordResetComponent from '@/views/User/PasswordResetComponent.vue'
import LoginComponent from '@/views/User/LoginComponent.vue'
import RegisterComponent from '@/views/User/RegisterComponent.vue'
import ComponentOtherUsers from '@/views/User/ComponentOtherUsers.vue'

const owner = { id: 1, email: 'owner@example.com', firstname: 'Ada', lastname: null, role: 'admin' as const, token: 't' }
const fieldProps = { id: 'p', label: 'Password', autocomplete: 'new-password' }

beforeEach(() => {
    localStorage.clear()
    Cookies.remove(import.meta.env.VITE_TOKEN_NAME)
    setActivePinia(createPinia())
})
afterEach(() => vi.restoreAllMocks())

describe('password field', () => {
    it('hides the password until the eye is pressed, and can hide it again', async () => {
        const wrapper = mount(PasswordField, { props: fieldProps })
        const input = () => wrapper.find('input').attributes('type')

        expect(input()).toBe('password')
        await wrapper.find('button').trigger('click')
        expect(input()).toBe('text')
        expect(wrapper.find('button').attributes('aria-label')).toBe('Hide password')
        await wrapper.find('button').trigger('click')
        expect(input()).toBe('password')
    })

    it('never submits the form when the eye is pressed', () => {
        const wrapper = mount(PasswordField, { props: fieldProps })
        expect(wrapper.find('button').attributes('type')).toBe('button')
    })

    it('goes back to hidden once the form clears it', async () => {
        const wrapper = mount(PasswordField, { props: { ...fieldProps, modelValue: 'secret123' } })
        await wrapper.find('button').trigger('click')
        await wrapper.setProps({ modelValue: '' })
        expect(wrapper.find('input').attributes('type')).toBe('password')
    })
})

describe('forms use the floating eye', () => {
    const stubs = { AuthCard: { template: '<div><slot /></div>' }, RouterLink: true }

    it('login has it on its password field', () => {
        const wrapper = mount(LoginComponent, { global: { stubs, plugins: [router] } })
        expect(wrapper.findAll('.password-field .eye')).toHaveLength(1)
        expect(wrapper.find('.input-group').exists()).toBe(false)
    })

    it('register has it on both password fields', () => {
        const wrapper = mount(RegisterComponent, { global: { stubs, plugins: [router] } })
        expect(wrapper.findAll('.password-field .eye')).toHaveLength(2)
    })

    it('login still submits what was typed', async () => {
        const login = vi.spyOn(api, 'login').mockRejectedValue(new Error('stop here'))
        const wrapper = mount(LoginComponent, { global: { stubs, plugins: [router] } })
        await wrapper.find('#emailInput').setValue('owner@example.com')
        await wrapper.find('#passwInput').setValue('password1')
        await wrapper.find('form').trigger('submit')
        await flushPromises()
        expect(login).toHaveBeenCalledWith({ email: 'owner@example.com', password: 'password1' })
    })
})

describe('scorer form', () => {
    it('hides the password by default and does not offer the owner\'s saved one', () => {
        vi.spyOn(api, 'subUsersList').mockResolvedValue({ data: [] } as any)
        const wrapper = mount(ComponentOtherUsers, {
            global: { stubs: { AdminCard: { template: '<div><slot /></div>' } } },
        })
        const input = wrapper.find('.password-field input')
        expect(input.attributes('type')).toBe('password')
        expect(input.attributes('autocomplete')).toBe('new-password')
        expect(wrapper.find('.eye').exists()).toBe(true)
    })
})

describe('reset links', () => {
    const link = '/reset-password?token=abc&email=owner@example.com'

    it('open for a signed-in user instead of bouncing to the dashboard', async () => {
        useAuthStore().login(owner)
        await router.push(link)
        expect(router.currentRoute.value.path).toBe('/reset-password')
    })

    it('still keep signed-in users off the page when there is no token', async () => {
        useAuthStore().login(owner)
        await router.push('/reset-password')
        expect(router.currentRoute.value.path).toBe('/user/dashboard')
    })

    it('sign the user out after a successful reset, then go to login', async () => {
        const auth = useAuthStore()
        auth.login(owner)
        useUserDataStore().tournaments = [{ tour_id: 't1' }]
        await router.push(link)
        vi.spyOn(api, 'resetPassword').mockResolvedValue({ data: {} } as any)
        vi.spyOn(fx.toast, 'success').mockImplementation(() => ({}) as any)

        const wrapper = mount(PasswordResetComponent, {
            global: { plugins: [router], stubs: { AuthCard: { template: '<div><slot /></div>' }, RouterLink: true } },
        })
        expect(wrapper.text()).toContain('sign you out')

        await wrapper.find('#resetPass').setValue('newpassword1')
        await wrapper.find('#resetPass2').setValue('newpassword1')
        await wrapper.find('form').trigger('submit')
        await flushPromises()
        await router.isReady()

        expect(auth.isLoggedIn).toBe(false)
        expect(useUserDataStore().tournaments).toEqual([])
        expect(router.currentRoute.value.path).toBe('/login')
    })

    it('leave the session alone when the reset fails', async () => {
        const auth = useAuthStore()
        auth.login(owner)
        await router.push(link)
        vi.spyOn(api, 'resetPassword').mockRejectedValue(new Error('expired'))

        const wrapper = mount(PasswordResetComponent, {
            global: { plugins: [router], stubs: { AuthCard: { template: '<div><slot /></div>' }, RouterLink: true } },
        })
        await wrapper.find('#resetPass').setValue('newpassword1')
        await wrapper.find('#resetPass2').setValue('newpassword1')
        await wrapper.find('form').trigger('submit')
        await flushPromises()

        expect(auth.isLoggedIn).toBe(true)
        expect(router.currentRoute.value.path).toBe('/reset-password')
    })
})
