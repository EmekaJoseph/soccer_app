<template>
    <BaseModal title="End match" @close="close">
        <div class="modal-body">
            <form id="endMatchForm" @submit.prevent="end(true)">
                <p class="final-score">
                    {{ live.home_team }}
                    <b>{{ live.home_team_score }}–{{ live.away_team_score }}</b>
                    {{ live.away_team }}
                </p>

                <template v-if="canHavePenalties">
                    <label class="cursor-pointer">
                        <input v-model="form.isPenalties" type="checkbox" class="form-check-input me-1">
                        Decided on penalties?
                    </label>
                    <div v-if="form.isPenalties" class="penalties">
                        <label class="score-entry">
                            <span>{{ live.home_team }}</span>
                            <input v-model.number="form.home_score_pen" type="number" min="0" max="99" class="form-control" required>
                        </label>
                        <label class="score-entry">
                            <span>{{ live.away_team }}</span>
                            <input v-model.number="form.away_score_pen" type="number" min="0" max="99" class="form-control" required>
                        </label>
                    </div>
                </template>

                <p class="small mt-3 mb-0">"End only" stops the match without recording a result.</p>
                <div v-if="error" class="small text-danger mt-2" role="alert">{{ error }}</div>
            </form>
        </div>
        <div class="modal-footer modal-actions">
            <button form="endMatchForm" type="submit" class="ui-btn ui-btn-primary" :disabled="isSaving">
                {{ isSaving ? 'Ending…' : 'End & save result' }}
            </button>
            <button type="button" class="ui-btn ui-btn-ghost" :disabled="isSaving" @click="end(false)">End only</button>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'

const props = defineProps<{
    live: any
    /** Runs just before ending, e.g. to store the score the scorer has on screen. */
    beforeEnd?: () => Promise<unknown>
}>()

const emit = defineEmits<{ close: []; ended: []; failed: [] }>()

const isSaving = ref(false)
const error = ref('')
const form = reactive({ isPenalties: true, home_score_pen: 0, away_score_pen: 0 })

const canHavePenalties = computed(() =>
    fx.allowsPenalties(props.live.match_stage) && props.live.home_team_score === props.live.away_team_score,
)

function close() {
    if (!isSaving.value) emit('close')
}

async function end(save: boolean) {
    error.value = ''
    isSaving.value = true

    const penalties = save && canHavePenalties.value && form.isPenalties
        ? { home_score_pen: form.home_score_pen, away_score_pen: form.away_score_pen }
        : {}

    try {
        await props.beforeEnd?.()
        await api.endLiveMatch(props.live.live_id, save, penalties)
        fx.toast.success(save ? 'Match ended and result saved' : 'Match ended')
        emit('ended')
    } catch (e) {
        error.value = apiErrorMessage(e)
        emit('failed')
    } finally {
        isSaving.value = false
    }
}
</script>

<style scoped>
.final-score {
    margin-bottom: 1rem;
    color: var(--fg);
    overflow-wrap: anywhere;
    text-align: center;
}

.final-score b {
    margin-inline: 6px;
    font-family: var(--font-mono);
    font-size: 1.25rem;
    font-weight: 500;
}
</style>
