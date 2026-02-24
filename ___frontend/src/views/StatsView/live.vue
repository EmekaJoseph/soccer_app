<template>
    <div v-if="!stats.tourLives.length" class="min-vh-100 d-flex align-items-center justify-content-center">
        <emptyDataComponent>
            <div class="text-white">No Live Matches at the moment.</div>
        </emptyDataComponent>
    </div>
    <div v-else class="min-vh-50 pb-5">
        <div class="row g-4">
            <div v-for="({ match_stage, home_team, away_team, home_team_score, away_team_score, curr_time }, i) in stats.tourLives"
                :key="i" class="col-12 col-lg-6">
                <div class="glass-card live-card h-100 p-3 p-md-4 transition-all">
                    <div
                        class="d-flex justify-content-between align-items-center mb-4 border-bottom border-white border-opacity-10 pb-2">
                        <div class="d-flex align-items-center">
                            <span class="live-dot me-2"></span>
                            <span class="text-success fw-bold caps-ls-1">LIVE: {{ curr_time }}'</span>
                        </div>
                        <span class="badge bg-white bg-opacity-10 text-white-50 caps-ls-1 small">
                            {{ match_stage ? match_stage.replaceAll('_', ' ') : 'MATCH' }}
                        </span>
                    </div>

                    <div class="live-score-display d-flex align-items-center justify-content-between px-md-3">
                        <div class="team text-center flex-1">
                            <div class="shield-modern mb-2 mx-auto">
                                <span v-if="home_team?.team_badge" class="badge-text fs-4">{{ home_team.team_badge
                                }}</span>
                                <i v-else :style="{ color: home_team?.team_color || '#eee' }"
                                    class="bi bi-shield-fill fs-2"></i>
                            </div>
                            <div class="name-modern text-white fw-bold text-uppercase small">{{ home_team?.team_name }}
                            </div>
                        </div>

                        <div class="score-center px-4">
                            <div class="d-flex align-items-center gap-3">
                                <span class="score-digit text-gradient fs-1 fw-black">{{ home_team_score }}</span>
                                <span class="score-divider fs-3 text-white-50">:</span>
                                <span class="score-digit text-gradient fs-1 fw-black">{{ away_team_score }}</span>
                            </div>
                        </div>

                        <div class="team text-center flex-1">
                            <div class="shield-modern mb-2 mx-auto">
                                <span v-if="away_team?.team_badge" class="badge-text fs-4">{{ away_team.team_badge
                                }}</span>
                                <i v-else :style="{ color: away_team?.team_color || '#eee' }"
                                    class="bi bi-shield-fill fs-2"></i>
                            </div>
                            <div class="name-modern text-white fw-bold text-uppercase small">{{ away_team?.team_name }}
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
const stats = useStatsStore();
</script>

<style scoped>
.live-card {
    border: 1px solid rgba(0, 242, 254, 0.2);
    box-shadow: 0 0 20px rgba(0, 242, 254, 0.1);
    background: rgba(255, 255, 255, 0.05);
}

.caps-ls-1 {
    text-transform: uppercase;
    letter-spacing: 1px;
}

.flex-1 {
    flex: 1;
}

.fw-black {
    font-weight: 900;
}

.live-dot {
    width: 8px;
    height: 8px;
    background: #4caf50;
    border-radius: 50%;
    box-shadow: 0 0 10px #4caf50;
    animation: blink 1s infinite;
}

@keyframes blink {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.3;
    }

    100% {
        opacity: 1;
    }
}

.shield-modern {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.name-modern {
    font-size: 11px;
    letter-spacing: 0.5px;
    max-width: 100px;
    margin: 0 auto;
}

.score-digit {
    min-width: 40px;
    text-align: center;
    filter: drop-shadow(0 0 10px rgba(0, 242, 254, 0.3));
}

.text-gradient {
    background: var(--accent-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

@media (max-width: 576px) {
    .score-digit {
        font-size: 2rem !important;
    }

    .shield-modern {
        width: 40px;
        height: 40px;
    }

    .score-center {
        padding: 0 1rem !important;
    }
}
</style>
