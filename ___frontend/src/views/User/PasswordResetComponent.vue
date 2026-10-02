<template>
    <!-- Step 1: ask for the e-mail -->
    <AuthCard v-if="!hasToken" title="Forgot Password" subtitle="We'll e-mail you a link to choose a new password">
        <div v-if="message" class="alert auth-success small text-center py-2">{{ message }}</div>
        <div v-if="formError" class="alert auth-alert small text-center py-2">{{ formError }}</div>

        <form class="row g-3" @submit.prevent="requestLink">
            <div class="col-12">
                <div class="form-floating">
                    <input v-model.trim="email" type="email" class="form-control" id="forgotEmail" placeholder="name@example.com" required autocomplete="email">
                    <label for="forgotEmail">Email address</label>
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" :disabled="isLoading" class="btn btn-primary-theme w-100 py-3 fw-bold rounded-3">
                    {{ isLoading ? 'SENDING...' : 'SEND RESET LINK' }}
                </button>
            </div>
            <div class="col-12 text-center">
                <RouterLink to="/login" class="small text-gradient fw-bold text-decoration-none">Back to login</RouterLink>
            </div>
        </form>
    </AuthCard>

    <!-- Step 2: arrived from the e-mailed link -->
    <AuthCard v-else title="Choose a New Password" :subtitle="`For ${route.query.email}`">
        <div v-if="formError" class="alert auth-alert small text-center py-2">{{ formError }}</div>

        <form class="row g-3" @submit.prevent="resetPassword">
            <div class="col-md-6">
                <div class="form-floating">
                    <input v-model="password" type="password" class="form-control" id="resetPass" placeholder="password" minlength="8" required autocomplete="new-password">
                    <label for="resetPass">New password</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input v-model="passwordConfirmation" type="password" class="form-control" id="resetPass2" placeholder="password" minlength="8" required autocomplete="new-password">
                    <label for="resetPass2">Repeat password</label>
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" :disabled="isLoading" class="btn btn-primary-theme w-100 py-3 fw-bold rounded-3">
                    {{ isLoading ? 'SAVING...' : 'RESET PASSWORD' }}
                </button>
            </div>
        </form>
    </AuthCard>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import AuthCard from './AuthCard.vue'

const route = useRoute()
const router = useRouter()

const hasToken = computed(() => typeof route.query.token === 'string' && typeof route.query.email === 'string')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const message = ref('')
const formError = ref('')
const isLoading = ref(false)

async function requestLink() {
    formError.value = ''
    message.value = ''
    isLoading.value = true
    try {
        message.value = (await api.forgotPassword(email.value)).data.message
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isLoading.value = false
    }
}

async function resetPassword() {
    formError.value = ''
    if (password.value !== passwordConfirmation.value) {
        formError.value = 'The passwords do not match.'
        return
    }

    isLoading.value = true
    try {
        await api.resetPassword({
            email: route.query.email,
            token: route.query.token,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
        })
        fx.toast.success('Password reset — you can log in now.')
        router.replace('/login')
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isLoading.value = false
    }
}
</script>
