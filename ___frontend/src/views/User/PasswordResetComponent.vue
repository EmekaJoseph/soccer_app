<template>
    <!-- Step 1: ask for the e-mail -->
    <AuthCard v-if="!hasToken" title="Forgot your password?" subtitle="We'll e-mail you a link to choose a new password">
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
                <button type="submit" :disabled="isLoading" class="ui-btn ui-btn-primary ui-btn-lg w-100">
                    {{ isLoading ? 'Sending…' : 'Send reset link' }}
                </button>
            </div>
            <div class="col-12 text-center">
                <RouterLink to="/login" class="small auth-link">Back to login</RouterLink>
            </div>
        </form>
    </AuthCard>

    <!-- Step 2: arrived from the e-mailed link -->
    <AuthCard v-else title="Choose a new password" :subtitle="`For ${route.query.email}`">
        <p v-if="authStore.isLoggedIn" class="small">Resetting your password will sign you out on every device, including this one.</p>
        <div v-if="formError" class="alert auth-alert small text-center py-2">{{ formError }}</div>

        <form class="row g-3" @submit.prevent="resetPassword">
            <div class="col-12">
                <PasswordField id="resetPass" v-model="password" label="New password" autocomplete="new-password" :minlength="8" required />
            </div>
            <div class="col-12">
                <PasswordField id="resetPass2" v-model="passwordConfirmation" label="Repeat password" autocomplete="new-password" :minlength="8" required />
            </div>
            <div class="col-12 mt-4">
                <button type="submit" :disabled="isLoading" class="ui-btn ui-btn-primary ui-btn-lg w-100">
                    {{ isLoading ? 'Saving…' : 'Reset password' }}
                </button>
            </div>
        </form>
    </AuthCard>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api, { apiErrorMessage } from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import { useUserDataStore } from '@/store/userDataStore'
import fx from '@/store/useFunctions'
import PasswordField from '@/components/PasswordField.vue'
import AuthCard from './AuthCard.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const userData = useUserDataStore()

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
        // The server has just revoked every session of this account.
        if (authStore.isLoggedIn) {
            authStore.logout()
            userData.reset()
        }
        fx.toast.success('Password reset. You can log in now.')
        router.replace('/login')
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isLoading.value = false
    }
}
</script>
