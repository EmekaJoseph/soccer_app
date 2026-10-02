<template>
    <div class="container">
        <internetErrorComponent v-if="userData.apiError && !userData.tournaments.length" />
        <div v-else class="row justify-content-center gy-4">
            <div class="col-12">
                <div class="welcome">
                    <div>
                        <h2 class="mb-1">{{ fx.greet() }}, {{ authStore.user?.firstname || 'coach' }} 👋</h2>
                        <div class="text-muted">Here's what's happening across your tournaments.</div>
                    </div>
                    <button v-if="authStore.isAdmin" class="btn btn-primary-theme px-3" @click="openTourModal()">
                        <i class="bi bi-plus-lg me-1"></i> New tournament
                    </button>
                </div>
            </div>

            <div v-for="card in statCards" :key="card.label" class="col-6 col-md-4 col-xl">
                <div class="card h-100 hover-tilt-Y">
                    <div class="stat-tile">
                        <span class="stat-icon" :class="card.tint"><i :class="card.icon"></i></span>
                        <div>
                            <div class="stat-value">{{ userData.dashboardFigures?.[card.key] ?? 0 }}</div>
                            <div class="stat-label">{{ card.label }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="authStore.isAdmin ? 'col-lg-8' : 'col-12'">
                <div class="card border-0">
                    <div class="card-header text-muted bg-transparent border-0">
                        TOURNAMENTS
                        <span class="badge rounded-pill text-bg-secondary fw-lighter xsmall">{{ userData.tournaments.length }}</span>
                        
                    </div>
                    <div class="card-body">
                        <div class="card-fixed-height">
                            <componentLoadingSpinner v-if="dataIsLoading" />
                            <div v-else-if="!userData.tournaments.length" class="card-body d-flex justify-content-center align-items-center">
                                <emptyDataComponent icon="bi bi-trophy">
                                    You don't have any tournaments yet.
                                    <div v-if="authStore.isAdmin" class="mt-3">
                                        <button @click="openTourModal()" class="btn btn-primary-theme btn-sm px-3">Create your first tournament</button>
                                    </div>
                                </emptyDataComponent>
                            </div>
                            <div v-else class="card-body p-0">
                                <EasyDataTable class="border-0" :headers="headers" :items="userData.tournaments" show-index>
                                    <template #header="header">
                                        <div class="fw-bolder">{{ header.text == '#' ? 'S/N' : header.text }}</div>
                                    </template>

                                    <template #item-tour_logo="item">
                                        <div class="image-circle" :style="{ backgroundImage: item.tour_logo ? `url(${fx.resolvePhotoSrc(item.tour_logo)})` : 'none' }">
                                            <i v-if="!item.tour_logo" class="bi bi-trophy"></i>
                                        </div>
                                    </template>

                                    <template #item-link="item">
                                        <button @click="openTournamentLinkModal(item.tour_id)"
                                            class="btn btn-link text-primary-theme btn-sm text-decoration-none border-0 p-0 m-0">
                                            <i class="bi bi-box-arrow-up-right"></i> Share
                                        </button>
                                    </template>

                                    <template #item-actions="item">
                                        <div v-if="authStore.isAdmin" class="d-flex gap-2 justify-content-end">
                                            <button class="btn-icon" title="Edit" @click="openTourModal(true, item)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn-icon danger" title="Delete" @click="deleteTournament(item)">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </template>
                                </EasyDataTable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="authStore.isAdmin" class="col-lg-4">
                <ComponentOtherUsers />
            </div>

            <div v-if="authStore.isAdmin" class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-muted fw-bold bg-transparent border-0">
                        FAN FEEDBACK
                        <span class="badge rounded-pill text-bg-secondary fw-lighter xsmall">{{ userData.feedback.length }}</span>
                    </div>
                    <div class="card-body">
                        <div v-if="!userData.feedback.length" class="text-center text-muted py-3">
                            No feedback yet. Fans can send feedback from your tournament's public page.
                        </div>
                        <div v-else class="table-responsive" style="max-height: 360px;">
                            <table class="table table-sm small">
                                <thead>
                                    <tr>
                                        <th>Tournament</th>
                                        <th>Name</th>
                                        <th>Feedback</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="feedback in userData.feedback" :key="feedback.feedback_id">
                                        <td class="text-nowrap">{{ feedback.tour_title }}</td>
                                        <td>{{ feedback.name || '-' }}</td>
                                        <td>{{ feedback.feedbackText }}</td>
                                        <td class="text-nowrap">{{ fx.dateDisplay(feedback.created_at, true) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <copyLinkModal v-if="copyModal" :link-to-copy="linkToCopy" @close="copyModal = false" />
    <tournamentFormModal v-if="newTournModal" :is-editing="isEditing" :editing-data="editingData"
        @close="closeTourModal" @done="refresh" />
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import fx from '@/store/useFunctions'
import copyLinkModal from '@/components/modals/userModals/copyLinkModal.vue'
import tournamentFormModal from '@/components/modals/userModals/tournamentFormModal.vue'
import ComponentOtherUsers from './ComponentOtherUsers.vue'

const authStore = useAuthStore()
const userData = useUserDataStore()

const statCards = [
    { key: 'tournaments', label: 'Tournaments', icon: 'bi bi-trophy', tint: 'tint-blue' },
    { key: 'teams', label: 'Teams', icon: 'bi bi-people', tint: 'tint-amber' },
    { key: 'matches', label: 'Matches', icon: 'bi bi-calendar2-event', tint: 'tint-violet' },
    { key: 'results', label: 'Results', icon: 'bi bi-check2-circle', tint: 'tint-green' },
    { key: 'live', label: 'Live now', icon: 'bi bi-broadcast', tint: 'tint-red' },
]

const headers: Header[] = [
    { text: '', value: 'tour_logo' },
    { text: 'Name', value: 'tour_title' },
    { text: 'TYPE', value: 'tour_type' },
    { text: 'TEAMS', value: 'teams_count' },
    { text: 'CREATED', value: 'created' },
    { text: 'Public page', value: 'link' },
    { text: '', value: 'actions' },
]

const newTournModal = ref(false)
const isEditing = ref(false)
const dataIsLoading = ref(true)
const editingData = ref<any>(null)
const copyModal = ref(false)
const linkToCopy = ref('')

function openTournamentLinkModal(tourId: string) {
    linkToCopy.value = `${window.location.origin}/stats/${tourId}`
    copyModal.value = true
}

function openTourModal(editing = false, editData: any = null) {
    isEditing.value = editing
    editingData.value = editData
    newTournModal.value = true
}

function closeTourModal() {
    isEditing.value = false
    newTournModal.value = false
    editingData.value = null
}

function refresh() {
    userData.getTournaments()
    userData.getDashboardFigures()
}

onMounted(async () => {
    await userData.getTournaments()
    dataIsLoading.value = false
    userData.getDashboardFigures()
    if (authStore.isAdmin) userData.getFeedbacks()
})

async function deleteTournament(tournament: any) {
    const tap = await fx.confirmDelete(`Delete "${tournament.tour_title}"?`, 'Yes, delete')
    if (!tap.isConfirmed) return

    try {
        await api.deleteTournament(tournament.tour_id)
        fx.toast.success('Tournament deleted')
        refresh()
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>

<style scoped>
.welcome {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.welcome h2 {
    font-weight: 800;
    font-size: 1.6rem;
    color: var(--text-strong);
}

.image-circle {
    height: 36px;
    width: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    background-color: var(--bs-light-bg-subtle);
    border: 1px solid var(--surface-border);
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}

.large-text {
    font-size: 2.3rem;
    font-weight: bolder;
}
</style>
