<template>
    <TournamentPage v-model="selectedTournament" :loading="loading" @change="changed">
        <AdminCard title="Matches" :count="userData.tournamentMatches.length">
            <template v-if="userData.tournamentTeams.length >= 2" #actions>
                <button class="ui-btn ui-btn-primary ui-btn-sm" @click="openForm()">
                    <i class="bi bi-plus-lg"></i> Add match
                </button>
            </template>

            <componentLoadingSpinner v-if="dataIsLoading" />
            <emptyDataComponent v-else-if="userData.tournamentTeams.length < 2" icon="bi bi-shield">
                Add at least two teams to schedule matches.
                <div v-if="authStore.isAdmin" class="mt-3">
                    <RouterLink to="/user/teams" class="ui-btn ui-btn-primary">Go to Teams</RouterLink>
                </div>
                <div v-else class="mt-2 small">Ask the tournament owner to add them.</div>
            </emptyDataComponent>
            <emptyDataComponent v-else-if="!userData.tournamentMatches.length" icon="bi bi-calendar2-event">
                No matches scheduled yet.
                <div class="mt-3">
                    <button class="ui-btn ui-btn-primary" @click="openForm()">Add the first match</button>
                </div>
            </emptyDataComponent>
            <EasyDataTable v-else class="sticky-actions" :headers="tableHeaders" :items="userData.tournamentMatches">
                <template #item-homeVsAway="item">
                    {{ item.home_team?.team_name ?? '—' }}
                    <span class="vs">v</span>
                    {{ item.away_team?.team_name ?? '—' }}
                </template>
                <template #item-match_stage="item">{{ fx.stageLabel(item.match_stage, '—') }}</template>
                <template #item-kick_off="item">
                    <span class="text-nowrap">{{ fx.dateDisplay(item.kick_off, true) }}</span>
                </template>
                <template #item-status="item">
                    <span v-if="item.result" class="tag tag-strong">{{ item.result.home_score }}–{{ item.result.away_score }}</span>
                    <span v-else-if="item.live" class="tag tag-live">Live</span>
                    <span v-else class="tag">Scheduled</span>
                </template>
                <template #item-actions="item">
                    <div class="d-flex gap-2 justify-content-end">
                        <button v-if="!item.result && !item.live" class="btn-icon" title="Record result" aria-label="Record result" @click="resultMatch = item">
                            <i class="bi bi-check2-circle"></i>
                        </button>
                        <button class="btn-icon" title="Edit" aria-label="Edit match" @click="openForm(item)"><i class="bi bi-pencil"></i></button>
                        <button v-if="!item.result" class="btn-icon danger" title="Delete" aria-label="Delete match" @click="deleteMatch(item)">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </template>
            </EasyDataTable>
        </AdminCard>
    </TournamentPage>

    <MatchFormModal v-if="formOpen" :tournament="selectedTournament" :match="editingMatch"
        @close="formOpen = false" @saved="reloadMatches" />
    <ResultFormModal v-if="resultMatch" :tournament="selectedTournament" :match="resultMatch"
        @close="resultMatch = null" @saved="reloadMatches" />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import { useAuthStore } from '@/store/authStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'
import MatchFormModal from '@/components/modals/userModals/MatchFormModal.vue'
import ResultFormModal from '@/components/modals/userModals/ResultFormModal.vue'

const userData = useUserDataStore()
const authStore = useAuthStore()
const dataIsLoading = ref(true)
const formOpen = ref(false)
const editingMatch = ref<any>(null)
const resultMatch = ref<any>(null)

const tableHeaders: Header[] = [
    { text: 'MATCH', value: 'homeVsAway' },
    { text: 'STAGE', value: 'match_stage' },
    { text: 'KICK OFF', value: 'kick_off', sortable: true },
    { text: 'STATUS', value: 'status' },
    { text: '', value: 'actions' },
]

const { selectedTournament, changed, loading } = useTournamentPicker(async (tourId) => {
    dataIsLoading.value = true
    await Promise.all([userData.getTournamentTeams(tourId), userData.getTournamentMatches(tourId)])
    dataIsLoading.value = false
})

function reloadMatches() {
    return userData.getTournamentMatches(selectedTournament.value.tour_id)
}

function openForm(match: any = null) {
    editingMatch.value = match
    formOpen.value = true
}

async function deleteMatch(match: any) {
    const tap = await fx.confirmDelete(`Delete ${match.home_team?.team_name} vs ${match.away_team?.team_name}?`, 'Delete')
    if (!tap.isConfirmed) return

    try {
        await api.deleteMatch(match.match_id)
        fx.toast.info('Match deleted')
        reloadMatches()
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>

<style scoped>
.vs {
    margin-inline: 4px;
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
}
</style>
