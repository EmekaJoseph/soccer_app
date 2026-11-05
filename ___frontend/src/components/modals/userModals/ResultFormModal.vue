<template>
    <BaseModal title="Record result" @close="emit('close')">
        <div class="modal-body">
            <form id="resultForm" class="row g-3" @submit.prevent="save">
                <div class="col-12">
                    <label class="w-100">Match
                        <select v-model="selectedMatch" class="form-select" required>
                            <option :value="null" disabled>
                                {{ pendingMatches.length ? 'Select' : 'No matches waiting for a result' }}
                            </option>
                            <option v-for="m in pendingMatches" :key="m.match_id" :value="m">
                                {{ m.home_team.team_name }} v {{ m.away_team.team_name }}
                                <template v-if="m.match_stage">({{ fx.stageLabel(m.match_stage) }})</template>
                            </option>
                        </select>
                    </label>
                </div>

                <template v-if="selectedMatch">
                    <div class="col-12">
                        <label class="score-entry">
                            <span>{{ selectedMatch.home_team.team_name }}</span>
                            <input v-model.number="form.homeTeam_score" type="number" min="0" max="99" class="form-control" required>
                        </label>
                        <label class="score-entry">
                            <span>{{ selectedMatch.away_team.team_name }}</span>
                            <input v-model.number="form.awayTeam_score" type="number" min="0" max="99" class="form-control" required>
                        </label>
                    </div>

                    <div v-if="canHavePenalties" class="col-12">
                        <label class="cursor-pointer">
                            <input v-model="form.isPenalties" type="checkbox" class="form-check-input me-1">
                            Decided on penalties?
                        </label>
                        <div v-if="form.isPenalties" class="penalties">
                            <label class="score-entry">
                                <span>{{ selectedMatch.home_team.team_name }}</span>
                                <input v-model.number="form.home_score_pen" type="number" min="0" max="99" class="form-control" required>
                            </label>
                            <label class="score-entry">
                                <span>{{ selectedMatch.away_team.team_name }}</span>
                                <input v-model.number="form.away_score_pen" type="number" min="0" max="99" class="form-control" required>
                            </label>
                        </div>
                    </div>
                </template>

                <div v-if="formError" class="col-12 small text-danger" role="alert">{{ formError }}</div>
            </form>
        </div>
        <div class="modal-footer">
            <button form="resultForm" type="submit" :disabled="!selectedMatch || isSaving" class="ui-btn ui-btn-primary ui-btn-lg w-100">
                {{ isSaving ? 'Saving…' : 'Save result' }}
            </button>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'

const NO_PENALTY_STAGES = ['Group_Stage', 'Friendly']

const props = defineProps<{
    tournament: any
    /** Match to preselect. */
    match?: any
}>()

const emit = defineEmits<{ close: []; saved: [] }>()

const userData = useUserDataStore()
// Look the match up in the store: table rows are copies, which a <select> would not recognise as an option.
const selectedMatch = ref<any>(userData.tournamentMatches.find((m) => m.match_id === props.match?.match_id) ?? null)
const isSaving = ref(false)
const formError = ref('')

const form = reactive({
    homeTeam_score: 0,
    awayTeam_score: 0,
    isPenalties: false,
    home_score_pen: 0,
    away_score_pen: 0,
})

/** Matches that still need a result and are not being scored live. */
const pendingMatches = computed(() => userData.tournamentMatches.filter((m) => !m.result && !m.live && m.home_team && m.away_team))

const canHavePenalties = computed(() =>
    props.tournament?.type == 'cup'
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
        emit('saved')
        emit('close')
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}
</script>

<style scoped>
.score-entry {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    width: 100%;
    padding-block: 0.4rem;
}

.score-entry span {
    min-width: 0;
    color: var(--fg);
    overflow-wrap: anywhere;
}

.modal .score-entry .form-control {
    flex: none;
    width: 76px;
    margin: 0;
    font-family: var(--font-mono);
    text-align: center;
}

.penalties {
    margin-top: 0.75rem;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
}
</style>
