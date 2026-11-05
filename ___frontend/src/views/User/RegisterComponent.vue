<template>
    <AuthCard title="Create your account" subtitle="Set up your first tournament in minutes">
        <div v-if="formError" class="alert auth-alert small text-center py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ formError }}
        </div>

        <form @submit.prevent="register" class="row g-3" novalidate>
            <div class="col-12">
                <div class="form-floating">
                    <input v-model.trim="form.name" :class="{ 'is-invalid': errors.name }" type="text"
                        class="form-control" id="nameInput" placeholder="John Doe" autocomplete="name">
                    <label for="nameInput">Full name</label>
                </div>
                <div class="small text-danger mt-1" v-if="errors.name">{{ errors.name }}</div>
            </div>

            <div class="col-12">
                <div class="form-floating">
                    <input v-model.trim="form.email" :class="{ 'is-invalid': errors.email }" type="email"
                        class="form-control" id="regEmailInput" placeholder="name@example.com" autocomplete="email">
                    <label for="regEmailInput">Email address</label>
                </div>
                <div class="small text-danger mt-1" v-if="errors.email">{{ errors.email }}</div>
            </div>

            <div class="col-12">
                <PasswordField id="regPasswInput" v-model="form.password" label="Password" autocomplete="new-password" :invalid="!!errors.password" />
            </div>

            <div class="col-12">
                <PasswordField id="repeatPasswInput" v-model="form.password_confirmation" label="Repeat password" autocomplete="new-password" :invalid="!!errors.password" />
            </div>
            <div class="col-12 small text-danger" v-if="errors.password">{{ errors.password }}</div>

            <div class="col-12 mt-4">
                <button :disabled="isLoading" type="submit"
                    class="ui-btn ui-btn-primary ui-btn-lg w-100">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    {{ isLoading ? 'Creating account…' : 'Create account' }}
                </button>
            </div>

            <div class="col-12 mt-4 text-center">
                <p class="small mb-0">
                    Already have an account?
                    <RouterLink class="auth-link ms-1" to="/login">Log in</RouterLink>
                </p>
            </div>
        </form>
    </AuthCard>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api, { apiErrorMessage } from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import fx from '@/store/useFunctions'
import PasswordField from '@/components/PasswordField.vue'
import AuthCard from './AuthCard.vue'

const authStore = useAuthStore()
const router = useRouter()

const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })
const errors = reactive({ name: '', email: '', password: '' })
const formError = ref('')
const isLoading = ref(false)

function validate() {
    errors.name = form.name ? '' : 'Name is required'
    errors.email = !form.email ? 'Email is required' : !fx.isValidEmail(form.email) ? 'Enter a valid email' : ''
    errors.password = form.password.length < 8
        ? 'Password must be at least 8 characters'
        : form.password !== form.password_confirmation ? 'Passwords do not match' : ''
    return !errors.name && !errors.email && !errors.password
}

async function register() {
    formError.value = ''
    if (!validate()) return

    const [firstname, ...rest] = form.name.split(/\s+/)
    isLoading.value = true
    try {
        const { data } = await api.register({
            firstname,
            lastname: rest.join(' ') || null,
            email: form.email,
            password: form.password,
            password_confirmation: form.password_confirmation,
        })
        authStore.login(data)
        fx.toast.success('Welcome! Create your first tournament to get started.')
        router.replace('/user/dashboard')
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isLoading.value = false
    }
}
</script>
