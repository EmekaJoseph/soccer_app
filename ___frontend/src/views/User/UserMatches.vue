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
                    <div class="col-lg-5">
                        <div class="card border-0 h-100">
                            <div class="card-header text-muted bg-transparent border-0">
                                {{ form.match_id ? 'EDIT MATCH' : 'ADD MATCH' }}
                                <button v-if="form.match_id" class="btn btn-sm btn-link float-end p-0" @click="resetForm">Cancel</button>
                            </div>
                            <div class="card-body">
                                <emptyDataComponent v-if="userData.tournamentTeams.length < 2">
                                    Add at least two teams to schedule matches.
                                </emptyDataComponent>
                                <form v-else class="row g-3" @submit.prevent="save">
                                    <div class="col-md-6">
                                        <label class="w-100">Home team:
                                            <select v-model="form.homeTeam" class="form-select" :disabled="!!form.match_id" required>
                                                <option value="" disabled>--select--</option>
                                                <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="w-100">Away team:
                                            <select v-model="form.awayTeam" class="form-select" :disabled="!!form.match_id" required>
                                                <option value="" disabled>--select--</option>
                                                <option v-for="t in awayTeamOptions" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="w-100">Venue:
                                            <input v-model.trim="form.venue" class="form-control" required>
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="w-100">Kick-off:
                                            <input v-model="form.kick_off" type="datetime-local" class="form-control" required>
                                        </label>
                                    </div>
                                    <div v-if="selectedTournament.type == 'cup'" class="col-md-12">
                                        <label class="w-100">Stage:
                                            <select v-model="form.match_stage" class="form-select" required
                                                :disabled="!!form.match_id && form.hasResult">
                                                <option value="" disabled>--select--</option>
                                                <option v-for="s in userData.match_stages" :key="s" :value="s">{{ fx.stageLabel(s) }}</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div v-if="formError" class="col-12 small text-danger">{{ formError }}</div>
                                    <div class="col-md-12 mt-4">
                                        <button type="submit" :disabled="isSaving" class="btn btn-primary-theme w-100">
                                            <span v-if="isSaving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                            {{ isSaving ? 'Saving…' : form.match_id ? 'Update match' : 'Save' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card border-0 card-fixed-height h-100">
                            <div class="card-header text-muted bg-transparent border-0">MATCHES ({{ userData.tournamentMatches.length }})</div>
                            <div class="card-body">
                                <EasyDataTable class="border-0" :headers="tableHeaders" :items="userData.tournamentMatches" :loading="dataIsLoading">
                                    <template #item-homeVsAway="item">
                                        {{ item.home_team?.team_name ?? '-' }}
                                        <span class="fw-bolder"> VS </span>
                                        {{ item.away_team?.team_name ?? '-' }}
                                    </template>
                                    <template #item-match_stage="item">{{ fx.stageLabel(item.match_stage, '—') }}</template>
                                    <template #item-kick_off="item">
                                        <span class="fw-bolder">{{ fx.dateDisplay(item.kick_off, true) }}</span>
                                    </template>
                                    <template #item-status="item">
                                        <span v-if="item.result" class="badge text-bg-success">{{ item.result.home_score }} - {{ item.result.away_score }}</span>
                                        <span v-else-if="item.live" class="badge text-bg-danger">LIVE</span>
                                        <span v-else class="badge text-bg-light">Scheduled</span>
                                    </template>
                                    <template #item-actions="item">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn-icon" title="Edit" @click="edit(item)"><i class="bi bi-pencil"></i></button>
                                            <button v-if="!item.result" class="btn-icon danger" title="Delete" @click="deleteMatch(item)">
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
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'

const userData = useUserDataStore()
const dataIsLoading = ref(true)
const isSaving = ref(false)
const formError = ref('')

const tableHeaders: Header[] = [
    { text: 'MATCH', value: 'homeVsAway' },
    { text: 'STAGE', value: 'match_stage' },
    { text: 'KICK OFF', value: 'kick_off', sortable: true },
    { text: 'STATUS', value: 'status' },
    { text: '', value: 'actions' },
]

const nextHour = () => {
    const d = new Date()
    d.setHours(d.getHours() + 1, 0, 0, 0)
    return fx.toDatetimeLocal(d)
}

const emptyForm = () => ({
    match_id: '',
    hasResult: false,
    homeTeam: '',
    awayTeam: '',
    venue: '',
    kick_off: nextHour(),
    match_stage: '',
})
const form = reactive(emptyForm())

const { selectedTournament, changed, loading } = useTournamentPicker(async (tourId) => {
    dataIsLoading.value = true
    resetForm()
    await Promise.all([userData.getTournamentTeams(tourId), userData.getTournamentMatches(tourId)])
    dataIsLoading.value = false
})

const awayTeamOptions = computed(() => userData.tournamentTeams.filter((t) => t.team_id !== form.homeTeam))

function resetForm() {
    // Keep venue and kick-off: fixtures are usually entered in batches at the same ground.
    const { venue, kick_off } = form
    Object.assign(form, emptyForm(), { venue, kick_off })
    formError.value = ''
}

function edit(match: any) {
    Object.assign(form, {
        match_id: match.match_id,
        hasResult: Boolean(match.result),
        homeTeam: match.home_team?.team_id ?? '',
        awayTeam: match.away_team?.team_id ?? '',
        venue: match.venue,
        kick_off: fx.toDatetimeLocal(match.kick_off),
        match_stage: match.match_stage ?? '',
    })
    window.scrollTo({ top: 0, behavior: 'smooth' })
}

async function save() {
    formError.value = ''
    isSaving.value = true

    const payload = {
        tour_id: selectedTournament.value.tour_id,
        homeTeam: form.homeTeam,
        awayTeam: form.awayTeam,
        venue: form.venue,
        kick_off: new Date(form.kick_off).toISOString(),
        match_stage: selectedTournament.value.type == 'cup' ? form.match_stage : null,
    }

    try {
        if (form.match_id) await api.updateMatch(form.match_id, payload)
        else await api.createMatch(payload)
        fx.toast.success(form.match_id ? 'Match updated' : 'Match scheduled')
        resetForm()
        await userData.getTournamentMatches(selectedTournament.value.tour_id)
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}

async function deleteMatch(match: any) {
    const tap = await fx.confirmDelete(`Delete ${match.home_team?.team_name} vs ${match.away_team?.team_name}?`, 'Delete')
    if (!tap.isConfirmed) return

    try {
        await api.deleteMatch(match.match_id)
        fx.toast.info('Match deleted')
        userData.getTournamentMatches(selectedTournament.value.tour_id)
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>
