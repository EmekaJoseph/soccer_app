<template>
    <div class="animate__animated animate__fadeIn">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="/icons/soccer.svg" width="50" alt="" class="mb-3 animate-float">
                <h2 class="text-white fw-bold mb-1">Create Account</h2>
                <p class="text-white-50 small">Join our community and manage your tournaments like a pro</p>
            </div>

            <form @submit.prevent="register" class="row g-3">
                <div class="col-12">
                    <div class="form-floating custom-form-floating">
                        <input v-model="form.name" :class="{ 'is-invalid': form.nameError }" type="text"
                            class="form-control" id="nameInput" placeholder="John Doe">
                        <label for="nameInput" class="text-white-50">Full Name</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating custom-form-floating">
                        <input v-model="form.email" :class="{ 'is-invalid': form.emailError }" type="email"
                            class="form-control" id="emailInput" placeholder="name@example.com">
                        <label for="emailInput" class="text-white-50">Email address</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating custom-form-floating">
                        <input v-model="form.password" :type="form.pswordType" class="form-control"
                            :class="{ 'is-invalid': form.passwError }" id="passwInput" placeholder="password">
                        <label for="passwInput" class="text-white-50">Password</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating custom-form-floating">
                        <input v-model="form.password_confirmation" :type="form.pswordType" class="form-control"
                            :class="{ 'is-invalid': form.passwError }" id="repeatPasswInput" placeholder="password">
                        <label for="repeatPasswInput" class="text-white-50">Repeat Password</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button :disabled="form.isLoading" type="submit"
                        class="btn btn-primary-theme w-100 py-3 fw-bold rounded-3 shadow-sm hover-tilt-Y">
                        <span v-if="!form.isLoading">CREATE ACCOUNT</span>
                        <span v-else>
                            <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                            CREATING ACCOUNT...
                        </span>
                    </button>
                </div>

                <div class="col-12 mt-4 text-center">
                    <p class="text-white-50 small mb-0">
                        Already have an account?
                        <RouterLink class="text-gradient fw-bold text-decoration-none ms-1" to="/login">
                            Login
                        </RouterLink>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore';

const authStore = useAuthStore()
const router = useRouter();

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    pswordType: 'password',
    isLoading: false,
    nameError: '',
    emailError: '',
    passwError: ''
})

async function register() {
    form.nameError = '';
    form.emailError = '';
    form.passwError = '';

    if (!form.name) {
        form.nameError = 'Name is required';
        return;
    }
    if (!form.email) {
        form.emailError = 'Email is required';
        return;
    }
    if (!form.password) {
        form.passwError = 'Password is required';
        return;
    }

    form.isLoading = true
    try {
        let resp = await api.login({
            email: form.email,
            password: form.password
        })

        if (resp.status === 203) {
            alert('invalid details')
            form.isLoading = false
            return;
        }

        authStore.login(resp.data)
        router.replace({ path: '/user/dashboard' })
    } catch (error) {
        alert('Network Error')
    } finally {
        form.isLoading = false
    }
}
</script>

<style scoped>
.custom-form-floating .form-control {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    border-radius: 12px;
}

.custom-form-floating .form-control:focus {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: var(--accent-color) !important;
    box-shadow: 0 0 0 4px rgba(0, 242, 254, 0.1) !important;
}

.is-invalid {
    border-color: #ff4d4d !important;
}

.btn-primary-theme {
    background: var(--accent-gradient) !important;
    border: none !important;
    color: #000 !important;
    letter-spacing: 0.5px;
}

.text-gradient {
    background: var(--accent-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
