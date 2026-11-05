<template>
    <OverlayLoading v-if="ending" />
    <div class="col-12 col-md-6 col-xxl-4">
        <div class="scoreboard">
            <div class="board-head">
                <span class="stage">{{ fx.stageLabel(teamData.match_stage, 'Match') }}</span>
                <span v-if="live.isPaused" class="tag">Paused</span>
                <span v-else class="tag tag-live"><span class="ui-live-dot"></span>Live</span>
            </div>

            <div class="clock">{{ live.curr_time }}<span>'</span></div>
            <div class="clock-controls">
                <button class="ui-btn ui-btn-ghost" aria-label="Minus one minute" @click="live.curr_time = Math.max(0, live.curr_time - 1)">−1'</button>
                <button class="ui-btn ui-btn-ghost" @click="live.isPaused = !live.isPaused">
                    <i :class="live.isPaused ? 'bi bi-play-fill' : 'bi bi-pause-fill'"></i>
                    {{ live.isPaused ? 'Resume' : 'Pause' }}
                </button>
                <button class="ui-btn ui-btn-ghost" aria-label="Plus one minute" @click="live.curr_time = Math.min(200, live.curr_time + 1)">+1'</button>
            </div>

            <div v-for="side in sides" :key="side.key" class="team-row">
                <span class="team-name">{{ side.name }}</span>
                <button class="goal-btn" :disabled="live[side.key] === 0"
                    :aria-label="`Remove goal for ${side.name}`" @click="live[side.key]--">
                    <i class="bi bi-dash-lg"></i>
                </button>
                <span class="score">{{ live[side.key] }}</span>
                <button class="goal-btn plus" :aria-label="`Goal for ${side.name}`" @click="live[side.key]++">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            <div class="sync" role="status">
                <template v-if="syncState === 'saving'"><span class="spinner-border spinner-border-sm"></span> Saving…</template>
                <span v-else-if="syncState === 'error'" class="text-danger"><i class="bi bi-exclamation-triangle"></i> Not saved. Check your connection.</span>
                <span v-else-if="syncState === 'saved'" class="synced"><i class="bi bi-check2-circle"></i> Fans are up to date</span>
            </div>

            <button class="ui-btn ui-btn-danger w-100" @click="endLive">
                <i class="bi bi-stop-circle"></i> End match
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onUnmounted, reactive, ref, watch } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import OverlayLoading from '@/components/overlayLoading.vue'

const props = defineProps<{ teamData: any }>()
const emit = defineEmits<{ ended: [] }>()

const live = reactive({
    home_team_score: Number(props.teamData.home_team_score ?? 0),
    away_team_score: Number(props.teamData.away_team_score ?? 0),
    curr_time: Number(props.teamData.curr_time ?? 0),
    isPaused: Boolean(props.teamData.isPaused),
})

const sides = [
    { key: 'home_team_score', name: props.teamData.home_team },
    { key: 'away_team_score', name: props.teamData.away_team },
] as const

const ending = ref(false)
const syncState = ref<'idle' | 'saving' | 'saved' | 'error'>('idle')

async function sendUpdate() {
    syncState.value = 'saving'
    try {
        await api.updateLiveMatch(props.teamData.live_id, { ...live })
        syncState.value = 'saved'
    } catch {
        syncState.value = 'error'
    }
}

// Batch rapid taps into one request.
const queueUpdate = fx.debounce(sendUpdate, 800)
watch(live, queueUpdate)

// The match clock advances by itself once a minute unless paused.
const clock = setInterval(() => {
    if (!live.isPaused) live.curr_time = Math.min(200, live.curr_time + 1)
}, 60_000)

onUnmounted(() => {
    clearInterval(clock)
    queueUpdate.cancel()
})

async function endLive() {
    const tap = await fx.confirmOptions(
        `End ${props.teamData.home_team} ${live.home_team_score} - ${live.away_team_score} ${props.teamData.away_team}?`,
        'End & save result',
        'End only',
    )
    if (!tap.isConfirmed && !tap.isDenied) return

    ending.value = true
    queueUpdate.cancel()
    try {
        // Make sure the final score is stored before it is saved as the result.
        await api.updateLiveMatch(props.teamData.live_id, { ...live })
        await api.endLiveMatch(props.teamData.live_id, tap.isConfirmed)
        fx.toast.success(tap.isConfirmed ? 'Match ended and result saved' : 'Match ended')
        emit('ended')
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    } finally {
        ending.value = false
    }
}
</script>

<style scoped>
.scoreboard {
    padding: 1.25rem;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius-lg);
    background: var(--surface);
}

.board-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}

.stage {
    font-size: 0.8125rem;
}

.clock {
    margin-block: 1rem 0.75rem;
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 3.5rem;
    font-weight: 500;
    letter-spacing: -0.05em;
    line-height: 1;
    text-align: center;
}

.clock span {
    color: var(--accent);
}

.clock-controls {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.team-row {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding-block: 0.75rem;
    border-top: 1px solid var(--line);
}

.team-name {
    flex: 1;
    min-width: 0;
    color: var(--fg);
    font-size: 0.9375rem;
    font-weight: 500;
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.score {
    min-width: 2ch;
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 1.75rem;
    font-weight: 500;
    text-align: center;
}

/* Sized for tapping quickly from the touchline. */
.goal-btn {
    display: grid;
    flex: none;
    place-items: center;
    width: 44px;
    height: 44px;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius-sm);
    background: transparent;
    color: var(--fg);
}

.goal-btn:hover:not(:disabled) {
    background: var(--surface-raised);
}

.goal-btn.plus {
    border-color: transparent;
    background: var(--volt);
    color: var(--on-volt);
}

.goal-btn.plus:hover {
    background: #d8ff3f;
}

.goal-btn:disabled {
    opacity: 0.35;
}

.sync {
    min-height: 2.5rem;
    padding-block: 0.6rem;
    border-top: 1px solid var(--line);
    font-size: 0.8125rem;
    text-align: center;
}

.synced {
    color: var(--accent);
}
</style>
