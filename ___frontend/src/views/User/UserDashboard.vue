<template>
    <div class="container">

        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row justify-content-center gy-4">
                <div class="col-md-4">
                    <div class="card border-0  hover-tilt-Y">
                        <div class="card-body">
                            <div class="large-text">{{ userData.tournaments.length }}
                                <span class="fs-2 float-end bi bi-card-checklist text-danger-emphasis"></span>
                            </div>
                            <div class="small">{{ userData.tournaments.length == 1 ? 'Tournament' : 'Tournaments' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0  hover-tilt-Y">
                        <div class="card-body">
                            <div class="large-text">27
                                <span class="fs-2 float-end bi bi-people text-warning-emphasis"></span>
                            </div>
                            <div class="small">Visitors
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 hover-tilt-Y">
                        <div class="card-body">
                            <div class="large-text">10
                                <span class="fs-2 float-end bi bi-activity text-success-emphasis"></span>
                            </div>
                            <div class="small">Other Data
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card  border-0 ">
                        <div v-if="userData.tournaments.length" class="card-header text-muted  bg-transparent border-0">
                            TOURNAMENTS
                            <span class="badge rounded-pill text-bg-secondary fw-lighter xsmall ">
                                {{ userData.tournaments.length }}
                            </span>

                            <span v-if="!dataIsLoading && authStore.isAdmin" @click="openTourModal()"
                                class="float-end  hover-tilt-Y text-primary-theme cursor-pointer fw-bold">
                                <span class="">
                                    Add New Tournament <i class="bi bi-plus-lg"></i>
                                </span>
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- <fieldset class="border rounded-3 p-3  h-100"> -->
                            <!-- <legend class="text-muted float-none  small p-0 px-2 w-auto fw-bold">TOURNAMENTS
                            </legend> -->
                            <div class="content-panel">
                                <div class="col-md-12 mt-3">
                                    <div class="card  card-fixed-height">
                                        <componentLoadingSpinner v-if="dataIsLoading" />
                                        <div v-else class="card-body">
                                            <div class="d-flex justify-content-center align-items-center"
                                                v-if="!userData.tournaments.length">
                                                <div>
                                                    <div class="text-center">
                                                        <i style="font-size: 5rem;"
                                                            class="bi bi-database-fill-exclamation text-dim"></i>
                                                    </div>
                                                    <div class=" text-center text-dim fs-5">
                                                        You dont have any Tournaments,
                                                        <div v-if="!dataIsLoading && authStore.isAdmin"
                                                            @click="openTourModal()"
                                                            class="  hover-tilt-Y text-primary-theme cursor-pointer">

                                                            Click here create one
                                                        </div>.
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-else class="card-body p-0">
                                                <EasyDataTable class="border-0" :headers="headers"
                                                    :items="userData.tournaments" show-index>

                                                    <template #header="header">
                                                        <div class="fw-bolder">
                                                            {{ header.text == '#' ? 'S/N' : header.text }}</div>
                                                    </template>

                                                    <template #item-tour_logo="item">
                                                        <div class="image-circle"
                                                            :style="{ backgroundImage: `url(${hostURL}/${item.tour_logo ?? ''})` }">
                                                        </div>
                                                    </template>

                                                    <template #item-link="item">
                                                        <button @click="openTournamentLinkModal(item.tour_id)"
                                                            class="btn btn-link text-primary-theme btn-sm text-decoration-none border-0 p-0 m-0">
                                                            <i class="bi bi-box-arrow-up-right"></i> open
                                                        </button>
                                                    </template>


                                                    <template #item-edit="item">
                                                        <div class="operation-wrapper">
                                                            <span @click="openTourModal(true, item)"
                                                                class="me-4 operation-icon cursor-pointer">
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
                    <ComponentOtherUsers v-if="!dataIsLoading" />
                </div>

                <div v-if="authStore.getUserData().role == 'admin'" class="col-12 d-none">
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
    <tournamentFormModal v-if="newTournModal" :isEditing :editingData @close="closeTourModal"
        @done="userData.getTournaments()" />

</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import copyLinkModal from '@/components/modals/userModals/copyLinkModal.vue';
import tournamentFormModal from '@/components/modals/userModals/tournamentFormModal.vue';
import { useToast } from 'vue-toast-notification';
import { useAuthStore } from '@/store/authStore';
import ComponentOtherUsers from './ComponentOtherUsers.vue'

const authStore = useAuthStore()
const userData = useUserDataStore()

const $toast = useToast();

const newTournModal = ref<boolean>(false)
const isEditing = ref<boolean>(false)
const dataIsLoading = ref<boolean>(true)
const editingData = ref<any>(null)


const copyModal = ref<boolean>(false)
const linkToCopy = ref<string>('')

function openTournamentLinkModal(tour_id: string) {
    linkToCopy.value = `${window.location.host}/stats/${tour_id}`
    copyModal.value = true
}

function openTourModal(editing = false, editData = null) {
    isEditing.value = editing
    newTournModal.value = true
    editingData.value = editData
}

function closeTourModal() {
    isEditing.value = false
    newTournModal.value = false
    editingData.value = null
}

const hostURL = import.meta.env.VITE_API_URL;

onMounted(async () => {
    await userData.getTournaments()
    dataIsLoading.value = false
    userData.getSubUsers()
    getFeedbacks()
})


const headers: Header[] = [
    { text: "", value: "tour_logo" },
    { text: "Name", value: "tour_title" },
    { text: "TYPE", value: "tour_type" },
    { text: "CREATED", value: "created" },
    { text: "Link", value: "link" },
    { text: "Edit", value: "edit" },
    { text: "Delete", value: "delete" },
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


// feedbacks
const feedBackArray = ref<any[]>([])
async function getFeedbacks() {
    let resp = await api.getFeedbacks()
    feedBackArray.value = resp.data
}

</script>

<style scoped>
.bg-faint {
    background-color: rgba(17, 15, 15, 0.183);
}

.image-circle {
    height: 30px;
    width: 30px;
    border-radius: 50%;
    background-color: var(--bs-light-bg-subtle);
    border: 1px solid #e8e5e5;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}

.large-text {
    font-size: 2.3rem;
    font-weight: bolder;
}
</style>