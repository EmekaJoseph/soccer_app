<template>
    <div class="container">
        <div class="min-vh-100 d-flex justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm rounded-">
                    <div class="card-header fs-4 text-center fw-bold border-0 py-3">
                        DLAMFA- <span class="text-muted">ADMIN</span>
                    </div>
                    <div class="card-body pt-5">
                        <form @submit.prevent="login" class="row gy-3">
                            <div class="col-12">
                                <input v-model="form.email" type="text" class="form-control form-control-lg rounded-0"
                                    :class="{ 'form-error': form.emailError }" placeholder="email">
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <input v-model="form.password" :type="form.pswordType"
                                        class="form-control rounded-0 border-end-0 form-control-lg "
                                        :class="{ 'form-error': form.passwError }" placeholder="password">
                                    <div v-if="form.pswordType == 'password'" @click="swapPasFieldType"
                                        class="input-group-text small-text fw-bold bg-white border-start-0 rounded-0 cursor-pointer">
                                        <i class="bi bi-eye-slash"></i>
                                    </div>
                                    <div v-else @click="swapPasFieldType"
                                        class="input-group-text small-text fw-bold bg-white border-start-0 rounded-0 cursor-pointer">
                                        <i class="bi bi-eye"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <button :disabled="form.isLoading" type="submit"
                                    class="btn btn-primary-theme w-100 btn-lg">LOGIN</button>
                                <!-- <span class="float-end cursor-pointer">forgot password?</span> -->
                            </div>

                            <div class="col-12 mt-5 text-center">
                                <RouterLink class="text-decoration-none" to="/">
                                    <i class="bi bi-chevron-left"></i> Homepage
                                </RouterLink>
                            </div>
                        </form>
                    </div>
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