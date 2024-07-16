<template>
    <div class="card shadow-sm  h-100 border-0">
    <div class="card-header text-muted fw-bold bg-transparent border-0">
       USERS
    </div>
    <div class="card-body" >
        <!-- <fieldset v-if="account.state.role == 'admin'" class="border rounded-3 p-3"> -->
        <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bold">OTHER USERS
        </legend> -->
        <div class="content-panel">
            <form @submit.prevent="">
                <div class="row g-3">
                    <div class="col-lg-12">
                        <input v-model="usersForm.email" type="email" class="form-control"
                            placeholder="valid email">
                    </div>
                    <div class="col-lg-12">
                        <input v-model="usersForm.password" type="text" class="form-control"
                            placeholder="password">
                    </div>
                    <div class="col-lg-12 ">
                        <button @click="submitUser" :disabled="usersForm.isSaving"
                            class="btn btn-primary-theme float-end w-100">
                            Click to Add
                        </button>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="card" style="height: 200px; overflow-y: auto;">
                            <div class="card-body">
                                <div v-if="userData.subUsers.length" class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>S/N</th>
                                                <th>Email</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(user, i) in userData.subUsers"
                                                :key="user.user_id">
                                                <th>{{ (i + 1) }}</th>
                                                <td>{{ user.email }}</td>
                                                <td @click="deleteUser(user.subuser_id)">
                                                    <i
                                                        class="cursor-pointer text-danger bi bi-x-lg"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else class="text-center small">No users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- </fieldset> -->
    </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';
import { useAuthStore } from '@/store/authStore';

const authStore = useAuthStore()
const userData = useUserDataStore()

const $toast = useToast();

onMounted(() => {
    userData.getSubUsers()
})


// ############################################ ADDING USERS
const usersForm = reactive({
    email: '',
    password: '',
    isSaving: false
})

function resetFrom() {
    usersForm.isSaving = false
    usersForm.email = ''
    usersForm.password = ''
}

async function submitUser() {
    if (usersForm.email && usersForm.password) {
        usersForm.isSaving = true
        try {
            await api.createSubUser(usersForm)
            userData.getSubUsers()
            resetFrom()
        } catch (error) {
            console.log(error);

            resetFrom()
        }
    }
}

async function deleteUser(id: any) {
    if (confirm('Delete this user')) {
        try {
            await api.deleteSubUser(id)
            userData.getSubUsers()
        } catch (error) {
            // console.log(error);
        }
    }
}
</script>

<style scoped>
.bg-faint {
    background-color: rgba(17, 15, 15, 0.183);
}
</style>