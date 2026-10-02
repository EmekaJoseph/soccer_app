<template>
    <div class="min-vh-50 pb-5">
        <div class="row g-4">
            <div v-if="!stats.tourTeamsInfo.length" class="col-12">
                <emptyDataComponent><div class="text-white">No teams yet.</div></emptyDataComponent>
            </div>
            <div v-for="team in stats.tourTeamsInfo" :key="team.team_id" class="col-12">
                <div class="glass-card team-profile-card p-4 transition-all">
                    <div class="d-flex align-items-center mb-4">
                        <div class="profile-shield me-3"
                            :style="{ borderColor: team.team_color ? team.team_color + '40' : 'rgba(255,255,255,0.1)' }">
                            <TeamBadge :badge="team?.team_badge" :color="team.team_color" :size="40" />
                        </div>
                        <div>
                            <h3 class="text-white fw-bold mb-0 text-uppercase">{{ team.team_name }}</h3>
                            <div class="small text-white-50">
                                <template v-if="team.group_in">Group {{ team.group_in }} · </template>
                                <template v-if="team.manager">Manager: {{ team.manager }}</template>
                                <template v-else>Tournament participant</template>
                            </div>
                        </div>
                    </div>

                    <div class="stats-grid row g-3 mb-4">
                        <div class="col-6 col-md-4">
                            <div
                                class="stat-box p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="small text-white opacity-100 mb-1">Played</div>
                                <div class="fs-4 fw-bold text-white">{{ team.played }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div
                                class="stat-box p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="small text-white opacity-100 mb-1">Won</div>
                                <div class="fs-4 fw-bold text-success">{{ team.won }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div
                                class="stat-box p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="small text-white opacity-100 mb-1">Lost</div>
                                <div class="fs-4 fw-bold text-danger">{{ team.lost }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div
                                class="stat-box p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="small text-white opacity-100 mb-1">Draw</div>
                                <div class="fs-4 fw-bold text-info">{{ team.draw }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div
                                class="stat-box p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="small text-white opacity-100 mb-1">GF</div>
                                <div class="fs-4 fw-bold text-white">{{ team.scored }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div
                                class="stat-box p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <div class="small text-white opacity-100 mb-1">GA</div>
                                <div class="fs-4 fw-bold text-white">{{ team.conceded }}</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="team.rating !== null && team.rating !== undefined"
                        class="rating-section pt-3 border-top border-white border-opacity-10">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-white-50 caps-ls-1">Performance Rating</span>
                            <span class="fw-bold text-white">{{ team.rating }}%</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern"
                                :style="{ width: team.rating + '%', background: team.rating < 50 ? '#ff4d4d' : 'var(--accent-gradient)' }">
                            </div>
                        </div>
                    </div>

                    <details v-if="squad(team.team_id).length" class="mt-3 squad">
                        <summary class="small text-white-50 caps-ls-1">Squad ({{ squad(team.team_id).length }})</summary>
                        <ul class="list-unstyled row g-2 mt-2 mb-0">
                            <li v-for="p in squad(team.team_id)" :key="p.player_id" class="col-6 col-md-4 d-flex align-items-center gap-2 text-white small">
                                <img v-if="p.image" :src="fx.resolvePhotoSrc(p.image)" class="rounded-circle" width="28" height="28" alt="">
                                <i v-else class="bi bi-person-circle fs-5 text-white-50"></i>
                                <span>{{ p.first_name }} {{ p.last_name }}<span v-if="p.info" class="text-white-50"> · {{ p.info }}</span></span>
                            </li>
                        </ul>
                    </details>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import TeamBadge from '@/components/TeamBadge.vue'
import { useStatsStore } from '@/store/statsStore';
import fx from '@/store/useFunctions'
const stats = useStatsStore()
const squad = (teamId: string) => stats.tourPlayers.filter((p) => p.team_id === teamId)
</script>

<style scoped>
.team-profile-card {
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.profile-shield {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    border: 2px solid;
}

.caps-ls-1 {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 11px;
}

.progress-modern {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar-modern {
    height: 100%;
    border-radius: 10px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 10px rgba(0, 242, 254, 0.3);
}

@media (max-width: 576px) {
    .profile-shield {
        width: 55px;
        height: 55px;
    }

    .profile-shield i {
        font-size: 1.5rem !important;
    }

    .stat-box {
        padding: 10px !important;
    }

    .stat-box .fs-4 {
        font-size: 1.1rem !important;
    }
}

.squad summary {
    cursor: pointer;
}

.stat-box .text-success {
    color: #4ade80 !important;
}

.stat-box .text-danger {
    color: #ff7b7f !important;
}

.stat-box .text-info {
    color: #5fd4ff !important;
}

.stat-box .fs-4 {
    font-family: var(--font-display);
    font-weight: 800 !important;
}
</style>
