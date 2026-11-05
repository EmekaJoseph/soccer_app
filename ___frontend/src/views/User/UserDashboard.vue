<template>
    <internetErrorComponent v-if="userData.apiError && !userData.tournaments.length" />
    <div v-else class="dash">
        <div class="welcome">
            <div>
                <h2>{{ fx.greet() }}, {{ authStore.user?.firstname || 'coach' }}</h2>
                <p>Here's what's happening across your tournaments.</p>
            </div>
            <button v-if="authStore.isAdmin" class="ui-btn ui-btn-primary" @click="openTourModal()">
                <i class="bi bi-plus-lg"></i> New tournament
            </button>
        </div>

        <AdminCard v-if="showSetup" title="Get started" class="setup" hint="Finish these and your tournament is ready for fans.">
            <ol class="setup-steps">
                <li v-for="(step, i) in setupSteps" :key="step.label" :class="{ done: step.done }">
                    <span class="step-mark">
                        <i v-if="step.done" class="bi bi-check-lg" aria-label="Done"></i>
                        <template v-else>{{ i + 1 }}</template>
                    </span>
                    <span class="step-label">{{ step.label }}</span>
                    <template v-if="step === nextStep">
                        <button v-if="!step.link" class="ui-btn ui-btn-primary ui-btn-sm" @click="openTourModal()">Create</button>
                        <RouterLink v-else :to="step.link" class="ui-btn ui-btn-primary ui-btn-sm">Open</RouterLink>
                    </template>
                </li>
            </ol>
        </AdminCard>

        <dl class="stat-strip">
            <div v-for="card in statCards" :key="card.key" class="stat">
                <dt>
                    <span v-if="card.key == 'live' && userData.dashboardFigures?.live" class="ui-live-dot"></span>
                    {{ card.label }}
                </dt>
                <dd>{{ userData.dashboardFigures?.[card.key] ?? 0 }}</dd>
            </div>
        </dl>

        <div class="row g-4">
            <div :class="authStore.isAdmin ? 'col-xl-8' : 'col-12'">
                <AdminCard title="Tournaments" :count="userData.tournaments.length" class="h-100">
                    <componentLoadingSpinner v-if="dataIsLoading" />
                    <emptyDataComponent v-else-if="!userData.tournaments.length" icon="bi bi-trophy">
                        You don't have any tournaments yet.
                        <div v-if="authStore.isAdmin && !showSetup" class="mt-3">
                            <button class="ui-btn ui-btn-primary" @click="openTourModal()">Create your first tournament</button>
                        </div>
                    </emptyDataComponent>
                    <EasyDataTable v-else class="sticky-actions" :headers="headers" :items="userData.tournaments">
                        <template #item-tour_logo="item">
                            <div class="tour-logo" :style="{ backgroundImage: item.tour_logo ? `url(${fx.resolvePhotoSrc(item.tour_logo)})` : 'none' }">
                                <i v-if="!item.tour_logo" class="bi bi-trophy"></i>
                            </div>
                        </template>

                        <template #item-tour_title="item">
                            <button class="tour-name" title="Manage this tournament" @click="openTournament(item)">{{ item.tour_title }}</button>
                        </template>

                        <template #item-tour_type="item">
                            <span class="tag type-tag">{{ item.tour_type }}</span>
                        </template>

                        <template #item-link="item">
                            <button class="share-btn" @click="openTournamentLinkModal(item.tour_id)">
                                <i class="bi bi-link-45deg"></i> Share
                            </button>
                        </template>

                        <template #item-actions="item">
                            <div v-if="authStore.isAdmin" class="d-flex gap-2 justify-content-end">
                                <button class="btn-icon" title="Edit" aria-label="Edit" @click="openTourModal(true, item)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon danger" title="Delete" aria-label="Delete" @click="deleteTournament(item)">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </template>
                    </EasyDataTable>
                </AdminCard>
            </div>

            <div v-if="authStore.isAdmin" class="col-xl-4">
                <ComponentOtherUsers />
            </div>

            <div v-if="authStore.isAdmin" class="col-12">
                <AdminCard title="Fan feedback" :count="userData.feedback.length">
                    <emptyDataComponent v-if="!userData.feedback.length" icon="bi bi-chat">
                        No feedback yet. Fans can send it from your tournament's public page.
                    </emptyDataComponent>
                    <div v-else class="table-responsive" style="max-height: 360px;">
                        <table class="table">
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
                                    <td>{{ feedback.name || '—' }}</td>
                                    <td>{{ feedback.feedbackText }}</td>
                                    <td class="text-nowrap">{{ fx.dateDisplay(feedback.created_at, true) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </AdminCard>
            </div>
        </div>
    </div>

    <copyLinkModal v-if="copyModal" :link-to-copy="linkToCopy" @close="copyModal = false" />
    <tournamentFormModal v-if="newTournModal" :is-editing="isEditing" :editing-data="editingData"
        @close="closeTourModal" @done="refresh" />
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStorage } from '@vueuse/core'
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
const router = useRouter()
// Same key as useTournamentPicker, so the page opened below starts on this tournament.
const rememberedTournament = useStorage('socc_selected_tournament', '')

/** Takes a tournament to the next place it needs work. Scorers cannot open Teams. */
function openTournament(tournament: any) {
    rememberedTournament.value = tournament.tour_id
    router.push(authStore.isAdmin && !tournament.teams_count ? '/user/teams' : '/user/matches')
}

const setupSteps = computed(() => {
    const figures = userData.dashboardFigures
    if (!figures || !authStore.isAdmin) return []
    return [
        { label: 'Create a tournament', done: (figures.tournaments ?? 0) > 0, link: '' },
        { label: 'Add at least two teams', done: (figures.teams ?? 0) >= 2, link: '/user/teams' },
        { label: 'Schedule your first match', done: (figures.matches ?? 0) > 0, link: '/user/matches' },
    ]
})
const nextStep = computed(() => setupSteps.value.find((step) => !step.done))
const showSetup = computed(() => Boolean(nextStep.value))

const statCards = [
    { key: 'tournaments', label: 'Tournaments' },
    { key: 'teams', label: 'Teams' },
    { key: 'matches', label: 'Matches' },
    { key: 'results', label: 'Results' },
    { key: 'live', label: 'Live now' },
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
    userData.apiError = false
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
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.75rem;
}

.welcome h2 {
    margin: 0 0 0.25rem;
    color: var(--fg);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 600;
    letter-spacing: -0.03em;
}

.welcome p {
    margin: 0;
}

.stat-strip {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    margin: 0 0 1.5rem;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    background: var(--surface);
}

.stat {
    padding: 1rem 1.25rem;
}

.stat + .stat {
    border-left: 1px solid var(--line);
}

.stat dt {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8125rem;
    font-weight: 400;
}

.stat dd {
    margin: 0.35rem 0 0;
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 1.625rem;
    font-weight: 500;
    letter-spacing: -0.03em;
    line-height: 1.1;
}

.setup {
    margin-bottom: 1.5rem;
}

.setup-steps {
    display: grid;
    gap: 0.25rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.setup-steps li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-height: 40px;
    color: var(--fg);
    font-size: 0.9375rem;
}

.setup-steps li.done .step-label {
    color: var(--fg-muted);
    text-decoration: line-through;
}

.step-mark {
    display: grid;
    flex: none;
    place-items: center;
    width: 24px;
    height: 24px;
    border: 1px solid var(--line-strong);
    border-radius: 50%;
    font-family: var(--font-mono);
    font-size: 0.75rem;
}

.done .step-mark {
    border-color: transparent;
    background: var(--volt);
    color: var(--on-volt);
}

.step-label {
    flex: 1;
    min-width: 0;
}

.tour-name {
    padding: 0;
    border: 0;
    background: none;
    color: var(--fg);
    font-weight: 500;
    text-align: left;
}

.tour-name:hover {
    color: var(--accent);
    text-decoration: underline;
}

.tour-logo {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    border: 1px solid var(--line);
    border-radius: 8px;
    background-color: var(--surface-raised);
    background-position: center;
    background-size: cover;
    color: var(--fg-muted);
}

.type-tag {
    text-transform: capitalize;
}

.share-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0;
    border: 0;
    background: none;
    color: var(--accent);
    font-size: 0.875rem;
    white-space: nowrap;
}

.share-btn:hover {
    text-decoration: underline;
}

@media (max-width: 767.98px) {
    .stat-strip {
        grid-template-columns: repeat(2, 1fr);
    }

    .stat {
        border-bottom: 1px solid var(--line);
    }

    .stat + .stat {
        border-left: 0;
    }

    .stat:nth-child(even) {
        border-left: 1px solid var(--line);
    }

    .stat:last-child {
        grid-column: span 2;
        border-bottom: 0;
    }
}
</style>
