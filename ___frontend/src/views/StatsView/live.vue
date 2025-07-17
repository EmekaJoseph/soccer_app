<template>
    <div v-if="!stats.tourLives.length" class="min-vh-100">
        <emptyDataComponent>
            No Live Matches at the moment.
        </emptyDataComponent>
    </div>
    <div v-else class="min-vh-100">
        <div class="row justify-content-cente">
            <div v-for="({ match_stage, home_team, away_team, home_team_score, away_team_score, curr_time }, i) in stats.tourLives"
                class="col-sm-6">
                <div class="card live-bg border-0 h-100 small border-0">
                    <div class="card-header fw-bolder  border-0 small py-1">
                        <span class="text-success  fw-bold fs-5">{{ curr_time }}'
                        </span>
                        <span class="float-end">{{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}</span>
                    </div>
                    <div class="card-body p-3">

                        <div class="fw-bolder text-uppercase mb-2">
                            <span v-if="home_team?.team_badge"> {{ home_team.team_badge }} </span>
                            <span v-else>
                                <i :style="{ color: `${!home_team.team_color ? '#eee' : home_team.team_color}` }"
                                    class="bi bi-shield-fill"></i>
                            </span>
                            {{ home_team?.team_name }}:
                            <span class="float-end">{{ home_team_score }}</span>
                        </div>
                        <div class="fw-bolder text-uppercase">
                            <span v-if="away_team?.team_badge"> {{ away_team?.team_badge }} </span>
                            <span v-else>
                                <i :style="{ color: `${!away_team.team_color ? '#eee' : away_team.team_color}` }"
                                    class="bi bi-shield-fill"></i>
                            </span>
                            {{ away_team.team_name }}:
                            <span class="float-end ">{{ away_team_score }}</span>
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
@media screen and (max-width: 992px) {
    .card {
        font-size: 14px;
    }
}

.live-bg {
    background-color: rgba(212, 243, 212, 0.61);
}

.live-bg .card-header {
    background-color: rgb(197, 245, 197);
}
</style>