<template>
    <div class="container">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row justify-content-center gy-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header text-muted fw-bold bg-transparent border-0">
                            TOURNAMENTS
                            <span v-if="authStore.getUserData().role == 'admin'" @click="newTournModal = true"
                                class="float-end  hover-tilt-Y text-primary-theme cursor-pointer fw-bold">
                                <span class="">
                                    Create <i class="bi bi-plus-circle"></i>
                                </span>
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- <fieldset class="border rounded-3 p-3  h-100"> -->
                            <!-- <legend class="text-muted float-none  small p-0 px-2 w-auto fw-bold">TOURNAMENTS
                            </legend> -->
                            <!-- <div v-if="account.state.role == 'admin'"
                                class="d-flex justify-content-end col-12 mb-3  hover-tilt-Y">
                                <span @click="newTournModal = true" class="text-primary cursor-pointer">
                                    New Tournament <i class="bi bi-plus-circle"></i>
                                </span>
                            </div> -->
                            <div class="content-panel">
                                <div class="col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div v-if="!userData.tournaments.length" class="card-body text-center">
                                                You dont have any Tournaments, create One.
                                            </div>
                                            <div v-else class="card-body p-0">
                                                <EasyDataTable class="border-0" :headers="headers"
                                                    :items="userData.tournaments" show-index>

                                                    <template #header="header">
                                                        <div class="fw-bolder">
                                                            {{ header.text == '#' ? 'S/N' : header.text }}</div>
                                                    </template>

                                                    <template #item-link="item">
                                                        <button @click="openTournamentLinkModal(item.id)"
                                                            class="btn btn-link text-primary-theme btn-sm text-decoration-none border-0 p-0 m-0">
                                                            Open
                                                        </button>
                                                    </template>


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
                                                            <span @click="deleteTournament(item.id)"
                                                                class="operation-icon cursor-pointer">
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
                            <!-- </fieldset> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm  h-100 border-0">
                        <div class="card-header text-muted fw-bold bg-transparent border-0">
                            OTHER USERS
                        </div>
                        <div class="card-body">
                            <!-- <fieldset v-if="account.state.role == 'admin'" class="border rounded-3 p-3"> -->
                            <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bold">OTHER USERS
                            </legend> -->
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
                                                class="btn btn-primary-theme float-end w-100">
                                                Click to Add
                                            </button>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <div class="card">
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
                </div>

                <div v-if="authStore.getUserData().role == 'admin'" class="col-12">
                    <div class="card  shadow-sm border-0">
                        <div class="card-header text-muted fw-bold bg-transparent border-0">
                            FEEDBACKS
                        </div>
                        <div class="card-body">
                            <!-- <fieldset class="border rounded-3 p-3  h-100"> -->
                            <!-- <legend class="text-muted float-none  p-0 px-2 w-auto small fw-bold">
                                FEEDBACKS
                            </legend> -->

                            <div class="content-panel">
                                <div class="col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div v-if="!feedBackArray.length" class="card-body text-center">
                                                No Feedbacks
                                            </div>
                                            <div v-else class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-sm small">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Name</th>
                                                                <th>FeedBack</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(feedback, index) in feedBackArray" :key="index">
                                                                <th>{{ (index + 1) }}</th>
                                                                <td>{{ feedback.name ?? '-' }}</td>
                                                                <td>{{ feedback.feedbackText }}</td>
                                                                <td>{{ new
            Date(feedback.created_at).toLocaleString() }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </fieldset> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- linkModal -->
    <copyLinkModal v-if="copyModal" :link-to-copy="linkToCopy" @close="copyModal = false" />
    <newTournamentModal v-if="newTournModal" @close="newTournModal = false" @done="  userData.getTournaments()" />

</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import copyLinkModal from '@/components/modals/userModals/copyLinkModal.vue';
import newTournamentModal from '@/components/modals/userModals/newTournamentModal.vue';
import { useToast } from 'vue-toast-notification';
import { useAuthStore } from '@/store/authStore';

const authStore = useAuthStore()
const userData = useUserDataStore()

const $toast = useToast();

const copyModal = ref<boolean>(false)
const newTournModal = ref<boolean>(false)
const linkToCopy = ref<string>('')

function openTournamentLinkModal(id: string) {
    linkToCopy.value = `${window.location.host}/stats/${id}`
    copyModal.value = true
}



const feedBackArray = ref<any[]>([])

onMounted(() => {
    userData.getTournaments()
    userData.getSubUsers()
    getFeedbacks()
})

async function getFeedbacks() {
    let resp = await api.getFeedbacks()
    feedBackArray.value = resp.data
}

const headers: Header[] = [
    { text: "Name", value: "title" },
    { text: "TYPE", value: "type" },
    { text: "DATED CREATED", value: "created" },
    { text: "Link", value: "link" },
    // { text: "", value: "edit" },
    { text: "", value: "delete" },
];

async function deleteTournament(id: string | number) {
    if (confirm('Dou you really want to delete this Record?')) {

        try {
            let resp = await api.deleteTournament(id)
            if (resp.status == 203) {
                $toast.warning('You must Delete Teams first!', { position: 'top-right' });
                return;
            }
            $toast.success('Tournament Deleted Successfuly', { position: 'top-right' });
            userData.getTournaments()

        } catch (error) {
            console.log(error);
            $toast.error('Network Error', { position: 'top-right' });
        }
    }
}







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