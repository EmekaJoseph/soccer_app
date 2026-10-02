<template>
    <div class="container">
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
                        <div class="card border-0">
                            <div class="card-header text-muted bg-transparent border-0">NEW RESULT</div>
                            <div class="card-body">
                                <form class="row g-3" @submit.prevent="save">
                                    <div class="col-md-12">
                                        <label class="w-100">Match:
                                            <select v-model="selectedMatch" class="form-select text-uppercase" required>
                                                <option :value="null" disabled>
                                                    {{ pendingMatches.length ? '-- select --' : 'No matches waiting for a result' }}
                                                </option>
                                                <option v-for="m in pendingMatches" :key="m.match_id" :value="m">
                                                    {{ m.home_team.team_name }} VS {{ m.away_team.team_name }}
                                                    <template v-if="m.match_stage">({{ fx.stageLabel(m.match_stage) }})</template>
                                                </option>
                                            </select>
                                        </label>
                                    </div>

                                    <template v-if="selectedMatch">
                                        <div class="col-9">
                                            <label class="w-100">Home team:
                                                <input type="text" :value="selectedMatch.home_team.team_name" class="form-control" disabled>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label class="w-100 small">Score:
                                                <input v-model.number="form.homeTeam_score" type="number" min="0" max="99" class="form-control" required>
                                            </label>
                                        </div>
                                        <div class="col-9">
                                            <label class="w-100">Away team:
                                                <input type="text" :value="selectedMatch.away_team.team_name" class="form-control" disabled>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label class="w-100 small">Score:
                                                <input v-model.number="form.awayTeam_score" type="number" min="0" max="99" class="form-control" required>
                                            </label>
                                        </div>

                                        <div v-if="canHavePenalties" class="col-md-12">
                                            <label class="cursor-pointer">
                                                <input v-model="form.isPenalties" type="checkbox" class="form-check-input me-1">
                                                Decided on penalties?
                                            </label>
                                            <div v-if="form.isPenalties" class="card mt-2">
                                                <div class="card-body small row">
                                                    <div class="col-6">
                                                        <label class="w-100 small">{{ selectedMatch.home_team.team_name }}:
                                                            <input v-model.number="form.home_score_pen" type="number" min="0" max="99" class="form-control" required>
                                                        </label>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="w-100 small">{{ selectedMatch.away_team.team_name }}:
                                                            <input v-model.number="form.away_score_pen" type="number" min="0" max="99" class="form-control" required>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div v-if="formError" class="col-12 small text-danger">{{ formError }}</div>
                                    <div class="col-md-12 mt-3">
                                        <button type="submit" :disabled="!selectedMatch || isSaving" class="btn btn-primary-theme w-100">
                                            {{ isSaving ? 'Saving…' : 'Save result' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card card-fixed-height border-0 h-100">
                            <div class="card-header text-muted bg-transparent border-0">RESULTS ({{ userData.tournamentResults.length }})</div>
                            <div class="card-body">
                                <EasyDataTable class="border-0 text-nowrap" :headers="tableHeaders" :items="userData.tournamentResults" :loading="dataIsLoading">
                                    <template #item-results="item">
                                        {{ item.home_name }} <span class="fw-bold">{{ item.home_score }}</span>
                                        –
                                        <span class="fw-bold">{{ item.away_score }}</span> {{ item.away_name }}
                                        <span v-if="item.home_score_pen !== null" class="small text-muted">
                                            ({{ item.home_score_pen }}-{{ item.away_score_pen }} pens)
                                        </span>
                                    </template>
                                    <template #item-match_stage="item">{{ fx.stageLabel(item.match_stage, '—') }}</template>
                                    <template #item-played="item">
                                        <span class="fw-bolder">{{ item.date_played ? fx.dateDisplay(item.date_played) : '—' }}</span>
                                    </template>
                                    <template #item-undo="item">
                                        <button class="btn btn-sm btn-outline-danger py-0" @click="undoResult(item)">undo</button>
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
import { computed, reactive, ref, watch } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'

const NO_PENALTY_STAGES = ['Group_Stage', 'Friendly']

const userData = useUserDataStore()
const selectedMatch = ref<any>(null)
const dataIsLoading = ref(true)
const isSaving = ref(false)
const formError = ref('')

const tableHeaders: Header[] = [
    { text: 'RESULT', value: 'results' },
    { text: 'STAGE', value: 'match_stage' },
    { text: 'DATE', value: 'played' },
    { text: '', value: 'undo' },
]

const emptyForm = () => ({
    homeTeam_score: 0,
    awayTeam_score: 0,
    isPenalties: false,
    home_score_pen: 0,
    away_score_pen: 0,
})
const form = reactive(emptyForm())

const { selectedTournament, changed, loading } = useTournamentPicker(loadData)

async function loadData(tourId: string) {
    dataIsLoading.value = true
    selectedMatch.value = null
    await Promise.all([userData.getTournamentResults(tourId), userData.getTournamentMatches(tourId)])
    dataIsLoading.value = false
}

/** Matches that still need a result and are not being scored live. */
const pendingMatches = computed(() => userData.tournamentMatches.filter((m) => !m.result && !m.live && m.home_team && m.away_team))

const canHavePenalties = computed(() =>
    selectedTournament.value?.type == 'cup'
    && selectedMatch.value?.match_stage
    && !NO_PENALTY_STAGES.includes(selectedMatch.value.match_stage)
    && form.homeTeam_score === form.awayTeam_score,
)

watch(canHavePenalties, (allowed) => {
    if (!allowed) form.isPenalties = false
})

async function save() {
    formError.value = ''
    const match = selectedMatch.value
    const payload = {
        match_id: match.match_id,
        homeTeam_score: form.homeTeam_score,
        awayTeam_score: form.awayTeam_score,
        home_score_pen: form.isPenalties ? form.home_score_pen : null,
        away_score_pen: form.isPenalties ? form.away_score_pen : null,
    }

    const tap = await fx.confirm(
        `Save ${match.home_team.team_name} ${payload.homeTeam_score} - ${payload.awayTeam_score} ${match.away_team.team_name}?`,
        'Yes, save',
    )
    if (!tap.isConfirmed) return

    isSaving.value = true
    try {
        await api.saveResult(payload)
        fx.toast.success('Result saved, standings updated')
        Object.assign(form, emptyForm())
        await loadData(selectedTournament.value.tour_id)
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}

async function undoResult(result: any) {
    const tap = await fx.confirmDelete('Undo this result? The standings will be recalculated.', 'Yes, undo')
    if (!tap.isConfirmed) return

    try {
        await api.undoResult(result.result_id)
        fx.toast.info('Result undone')
        loadData(selectedTournament.value.tour_id)
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>
