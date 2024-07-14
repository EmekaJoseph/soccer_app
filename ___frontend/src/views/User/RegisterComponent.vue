<template>
    <!-- <section class="bg-gradient"> -->
    <!-- <div class="container"> -->
    <div class="min-vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent fs-5 text-center fw-bolder border-0">
                    Create New Account
                </div>
                <div class="card-body">
                    <!-- <div class="text-center">Create Account</div> -->
                    <form @submit.prevent="login" class="row g-4 mt-1">
                        <div class="col-12">
                            <div class="form-floating">
                                <input v-model="form.email" :class="{ 'form-error': form.emailError }" type="text"
                                    class="form-control" id="nameInput" placeholder="name">
                                <label for="nameInput">Full Name</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input v-model="form.email" :class="{ 'form-error': form.emailError }" type="email"
                                    class="form-control" id="emailInput" placeholder="email">
                                <label for="emailInput">Email address</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input v-model="form.password" :type="form.pswordType" class="form-control "
                                    :class="{ 'form-error': form.passwError }" id="passwInput" placeholder="password">
                                <label for="passwInput">Password</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input v-model="form.password" :type="form.pswordType" class="form-control "
                                    :class="{ 'form-error': form.passwError }" id="passwInput" placeholder="password">
                                <label for="passwInput">Repeat Password</label>
                            </div>
                        </div>
                        <!-- <div class="col-12">
                                    <div class="input-group">
                                        <div class="form-floating">
                                            <input v-model="form.password" :type="form.pswordType"
                                                class="form-control  border-end-0 "
                                                :class="{ 'form-error': form.passwError }" id="passwInput"
                                                placeholder="password">
                                            <label for="passwInput">Password</label>
                                        </div>

                                        <div v-if="form.pswordType == 'password'" @click="swapPasFieldType"
                                            class="input-group-text small-text fw-bold bg-white border-start-0 cursor-pointer">
                                            <i class="bi bi-eye-slash"></i>
                                        </div>
                                        <div v-else @click="swapPasFieldType"
                                            class="input-group-text small-text fw-bold bg-white border-start-0 cursor-pointer">
                                            <i class="bi bi-eye"></i>
                                        </div>
                                    </div>
                                </div> -->
                        <div class="col-12 mt-4">
                            <button :disabled="form.isLoading" type="submit"
                                class="btn btn-primary-theme w-100 btn-lg">CREATE ACCOUNT</button>
                            <!-- <span class="float-end cursor-pointer">forgot password?</span> -->
                        </div>

                        <div class="col-12 mt-5 text-center">
                            Already have an account?
                            <RouterLink class="text-decoration-none text-primary-theme fw-bolder" to="/login">
                                Login
                            </RouterLink>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- </div>
    </section> -->
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
    passwError: ''
})

const swapPasFieldType = () => {
    form.pswordType = form.pswordType == 'password' ? 'text' : 'password'
}


async function login() {
    form.emailError = '';
    form.passwError = '';
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
            alert('invalid details')
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