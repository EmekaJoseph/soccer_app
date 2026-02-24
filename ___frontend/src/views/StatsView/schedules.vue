<template>
    <div v-if="!stats.tourMatches.length" class="min-vh-100 d-flex align-items-center justify-content-center">
        <emptyDataComponent>
            <div class="text-white">No schedules yet, <br> Come back later.</div>
        </emptyDataComponent>
    </div>

    <div v-else class="min-vh-50 pb-5">
        <div v-for="({ match_stage, kick_off, home_team, away_team, venue }, i) in stats.tourMatches" :key="i"
            class="glass-card fixture-card mb-4 p-3 p-md-4 transition-all hover-tilt-Y">

            <div
                class="d-flex justify-content-between align-items-center mb-4 border-bottom border-white border-opacity-10 pb-2">
                <span class="badge bg-white bg-opacity-10 text-white-50 caps-ls-1 small">
                    {{ match_stage ? match_stage.replaceAll('_', ' ') : 'FIXTURE' }}
                </span>
                <div class="text-end">
                    <div class="small text-white fw-bold">{{ dateFormat(kick_off) }}</div>
                    <div class="small text-white-50">{{ new Date(kick_off).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) }}</div>
                </div>
            </div>

            <div class="match-teams-row d-flex align-items-center justify-content-between px-md-4">
                <div class="team-container left text-center flex-1">
                    <div class="shield-wrapper mb-2 mx-auto">
                        <span v-if="home_team.team_badge" class="badge-text fs-3">{{ home_team.team_badge }}</span>
                        <i v-else :style="{ color: home_team.team_color || '#eee' }" class="bi bi-shield-fill fs-1"></i>
                    </div>
                    <div class="team-name text-white fw-bold text-uppercase">{{ home_team.team_name }}</div>
                </div>

                <div class="vs-divider flex-shrink-0 px-3">
                    <div class="vs-circle">VS</div>
                </div>

                <div class="team-container right text-center flex-1">
                    <div class="shield-wrapper mb-2 mx-auto">
                        <span v-if="away_team.team_badge" class="badge-text fs-3">{{ away_team.team_badge }}</span>
                        <i v-else :style="{ color: away_team.team_color || '#eee' }" class="bi bi-shield-fill fs-1"></i>
                    </div>
                    <div class="team-name text-white fw-bold text-uppercase">{{ away_team.team_name }}</div>
                </div>
            </div>

            <div class="mt-4 pt-2 border-top border-white border-opacity-10 text-center">
                <div class="small text-white-50 italic">
                    <i class="bi bi-geo-alt-fill me-1"></i> {{ venue }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore';
import { useDateFormat } from '@vueuse/core';

const stats = useStatsStore();
const dateFormat = (date: any) => useDateFormat(new Date(date), 'ddd, MMM DD, YYYY').value
</script>

<style scoped>
.fixture-card {
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.caps-ls-1 {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 10px !important;
}

.flex-1 {
    flex: 1;
}

.team-name {
    font-size: 14px;
    letter-spacing: 0.5px;
}

.shield-wrapper {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.fixture-card:hover .shield-wrapper {
    transform: scale(1.1);
    background: rgba(255, 255, 255, 0.08);
}

.vs-divider {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.vs-circle {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-gradient);
    color: #0f2027;
    font-weight: 800;
    font-size: 12px;
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(0, 242, 254, 0.3);
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

@media (max-width: 576px) {
    .team-name {
        font-size: 12px;
    }

    .shield-wrapper {
        width: 45px;
        height: 45px;
    }

    .shield-wrapper i {
        font-size: 1.5rem !important;
    }
}
</style>
