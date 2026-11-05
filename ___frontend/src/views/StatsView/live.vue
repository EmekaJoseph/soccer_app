<template>
    <emptyDataComponent v-if="!stats.tourLives.length" icon="bi bi-broadcast">
        No matches are live right now.
    </emptyDataComponent>

    <div v-else class="live-list">
        <article v-for="live in stats.tourLives" :key="live.live_id" class="live-card">
            <header class="live-head">
                <span v-if="live.isPaused" class="clock paused"><i class="bi bi-pause-fill" aria-hidden="true"></i>Paused {{ live.curr_time }}'</span>
                <span v-else class="clock"><span class="ui-live-dot"></span>Live {{ live.curr_time }}'</span>
                <span v-if="live.match_stage" class="stage">{{ fx.stageLabel(live.match_stage) }}</span>
            </header>
            <div class="board">
                <div class="team">
                    <span class="crest"><TeamBadge :badge="live.home_team?.team_badge" :color="live.home_team?.team_color" :size="36" /></span>
                    {{ live.home_team?.team_name }}
                </div>
                <!-- Keyed by score so the digit replays its animation on every goal. -->
                <div class="score" :aria-label="`${live.home_team_score} to ${live.away_team_score}`">
                    <span :key="live.home_team_score" class="digit">{{ live.home_team_score }}</span>
                    <span class="sep">–</span>
                    <span :key="live.away_team_score" class="digit">{{ live.away_team_score }}</span>
                </div>
                <div class="team">
                    <span class="crest"><TeamBadge :badge="live.away_team?.team_badge" :color="live.away_team?.team_color" :size="36" /></span>
                    {{ live.away_team?.team_name }}
                </div>
            </div>
        </article>
    </div>
</template>

<script setup lang="ts">
import TeamBadge from '@/components/TeamBadge.vue'
import { useStatsStore } from '@/store/statsStore'
import fx from '@/store/useFunctions'

const stats = useStatsStore()
</script>

<style scoped>
.live-list {
    display: grid;
    gap: 12px;
}

.live-card {
    border: 1px solid var(--line-strong);
    border-radius: 16px;
    background: var(--ink-900);
}

.live-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--line);
}

.clock {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--volt);
    font-family: var(--font-mono);
    font-size: 0.8125rem;
    font-weight: 500;
}

.clock.paused {
    gap: 4px;
    color: var(--fg-muted);
}

.stage {
    font-size: 0.8125rem;
    text-align: right;
    text-transform: capitalize;
}

.board {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: start;
    gap: 8px;
    padding: 32px 12px;
}

.team {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    min-width: 0;
    color: var(--fg);
    font-size: 0.9375rem;
    font-weight: 500;
    line-height: 1.3;
    overflow-wrap: anywhere;
    text-align: center;
}

.crest {
    display: grid;
    place-items: center;
    width: 56px;
    height: 56px;
    border: 1px solid var(--line);
    border-radius: 50%;
    background: var(--ink-800);
}

.score {
    display: flex;
    align-items: center;
    height: 56px;
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: clamp(2.5rem, 9vw, 3.5rem);
    font-weight: 500;
    letter-spacing: -0.04em;
}

.sep {
    margin-inline: 8px;
    color: var(--fg-muted);
    font-weight: 400;
}

.digit {
    animation: score-change 0.45s ease-out;
}

@keyframes score-change {
    from {
        color: var(--volt);
        transform: translateY(-6px);
    }
}

@media (prefers-reduced-motion: reduce) {
    .digit {
        animation: none;
    }
}
</style>
