<template>
    <div class="animate__animated animate__fadeIn">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="/icons/soccer.svg" width="50" alt="" class="mb-3 animate-float">
                <h2 class="text-white fw-bold mb-1">Welcome Back</h2>
                <p class="text-white-50 small">Enter your credentials to access your dashboard</p>
            </div>

            <div v-show="form.formError"
                class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small text-center py-2"
                role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Incorrect Email or Password
            </div>

            <form @submit.prevent="login" class="row g-3">
                <div class="col-12">
                    <div class="form-floating custom-form-floating">
                        <input v-model="form.email" :class="{ 'is-invalid': form.emailError }" type="email"
                            class="form-control" id="emailInput" placeholder="name@example.com">
                        <label for="emailInput" class="text-white-50">Email address</label>
                    </div>
                    <div class="small text-danger mt-1 text-start" v-if="form.emailError">{{ form.emailError }}</div>
                </div>

                <div class="col-12">
                    <div class="input-group custom-input-group">
                        <div class="form-floating flex-grow-1 custom-form-floating">
                            <input v-model="form.password" :type="form.pswordType" class="form-control border-end-0"
                                :class="{ 'is-invalid': form.passwError }" id="passwInput" placeholder="password">
                            <label for="passwInput" class="text-white-50">Password</label>
                        </div>
                        <span @click="swapPasFieldType"
                            class="input-group-text bg-transparent border-start-0 cursor-pointer text-white-50">
                            <i :class="form.pswordType === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </span>
                    </div>
                    <div class="small text-danger mt-1 text-start" v-if="form.passwError">{{ form.passwError }}</div>
                </div>

                <div class="col-12 d-flex justify-content-end mt-2">
                    <span class="cursor-pointer small text-gradient fw-bold">Forgot password?</span>
                </div>

                <div class="col-12 mt-4">
                    <button v-if="!form.isLoading" type="submit"
                        class="btn btn-primary-theme w-100 py-3 fw-bold rounded-3 shadow-sm hover-tilt-Y">
                        LOGIN TO ACCOUNT
                    </button>
                    <button v-else class="btn btn-primary-theme w-100 py-3 fw-bold rounded-3 shadow-sm" type="button"
                        disabled>
                        <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                        LOGGING IN...
                    </button>
                </div>

                <div class="col-12 mt-4 text-center">
                    <p class="text-white-50 small mb-0">
                        Don't have an account?
                        <RouterLink class="text-gradient fw-bold text-decoration-none ms-1" to="/register">
                            Create account
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
    email: '',
    password: '',
    pswordType: 'password',
    isLoading: false,
    emailError: '',
    passwError: '',
    formError: false,
})

const swapPasFieldType = () => {
    form.pswordType = form.pswordType === 'password' ? 'text' : 'password'
}

async function login() {
    form.emailError = '';
    form.passwError = '';
    form.formError = false;

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
            form.formError = true
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

.custom-input-group .input-group-text {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 0 12px 12px 0;
}

.custom-form-floating .form-control.border-end-0 {
    border-radius: 12px 0 0 12px;
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