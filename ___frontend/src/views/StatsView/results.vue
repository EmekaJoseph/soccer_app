<template>
    <div v-if="!stats.tourResults.length" class="min-vh-100 d-flex align-items-center justify-content-center">
        <emptyDataComponent>
            No Results recorded.
        </emptyDataComponent>
    </div>
    <div v-else class="min-vh-50 pb-5">
        <div class="row g-4">
            <div v-for="({ match_stage, home_name, away_name, away_score, home_score, winner, date_played, home_score_pen, away_score_pen, home_team, away_team }, i) in stats.tourResults"
                :key="i" class="col-12">
                <div class="glass-card modern-result-card p-4 transition-all hover-glow">
                    <!-- Header: Stage & Date -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="stage-tag px-3 py-1 rounded-pill">
                            {{ match_stage ? match_stage.replaceAll('_', ' ') : 'MATCH' }}
                        </div>
                        <div class="date-text">{{ dateFormat(date_played) }}</div>
                    </div>

                    <!-- Main Match Display -->
                    <div class="match-main-grid">
                        <!-- Home Team -->
                        <div class="team-side home">
                            <div class="shield-lg mb-3" :class="{ 'winner-shield': winner == home_team }">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <h5 class="team-name"
                                :class="{ 'text-white': winner == home_team, 'text-white-50': winner == away_team }">
                                {{ home_name }}
                            </h5>
                        </div>

                        <!-- Score Center -->
                        <div class="score-center mx-4">
                            <div class="score-display">
                                <span class="score-digit" :class="{ 'text-gradient': winner == home_team }">{{
                                    home_score }}</span>
                                <span class="score-separator">:</span>
                                <span class="score-digit" :class="{ 'text-gradient': winner == away_team }">{{
                                    away_score }}</span>
                            </div>
                            <div v-if="home_score_pen !== null" class="penalty-tag mt-2">
                                ({{ home_score_pen }} - {{ away_score_pen }} PEN)
                            </div>
                            <div class="ft-tag mt-2">FT</div>
                        </div>

                        <!-- Away Team -->
                        <div class="team-side away">
                            <div class="shield-lg mb-3" :class="{ 'winner-shield': winner == away_team }">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <h5 class="team-name"
                                :class="{ 'text-white': winner == away_team, 'text-white-50': winner == home_team }">
                                {{ away_name }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore';
import { useDateFormat } from '@vueuse/core';

const stats = useStatsStore();
const dateFormat = (date: any) => useDateFormat(new Date(date), 'MMM DD, YYYY').value
</script>

<style scoped>
.modern-result-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.hover-glow:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(0, 242, 254, 0.3);
    box-shadow: 0 0 30px rgba(0, 242, 254, 0.1);
}

.stage-tag {
    background: rgba(0, 242, 254, 0.1);
    color: var(--accent-color);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    border: 1px solid rgba(0, 242, 254, 0.2);
}

.date-text {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.4);
    font-weight: 500;
}

.match-main-grid {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.team-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.shield-lg {
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.4s ease;
}

.winner-shield {
    background: rgba(0, 242, 254, 0.1);
    color: var(--accent-color);
    border-color: rgba(0, 242, 254, 0.3);
    box-shadow: 0 0 20px rgba(0, 242, 254, 0.1);
    transform: scale(1.05);
}

.team-name {
    font-weight: 700;
    font-size: 16px;
    margin: 0;
    letter-spacing: 0.5px;
    color: #ccc;
}

.score-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 120px;
}

.score-display {
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(0, 0, 0, 0.2);
    padding: 8px 24px;
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.score-digit {
    font-size: 2.5rem;
    font-weight: 900;
    font-family: 'Outfit', sans-serif;
    line-height: 1;
    color: rgb(173, 172, 172);
}

.score-separator {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.2);
    font-weight: 300;
}

.ft-tag {
    font-size: 10px;
    font-weight: 800;
    color: var(--accent-color);
    letter-spacing: 2px;
    opacity: 0.8;
}

.penalty-tag {
    font-size: 11px;
    color: #ff4d4d;
    font-weight: 700;
    letter-spacing: 0.5px;
}

@media (max-width: 576px) {
    .shield-lg {
        width: 48px;
        height: 48px;
        font-size: 24px;
    }

    .score-digit {
        font-size: 1.8rem;
    }

    .team-name {
        font-size: 14px;
    }

    .score-display {
        gap: 10px;
        padding: 6px 16px;
    }
}
</style>
