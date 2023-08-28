<template>
    <div class="container">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>

            <div class="row justify-content-center gy-4">
                <div class="col-lg-8">

                    <fieldset class="border rounded-3 p-3 bg-light-subtle h-100">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">TOURNAMENTS</legend>
                        <div v-if="account.state.role == 'admin'"
                            class="d-flex justify-content-end col-12 mb-3  hover-tilt-Y">
                            <span @click="form.isOpen = !form.isOpen" v-if="!form.isOpen"
                                class="text-primary cursor-pointer"> <i class="bi bi-plus-lg"></i> create
                            </span>
                            <span @click="form.isOpen = !form.isOpen" v-else class="text-danger cursor-pointer"><i
                                    class="bi bi-x-lg"></i>
                                close </span>
                        </div>
                        <div class="content-panel">
                            <form @submit.prevent="saveNewTournament" v-if="form.isOpen"
                                class="row justify-content-center gy-3">
                                <div class="col-md-5">
                                    <input v-model="form.tour_title" type="text" class="form-control" placeholder="title..">
                                </div>
                                <div class="col-md-4">
                                    <select v-model="form.tour_type" class="form-select">
                                        <option value="cup" selected>type: CUP</option>
                                        <option value="league">type: LEAGUE</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button :disabled="form.isSaving" type="submit" class="btn btn-primary w-100">add
                                        new</button>
                                </div>
                            </form>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div v-if="!userData.tournaments.length" class="card-body text-center">
                                            You dont have any Tournaments, create One.
                                        </div>
                                        <div v-else class="card-body p-0">
                                            <EasyDataTable class="border-0" :headers="headers" :items="userData.tournaments"
                                                show-index>

                                                <template #item-edit="item">
                                                    <div class="operation-wrapper">
                                                        <span data-bs-toggle="modal" data-bs-target="#editMaterial"
                                                            @click="" class="me-4 operation-icon cursor-pointer">
                                                            <i class="bi bi-pencil"></i>
                                                        </span>
                                                    </div>
                                                </template>

                                                <template #item-delete="item">
                                                    <div class="operation-wrapper">
                                                        <span @click="" class="operation-icon cursor-pointer">
                                                            <i class="bi bi-trash3 text-danger"></i>
                                                        </span>
                                                    </div>
                                                </template>

                                            </EasyDataTable>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-4">
                    <fieldset v-if="account.state.role == 'admin'" class="border rounded-3 p-3 bg-white shadow">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">USERS</legend>
                        <div class="content-panel">
                            <form @submit.prevent="">
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <input v-model="usersForm.email" type="text" class="form-control"
                                            placeholder="valid email">
                                    </div>
                                    <div class="col-lg-12">
                                        <input v-model="usersForm.password" type="text" class="form-control"
                                            placeholder="password">
                                    </div>
                                    <div class="col-lg-12 ">
                                        <button @click="submitUser" :disabled="usersForm.isSaving"
                                            class="btn btn-primary float-end w-100">
                                            <i class="bi bi-arrow-down "></i> Add
                                        </button>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div v-if="userData.subUsers.length" class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Email</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(user, i) in userData.subUsers" :key="user.user_id">
                                                                <th>{{ (i + 1) }}</th>
                                                                <td>{{ user.email }}</td>
                                                                <td @click="deleteUser(user.subuser_id)">
                                                                    <i class="cursor-pointer text-danger bi bi-x-lg"></i>
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
                    </fieldset>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';
import { useAccount } from '@/store/accountStore';

const account = useAccount()

const userData = useUserDataStore()
const $toast = useToast();

const form = reactive({
    isOpen: false,
    tour_title: '',
    tour_type: 'cup',
    isSaving: false
})

onMounted(() => {
    userData.getTournaments()
    userData.getSubUsers()
})

async function saveNewTournament() {
    if (!form.tour_title) {
        $toast.default('Enter Tournament Title', { position: 'top-right' });
        return;
    }

    form.isSaving = true;

    try {
        let resp = await api.createTournament(form)
        if (resp.status == 203) {
            $toast.warning('Title already exists', { position: 'top-right' });
            form.isSaving = false;
            return;
        }

        $toast.success('Added Successfuly', { position: 'top-right' });
        form.isSaving = false;
        userData.getTournaments()
        form.isOpen = false;
        form.tour_title = ''

    } catch (error) {
        console.log(error);

        $toast.error('Network Error', { position: 'top-right' });
        form.isSaving = false;
    }

}

const headers: Header[] = [
    { text: "Name", value: "title" },
    { text: "TYPE", value: "type" },
    { text: "CREATED", value: "created" },
    // { text: "", value: "edit" },
    // { text: "", value: "delete" },

];







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

<style scoped></style>