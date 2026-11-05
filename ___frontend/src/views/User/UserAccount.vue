<template>
    <div class="row g-4">
        <div class="col-lg-6">
            <AdminCard title="Profile" class="h-100">
                <form class="row g-3" @submit.prevent="saveProfile">
                    <div class="col-12">
                        <label class="w-100">E-mail
                            <input :value="authStore.user?.email" class="form-control" disabled>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="w-100">First name
                            <input v-model.trim="profile.firstname" class="form-control" maxlength="100">
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="w-100">Last name
                            <input v-model.trim="profile.lastname" class="form-control" maxlength="100">
                        </label>
                    </div>
                    <div class="col-12 small">Role: {{ authStore.isAdmin ? 'Tournament owner' : 'Scorer' }}</div>
                    <div class="col-12">
                        <button type="submit" class="ui-btn ui-btn-primary" :disabled="savingProfile">
                            {{ savingProfile ? 'Saving…' : 'Save profile' }}
                        </button>
                    </div>
                </form>
            </AdminCard>
        </div>

        <div class="col-lg-6">
            <AdminCard title="Change password" class="h-100">
                <form class="row g-3" @submit.prevent="changePassword">
                    <div class="col-12">
                        <label class="w-100">Current password
                            <input v-model="password.current_password" type="password" class="form-control" autocomplete="current-password" required>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="w-100">New password
                            <input v-model="password.password" type="password" class="form-control" minlength="8" autocomplete="new-password" required>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="w-100">Repeat new password
                            <input v-model="password.password_confirmation" type="password" class="form-control" minlength="8" autocomplete="new-password" required>
                        </label>
                    </div>
                    <div v-if="passwordError" class="col-12 small text-danger">{{ passwordError }}</div>
                    <div class="col-12 small">Changing your password signs you out on other devices.</div>
                    <div class="col-12">
                        <button type="submit" class="ui-btn ui-btn-primary" :disabled="savingPassword">
                            {{ savingPassword ? 'Updating…' : 'Update password' }}
                        </button>
                    </div>
                </form>
            </AdminCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import fx from '@/store/useFunctions'

const authStore = useAuthStore()

const profile = reactive({ firstname: authStore.user?.firstname ?? '', lastname: authStore.user?.lastname ?? '' })
const savingProfile = ref(false)

const password = reactive({ current_password: '', password: '', password_confirmation: '' })
const savingPassword = ref(false)
const passwordError = ref('')

onMounted(async () => {
    try {
        const { data } = await api.me()
        authStore.setProfile(data)
        Object.assign(profile, { firstname: data.firstname ?? '', lastname: data.lastname ?? '' })
    } catch {
        // keep the cached profile
    }
})

async function saveProfile() {
    savingProfile.value = true
    try {
        const { data } = await api.updateProfile(profile)
        authStore.setProfile(data)
        fx.toast.success('Profile saved')
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    } finally {
        savingProfile.value = false
    }
}

async function changePassword() {
    passwordError.value = ''
    if (password.password !== password.password_confirmation) {
        passwordError.value = 'The new passwords do not match.'
        return
    }

    savingPassword.value = true
    try {
        await api.changePassword(password)
        fx.toast.success('Password updated')
        Object.assign(password, { current_password: '', password: '', password_confirmation: '' })
    } catch (error) {
        passwordError.value = apiErrorMessage(error)
    } finally {
        savingPassword.value = false
    }
}
</script>
