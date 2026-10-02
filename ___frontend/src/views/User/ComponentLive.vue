<template>
    <OverlayLoading v-if="ending" />
    <div class="col-12 col-md-6 col-xl-4">
        <div class="scoreboard">
            <div class="board-head">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stage">{{ fx.stageLabel(teamData.match_stage, 'Match') }}</span>
                    <span v-if="live.isPaused" class="state paused">PAUSED</span>
                    <span v-else class="state on"><span class="dot"></span>LIVE</span>
                </div>
                <div class="clock">{{ live.curr_time }}<span>'</span></div>
                <div class="clock-controls">
                    <button class="ctl" aria-label="Minus one minute" @click="live.curr_time = Math.max(0, live.curr_time - 1)">−1'</button>
                    <button class="ctl wide" @click="live.isPaused = !live.isPaused">
                        <i :class="live.isPaused ? 'bi bi-play-fill' : 'bi bi-pause-fill'"></i>
                        {{ live.isPaused ? 'Resume' : 'Pause' }}
                    </button>
                    <button class="ctl" aria-label="Plus one minute" @click="live.curr_time = Math.min(200, live.curr_time + 1)">+1'</button>
                </div>
            </div>

            <div class="board-body">
                <div v-for="side in sides" :key="side.key" class="team-row">
                    <span class="team-name text-truncate">{{ side.name }}</span>
                    <button class="goal-btn minus" :disabled="live[side.key] === 0"
                        :aria-label="`Remove goal for ${side.name}`" @click="live[side.key]--">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <span class="score">{{ live[side.key] }}</span>
                    <button class="goal-btn plus" :aria-label="`Goal for ${side.name}`" @click="live[side.key]++">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>

                <div class="sync">
                    <template v-if="syncState === 'saving'"><span class="spinner-border spinner-border-sm"></span> Saving…</template>
                    <span v-else-if="syncState === 'error'" class="text-danger"><i class="bi bi-exclamation-triangle"></i> Not saved — check connection</span>
                    <span v-else-if="syncState === 'saved'" class="text-success"><i class="bi bi-check2-circle"></i> Fans are up to date</span>
                    <span v-else>&nbsp;</span>
                </div>

                <button @click="endLive" class="btn btn-outline-danger w-100">
                    <i class="bi bi-stop-circle me-1"></i> End match
                </button>
            </div>
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
        'END & SAVE RESULT',
        'END ONLY',
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
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--surface-card);
    border: 1px solid var(--surface-border);
    box-shadow: var(--shadow-md);
}

.board-head {
    padding: 1rem 1.1rem 1.1rem;
    color: #fff;
    background:
        radial-gradient(120% 120% at 100% 0%, rgba(0, 242, 254, 0.18), transparent 55%),
        linear-gradient(160deg, var(--brand-navy-700), var(--brand-navy-900));
}

.stage {
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.6);
}

.state {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
}

.state.on {
    background: rgba(34, 197, 94, 0.18);
    color: #4ade80;
}

.state.paused {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
}

.dot {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    margin-right: 5px;
    vertical-align: middle;
    animation: blink 1s infinite;
}

@keyframes blink {
    50% { opacity: 0.2; }
}

.clock {
    font-family: var(--font-display);
    font-weight: 900;
    font-size: 3.2rem;
    text-align: center;
    line-height: 1.1;
    margin: 0.4rem 0 0.6rem;
}

.clock span {
    color: var(--brand-cyan);
}

.clock-controls {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    gap: 0.5rem;
}

.ctl {
    border: 1px solid rgba(255, 255, 255, 0.14);
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    border-radius: 10px;
    padding: 0.4rem;
    font-weight: 600;
    font-size: 0.85rem;
}

.ctl:hover {
    background: rgba(255, 255, 255, 0.16);
}

.board-body {
    padding: 1rem 1.1rem 1.1rem;
}

.team-row {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.55rem 0;
}

.team-row + .team-row {
    border-top: 1px dashed var(--surface-border);
}

.team-name {
    flex: 1;
    font-weight: 700;
    color: var(--text-strong);
    text-transform: uppercase;
    font-size: 0.9rem;
}

.score {
    font-family: var(--font-display);
    font-weight: 900;
    font-size: 1.9rem;
    min-width: 2ch;
    text-align: center;
    color: var(--text-strong);
}

.goal-btn {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    border: 1px solid var(--surface-border);
    display: flex;
    align-items: center;
    justify-content: center;
}

.goal-btn.minus {
    background: var(--surface-muted);
    color: var(--text-body);
}

.goal-btn.plus {
    border: 0;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    box-shadow: 0 6px 14px -6px rgba(22, 163, 74, 0.7);
}

.goal-btn:disabled {
    opacity: 0.4;
}

.sync {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-align: center;
    margin: 0.5rem 0 0.9rem;
    min-height: 1.3em;
}
</style>
