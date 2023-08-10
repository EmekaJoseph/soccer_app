<template>
    <div v-if="!stats.tourLives.length">
        <emptyDataComponent>
            No Live Matches at the moment.
        </emptyDataComponent>
    </div>
    <div v-else>
        <div class="row justify-content-cente">
            <div v-for="({ match_stage, home_team, away_team, home_team_score, away_team_score, curr_time }, i) in stats.tourLives"
                :key="i" class="col-sm-6">
                <div class="card bg-success-subtl live-bg  rounded-0 mb-4">
                    <div class="card-header fw-bold bg-transparent shadow-sm border-0 rounded-0 small">
                        <span class="text-success  fw-bold fs-5">{{ curr_time }}'
                        </span>
                        <div class="float-end">{{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}</div>

                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 fw-bolder text-uppercase">
                                {{ home_team }}
                                <span class="float-end">{{ home_team_score }}</span>
                            </div>

                            <div class="col-12 fw-bolder text-uppercase">
                                {{ away_team }}
                                <span class="float-end ">{{ away_team_score }}</span>
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
@media screen and (max-width: 992px) {
    .card {
        font-size: 14px;
    }
}

.live-bg {
    background-color: rgba(212, 243, 212, 0.61);
}
</style>