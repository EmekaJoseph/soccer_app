<template>
    <TournamentPage v-model="selectedTournament" :loading="loading" @change="changed">
        <AdminCard title="Players" :count="filteredPlayers.length">
            <template v-if="userData.tournamentTeams.length" #actions>
                <select v-model="teamFilter" class="form-select filter" aria-label="Filter by team">
                    <option value="">All teams</option>
                    <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                </select>
                <input v-model="search" class="form-control filter" placeholder="Search name…" aria-label="Search players by name">
                <button class="ui-btn ui-btn-primary ui-btn-sm" @click="openForm()">
                    <i class="bi bi-plus-lg"></i> Add player
                </button>
            </template>

            <componentLoadingSpinner v-if="playersLoading" />
            <emptyDataComponent v-else-if="!userData.tournamentTeams.length" icon="bi bi-shield">
                Add teams to this tournament before adding players.
                <div class="mt-3">
                    <RouterLink to="/user/teams" class="ui-btn ui-btn-primary">Go to Teams</RouterLink>
                </div>
            </emptyDataComponent>
            <emptyDataComponent v-else-if="!userData.tournamentPlayers.length" icon="bi bi-person-badge">
                No players yet.
                <div class="mt-3">
                    <button class="ui-btn ui-btn-primary" @click="openForm()">Add the first player</button>
                </div>
            </emptyDataComponent>
            <emptyDataComponent v-else-if="!filteredPlayers.length" icon="bi bi-search">
                No players match your search.
                <div class="mt-3">
                    <button class="ui-btn ui-btn-ghost" @click="clearFilters">Clear filters</button>
                </div>
            </emptyDataComponent>
            <EasyDataTable v-else class="sticky-actions" :headers="headers" :items="filteredPlayers">
                <template #item-name="item">
                    <div class="d-flex align-items-center gap-2 py-1">
                        <img v-if="item.image" :src="fx.resolvePhotoSrc(item.image)" class="rounded-circle" width="32" height="32" alt="">
                        <i v-else class="bi bi-person-circle fs-4"></i>
                        {{ item.first_name }} {{ item.last_name }}
                    </div>
                </template>
                <template #item-team="item">{{ item.team?.team_name }}</template>
                <template #item-age="item">{{ age(item.dob) }}</template>
                <template #item-actions="item">
                    <div class="d-flex gap-2 justify-content-end">
                        <button class="btn-icon" title="Edit" aria-label="Edit player" @click="openForm(item)"><i class="bi bi-pencil"></i></button>
                        <button class="btn-icon danger" title="Delete" aria-label="Delete player" @click="remove(item)"><i class="bi bi-trash3"></i></button>
                    </div>
                </template>
            </EasyDataTable>
        </AdminCard>
    </TournamentPage>

    <PlayerFormModal v-if="formOpen" :player="editingPlayer" :team-id="teamFilter"
        @close="formOpen = false" @saved="reloadPlayers" />
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'
import PlayerFormModal from '@/components/modals/userModals/PlayerFormModal.vue'

const userData = useUserDataStore()
const route = useRoute()
const playersLoading = ref(true)
// Teams links here with ?team= to open on that team's squad.
const teamFilter = ref(typeof route.query.team === 'string' ? route.query.team : '')
const search = ref('')
const formOpen = ref(false)
const editingPlayer = ref<any>(null)
let firstLoad = true

const headers: Header[] = [
    { text: 'NAME', value: 'name' },
    { text: 'TEAM', value: 'team' },
    { text: 'POSITION', value: 'info' },
    { text: 'AGE', value: 'age' },
    { text: '', value: 'actions' },
]

const { selectedTournament, changed, loading } = useTournamentPicker(async (tourId) => {
    playersLoading.value = true
    if (!firstLoad) clearFilters()
    firstLoad = false
    await Promise.all([userData.getTournamentTeams(tourId), userData.getPlayers(tourId)])
    playersLoading.value = false
})

const filteredPlayers = computed(() => {
    const term = search.value.trim().toLowerCase()
    return userData.tournamentPlayers.filter((p) =>
        (!teamFilter.value || p.team_id === teamFilter.value)
        && (!term || `${p.first_name} ${p.last_name}`.toLowerCase().includes(term)),
    )
})

function age(dob?: string | null) {
    if (!dob) return '—'
    const years = Math.floor((Date.now() - new Date(dob).getTime()) / (365.25 * 24 * 3600 * 1000))
    return Number.isFinite(years) ? years : '—'
}

function clearFilters() {
    teamFilter.value = ''
    search.value = ''
}

function reloadPlayers() {
    return userData.getPlayers(selectedTournament.value.tour_id)
}

function openForm(player: any = null) {
    editingPlayer.value = player
    formOpen.value = true
}

async function remove(player: any) {
    const tap = await fx.confirmDelete(`Remove ${player.first_name} ${player.last_name}?`, 'Remove')
    if (!tap.isConfirmed) return

    try {
        await api.deletePlayer(player.player_id)
        fx.toast.info('Player removed')
        await reloadPlayers()
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>

<style scoped>
.filter {
    width: auto;
    max-width: 180px;
    padding-block: 0.35rem;
    font-size: 0.875rem;
}
</style>
