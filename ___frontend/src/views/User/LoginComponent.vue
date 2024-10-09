<template>
    <div class="min-vh-100 d-flex animate__animated animate__fadeIn justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="card border-0">
                <div class="card-header bg-transparent fs-5 text-center fw-bolder border-0">
                    Login to your Account
                </div>
                <div class="card-body">
                    <div v-show="form.formError" class="alert alert-danger border-0 text-center py-2" role="alert">
                        <strong>
                            <i class="bi bi-exclamation-circle-fill"></i> Incorrect Email/Password
                        </strong>
                    </div>

                    <form @submit.prevent="login" class="row g-4 mt-1">
                        <div class="col-12">
                            <div class="form-floating">
                                <input v-model="form.email" :class="{ 'form-error': form.emailError }" type="email"
                                    class="form-control form-control-lg" id="emailInput" placeholder="email">
                                <label for="emailInput">Email address</label>
                            </div>
                            <div class="small text-danger text-start">{{ form.emailError }}</div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input v-model="form.password" :type="form.pswordType"
                                        class="form-control  border-end-0 form-control-lg "
                                        :class="{ 'form-error': form.passwError }" id="passwInput"
                                        placeholder="password">
                                    <label for="passwInput">Password</label>
                                </div>

                                <div v-if="form.pswordType == 'password'" @click="swapPasFieldType"
                                    :class="{ 'form-error': form.passwError }"
                                    class="input-group-text small-text fw-bold bg-white border-start-0 cursor-pointer">
                                    <i class="bi bi-eye-slash"></i>
                                </div>
                                <div v-else @click="swapPasFieldType" :class="{ 'form-error': form.passwError }"
                                    class="input-group-text small-text fw-bold bg-white border-start-0 cursor-pointer">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </div>
                            <div class="small text-danger text-start">{{ form.passwError }}</div>
                        </div>
                        <div class="col-12 mt-4">
                            <button v-if="!form.isLoading" type="submit"
                                class="btn btn-primary-theme w-100 btn-lg">LOGIN</button>
                            <button v-else class="btn btn-primary-theme btn-lg w-100" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                Logining in..
                            </button>
                            <span class="float-end cursor-pointer mt-2 text-primary-theme fw-bolder">forgot
                                password?</span>
                        </div>

                        <div class="col-12 mt-5 text-center">
                            Create account?
                            <RouterLink class="text-decoration-none fw-bolder text-primary-theme" to="/register">
                                Register
                            </RouterLink>
                        </div>
                    </form>
                </div>
            </div>
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
    form.pswordType = form.pswordType == 'password' ? 'text' : 'password'
}


async function login() {
    form.emailError = '';
    form.passwError = '';
    form.formError = false;
    if (!form.email) {
        form.emailError = 'username is required';
        return;
    }

    if (!form.password) {
        form.passwError = 'password is required';
        return;
    }

    let obj = {
        email: form.email,
        password: form.password
    }

    form.isLoading = true
    try {
        let resp = await api.login(obj)
        if (resp.status == 203) {
            form.formError = true
            form.isLoading = false
            return;
        }

        form.email = ''
        form.password = ''
        const data = resp.data

        // account.state = resp.data
        // account.token = resp.data.token
        authStore.login(data)
        router.replace({ path: '/user/dashboard' })
        form.isLoading = false

    } catch (error) {
        alert('Network Error')
        form.isLoading = false
    }

}
</script>

<style scoped>
input:focus+.input-group-text {
    /* border-color: var(--theme-color-3) !important; */
    border-color: #dee2e6 !important;
}

.form-error,
.form-error+.input-group-text {
    border-color: var(--theme-color-2)
}
</style>