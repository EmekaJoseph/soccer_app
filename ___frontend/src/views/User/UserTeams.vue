<template>
    <TournamentPage v-model="selectedTournament" :loading="loading" @change="changed">
        <div class="row g-4">
            <div class="col-lg-6">
                <AdminCard title="Teams" :count="userData.tournamentTeams.length" class="h-100">
                    <template #actions>
                        <button class="ui-btn ui-btn-primary ui-btn-sm" @click="openForm()">
                            <i class="bi bi-plus-lg"></i> New team
                        </button>
                    </template>
                    <componentLoadingSpinner v-if="teamsLoading" />
                    <emptyDataComponent v-else-if="!userData.tournamentTeams.length" icon="bi bi-shield">
                        No teams yet.
                        <div class="mt-3">
                            <button class="ui-btn ui-btn-primary" @click="openForm()">Add the first team</button>
                        </div>
                    </emptyDataComponent>
                    <ul v-else class="team-list">
                        <li v-for="(team, index) in userData.tournamentTeams" :key="team.team_id">
                            <button type="button" class="team-line" :class="{ active: activeTeam?.team_id == team.team_id }"
                                :aria-current="activeTeam?.team_id == team.team_id ? 'true' : undefined" @click="activeTeamId = team.team_id">
                                <span class="team-num">{{ index + 1 }}</span>
                                <TeamBadge :badge="team.team_badge" :color="team.team_color" :size="22" />
                                <span class="team-name">{{ team.team_name }}</span>
                                <span v-if="team.group_in" class="tag">Group {{ team.group_in }}</span>
                            </button>
                        </li>
                    </ul>
                </AdminCard>
            </div>

            <div v-if="activeTeam" class="col-lg-6">
                <AdminCard class="h-100">
                    <template #title>
                        <TeamBadge :badge="activeTeam.team_badge" :color="activeTeam.team_color" />
                        <span class="active-name">{{ activeTeam.team_name }}</span>
                    </template>
                    <template #actions>
                        <button class="btn-icon" title="Edit" aria-label="Edit team" @click="openForm(activeTeam)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn-icon danger" title="Delete" aria-label="Delete team" @click="deleteTeam(activeTeam)">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </template>
                    <dl class="details">
                        <div>
                            <dt>About</dt>
                            <dd>{{ activeTeam.team_brief || '—' }}</dd>
                        </div>
                        <div>
                            <dt>Manager</dt>
                            <dd>{{ activeTeam.manager || '—' }}</dd>
                        </div>
                        <div v-if="activeTeam.address">
                            <dt>Home ground</dt>
                            <dd>{{ activeTeam.address }}</dd>
                        </div>
                        <div>
                            <dt>Matches played</dt>
                            <dd>{{ activeTeam.match_played }}</dd>
                        </div>
                        <div>
                            <dt>Players</dt>
                            <dd>
                                {{ activeTeam.players_count ?? 0 }}
                                <RouterLink :to="{ path: '/user/players', query: { team: activeTeam.team_id } }" class="ms-2">Manage players</RouterLink>
                            </dd>
                        </div>
                    </dl>
                </AdminCard>
            </div>
        </div>
    </TournamentPage>

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
.team-list {
    display: grid;
    gap: 2px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.team-line {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 0;
    border-radius: 8px;
    background: none;
    color: var(--fg);
    font-size: 0.9375rem;
    text-align: left;
    transition: background-color 0.15s ease;
}

.team-line:hover {
    background: var(--surface-raised);
}

.team-line.active {
    background: var(--volt-soft);
}

.team-num {
    width: 1.25rem;
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
}

.team-name {
    flex: 1;
    min-width: 0;
    overflow-wrap: anywhere;
}

.active-name {
    min-width: 0;
    overflow-wrap: anywhere;
}

.details {
    margin: 0;
}

.details > div {
    padding-block: 0.75rem;
}

.details > div:first-child {
    padding-top: 0;
}

.details > div + div {
    border-top: 1px solid var(--line);
}

.details dt {
    margin-bottom: 0.2rem;
    font-size: 0.8125rem;
    font-weight: 400;
}

.details dd {
    margin: 0;
    color: var(--fg);
    font-size: 0.9375rem;
}

.details a {
    font-size: 0.875rem;
}
</style>
