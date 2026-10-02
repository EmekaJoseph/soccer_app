<template>
    <div class="container px-3">
        <componentLoadingSpinner v-if="loading" />
        <internetErrorComponent v-else-if="userData.apiError" />
        <div v-else class="row gy-4">
            <tourDropdownSelect v-model="selectedTournament" @change="changed" />

            <div v-if="!selectedTournament" class="col-12">
                <emptyDataComponent>Create a tournament on the dashboard first.</emptyDataComponent>
            </div>

            <div v-else class="col-lg-12">
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <div class="card border-0">
                            <div class="card-header text-muted bg-transparent border-0">
                                TEAMS ({{ userData.tournamentTeams.length }})
                                <button @click="openForm()" class="btn btn-primary-theme btn-sm float-end">
                                    New Team <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="card min-vh-50 h-100">
                                    <componentLoadingSpinner v-if="teamsLoading" />
                                    <div v-else class="card-body">
                                        <emptyDataComponent v-if="!userData.tournamentTeams.length">No teams yet</emptyDataComponent>
                                        <ul class="list-group list-group-flush">
                                            <li v-for="(team, index) in userData.tournamentTeams" :key="team.team_id"
                                                @click="activeTeamId = team.team_id"
                                                class="list-group-item team-line-item my-1 d-flex align-items-center gap-2"
                                                :class="{ 'active-team shadow-sm': activeTeam?.team_id == team.team_id }">
                                                <span class="text-muted small">{{ index + 1 }}</span>
                                                <TeamBadge :badge="team.team_badge" :color="team.team_color" :size="22" />
                                                <span class="flex-grow-1">{{ team.team_name }}</span>
                                                <span v-if="team.group_in" class="badge text-bg-light">Group {{ team.group_in }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" v-if="activeTeam">
                        <div class="card border-0 h-100">
                            <div class="card-header text-muted bg-transparent border-0 text-uppercase fw-bolder d-flex align-items-center gap-2">
                                <TeamBadge :badge="activeTeam.team_badge" :color="activeTeam.team_color" />
                                <span class="flex-grow-1">{{ activeTeam.team_name }}</span>
                                <button class="btn-icon" title="Edit" @click="openForm(activeTeam)">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button @click="deleteTeam(activeTeam)" class="btn-icon danger" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item">
                                        <div class="fw-bolder">ABOUT:</div>
                                        {{ activeTeam.team_brief || '—' }}
                                    </li>
                                    <li class="list-group-item">
                                        <div class="fw-bolder">MANAGER:</div>
                                        {{ activeTeam.manager || '—' }}
                                    </li>
                                    <li v-if="activeTeam.address" class="list-group-item">
                                        <div class="fw-bolder">HOME GROUND:</div>
                                        {{ activeTeam.address }}
                                    </li>
                                    <li class="list-group-item">
                                        <div class="fw-bolder">MATCHES PLAYED:</div>
                                        {{ activeTeam.match_played }}
                                    </li>
                                    <li class="list-group-item">
                                        <div class="fw-bolder">PLAYERS:</div>
                                        {{ activeTeam.players_count ?? 0 }}
                                        <RouterLink to="/user/players" class="ms-2">Manage players</RouterLink>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <teamFormModal v-if="formOpen" :tournament="selectedTournament" :team="editingTeam"
        @close="formOpen = false" @saved="onSaved" />
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'
import teamFormModal from '@/components/modals/userModals/teamFormModal.vue'
import TeamBadge from '@/components/TeamBadge.vue'

const userData = useUserDataStore()
const teamsLoading = ref(true)
const activeTeamId = ref<string | null>(null)
const formOpen = ref(false)
const editingTeam = ref<any>(null)

const activeTeam = computed(() =>
    userData.tournamentTeams.find((t) => t.team_id === activeTeamId.value) ?? userData.tournamentTeams[0] ?? null,
)

const { selectedTournament, changed, loading } = useTournamentPicker(loadTournamentTeams)

async function loadTournamentTeams(tourId: string) {
    teamsLoading.value = true
    await userData.getTournamentTeams(tourId)
    teamsLoading.value = false
}

function openForm(team: any = null) {
    editingTeam.value = team
    formOpen.value = true
}

async function onSaved(team: any) {
    await loadTournamentTeams(selectedTournament.value.tour_id)
    activeTeamId.value = team.team_id
    userData.getTournaments()
}

async function deleteTeam(team: any) {
    const tap = await fx.confirmDelete(`Delete ${team.team_name}? Its players and fixtures are removed too.`, 'Yes, delete')
    if (!tap.isConfirmed) return

    try {
        await api.deleteTeam(team.team_id)
        fx.toast.info(`${team.team_name} deleted`)
        activeTeamId.value = null
        loadTournamentTeams(selectedTournament.value.tour_id)
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>

<style scoped>
.team-line-item {
    cursor: pointer;
    font-size: 0.92rem;
    border-radius: 12px !important;
    border: 1px solid transparent !important;
    padding: 0.6rem 0.75rem;
    transition: background-color 0.15s, border-color 0.15s;
}

.team-line-item:hover {
    background-color: #fff;
}

.active-team {
    background-color: #fff;
    border-color: #bfe6f7 !important;
    box-shadow: 0 0 0 3px rgba(0, 200, 240, 0.12) !important;
    font-weight: 600;
    color: var(--text-strong);
}
</style>
