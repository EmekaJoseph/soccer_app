<template>
    <div v-if="stats.apiLoading" class="d-flex justify-content-center align-items-center mt-5">
        <img src="@/assets/images/loader.gif" width="40" alt="">
    </div>
    <div v-else>
        <div v-if="stats.apiError" class="mt-5">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div v-if="!stats.tourLives.length">
                <emptyDataComponent>
                    No Live Matches
                </emptyDataComponent>
            </div>
            <div v-else>
                <div class="row justify-content-center">
                    <div v-for="({ match_stage, home_team, away_team, home_team_score, away_team_score, curr_time }, i) in stats.tourLives"
                        :key="i" class="col-sm-6">
                        <div class="card bg-white rounded-0 mb-4">
                            <div class="card-header fw-bold bg-white shadow-sm border-0">
                                {{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}
                                <div class="float-end fw-bold">{{ curr_time }} <span class="text-success">'</span></div>
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
        font-size: 12px;
    }
}

.winner {
    border-right: 2px solid green;
}
</style>