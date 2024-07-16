<template>
    <div class="container">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row justify-content-center gy-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 h-100">
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
                                                        <button @click="openTournamentLinkModal(item.tour_id)"
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
                                                            <span @click="deleteTournament(item.tour_id)"
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
                    <ComponentOtherUsers/>
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
    <newTournamentModal v-if="newTournModal" @close="newTournModal = false" @done="userData.getTournaments()" />

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
import ComponentOtherUsers from './ComponentOtherUsers.vue'

const authStore = useAuthStore()
const userData = useUserDataStore()

const $toast = useToast();

const copyModal = ref<boolean>(false)
const newTournModal = ref<boolean>(false)
const linkToCopy = ref<string>('')

function openTournamentLinkModal(tour_id: string) {
    linkToCopy.value = `${window.location.host}/stats/${tour_id}`
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
    { text: "Name", value: "tour_title" },
    { text: "TYPE", value: "tour_type" },
    { text: "DATED CREATED", value: "created" },
    { text: "Link", value: "link" },
    // { text: "", value: "edit" },
    { text: "", value: "delete" },
];

async function deleteTournament(tour_id: string | number) {
    if (confirm('Dou you really want to delete this Record?')) {

        try {
            let resp = await api.deleteTournament(tour_id)
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

</script>

<style scoped>
.bg-faint {
    background-color: rgba(17, 15, 15, 0.183);
}
</style>