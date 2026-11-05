<template>
    <AuthCard title="Welcome back" subtitle="Log in to manage your tournaments">
        <div v-if="formError" class="alert auth-alert small text-center py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ formError }}
        </div>

        <form @submit.prevent="login" class="row g-3" novalidate>
            <div class="col-12">
                <div class="form-floating">
                    <input v-model.trim="form.email" :class="{ 'is-invalid': errors.email }" type="email"
                        class="form-control" id="emailInput" placeholder="name@example.com" autocomplete="email">
                    <label for="emailInput">Email address</label>
                </div>
                <div class="small text-danger mt-1 text-start" v-if="errors.email">{{ errors.email }}</div>
            </div>

            <div class="col-12">
                <PasswordField id="passwInput" v-model="form.password" label="Password" autocomplete="current-password" :invalid="!!errors.password" />
                <div class="small text-danger mt-1 text-start" v-if="errors.password">{{ errors.password }}</div>
            </div>

            <div class="col-12 d-flex justify-content-end mt-2">
                <RouterLink to="/forgot-password" class="small auth-link">Forgot password?</RouterLink>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" :disabled="isLoading"
                    class="ui-btn ui-btn-primary ui-btn-lg w-100">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    {{ isLoading ? 'Logging in…' : 'Log in' }}
                </button>
            </div>

            <div class="col-12 mt-4 text-center">
                <p class="small mb-0">
                    Don't have an account?
                    <RouterLink class="auth-link ms-1" to="/register">Create account</RouterLink>
                </p>
            </div>
        </form>
    </AuthCard>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api, { apiErrorMessage } from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import fx from '@/store/useFunctions'
import PasswordField from '@/components/PasswordField.vue'
import AuthCard from './AuthCard.vue'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const form = reactive({ email: '', password: '' })
const errors = reactive({ email: '', password: '' })
const formError = ref('')
const isLoading = ref(false)

async function login() {
    errors.email = !form.email ? 'Email is required' : !fx.isValidEmail(form.email) ? 'Enter a valid email' : ''
    errors.password = form.password ? '' : 'Password is required'
    formError.value = ''
    if (errors.email || errors.password) return

    isLoading.value = true
    try {
        const { data } = await api.login({ email: form.email, password: form.password })
        authStore.login(data)
        const redirect = typeof route.query.redirect === 'string' && route.query.redirect.startsWith('/user')
            ? route.query.redirect
            : '/user/dashboard'
        router.replace(redirect)
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isLoading.value = false
    }
}
</script>
