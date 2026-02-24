<template>
    <div v-if="!stats.tourResults.length" class="min-vh-100 d-flex align-items-center justify-content-center">
        <emptyDataComponent>
            No Results recorded.
        </emptyDataComponent>
    </div>
    <div v-else class="min-vh-50 pb-5">
        <div class="row g-3">
            <div v-for="({ match_stage, home_name, away_name, away_score, home_score, winner, date_played, home_score_pen, away_score_pen, home_team, away_team }, i) in stats.tourResults"
                :key="i" class="col-12 col-md-6">
                <div class="glass-card result-card h-100 p-3 p-md-4 transition-all hover-tilt-Y">
                    <div
                        class="d-flex justify-content-between align-items-center mb-3 border-bottom border-white border-opacity-10 pb-2">
                        <span class="badge bg-white bg-opacity-10 text-white-50 caps-ls-1 small">
                            {{ match_stage ? match_stage.replaceAll('_', ' ') : 'MATCH' }}
                        </span>
                        <span class="small text-white-50 fw-semibold">{{ dateFormat(date_played) }}</span>
                    </div>

                    <div class="teams-score-grid">
                        <div class="team-info d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <span class="name text-white" :class="{ 'opacity-50': winner == away_team }">{{
                                    home_name }}</span>
                            </div>
                            <span class="score fs-4 fw-bold text-white ml-auto"
                                :class="{ 'text-gradient': winner == home_team }">
                                {{ home_score }}
                            </span>
                        </div>

                        <div class="team-info d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="name text-white" :class="{ 'opacity-50': winner == home_team }">{{
                                    away_name }}</span>
                            </div>
                            <span class="score fs-4 fw-bold text-white ml-auto"
                                :class="{ 'text-gradient': winner == away_team }">
                                {{ away_score }}
                            </span>
                        </div>
                    </div>

                    <!-- Penalties -->
                    <div v-if="home_score_pen !== null && home_score_pen !== undefined"
                        class="mt-3 pt-2 border-top border-white border-opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-white-50 italic">Penalties</span>
                            <div class="text-white fw-bold">
                                <span :class="{ 'text-gradient': home_score_pen > away_score_pen }">{{ home_score_pen
                                    }}</span>
                                <span class="mx-2 opacity-50">:</span>
                                <span :class="{ 'text-gradient': away_score_pen > home_score_pen }">{{ away_score_pen
                                    }}</span>
                            </div>
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
.result-card {
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.caps-ls-1 {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 10px !important;
}

.name {
    font-weight: 600;
    font-size: 15px;
}

.score {
    min-width: 40px;
    text-align: right;
}

.text-gradient {
    background: var(--accent-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.transition-all {
    transition: all 0.3s ease;
}

.hover-tilt-Y:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(0, 242, 254, 0.3);
}

.italic {
    font-style: italic;
}
</style>
