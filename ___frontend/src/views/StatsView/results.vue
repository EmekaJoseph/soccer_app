<template>
    <div v-if="stats.apiLoading" class="d-flex justify-content-center align-items-center mt-5">
        <img src="@/assets/images/loader.gif" width="40" alt="">
    </div>
    <div v-else>
        <div v-if="stats.apiError" class="mt-5">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div v-if="!stats.tourResults.length">
                <emptyDataComponent>
                    No Results Yet.
                </emptyDataComponent>
            </div>
            <!-- <div v-else class="animate__animated  animate__bounceInUp"> -->
            <div v-else>
                <div class="row justify-content-center gy-3">
                    <div v-for="({ match_stage, home_team, away_team, home_name, away_name, away_score, home_score, winner, date_played }, i) in stats.tourResults"
                        :key="i" class="col-sm-6">
                        <div class="card bg-white rounded-0 mb-4 h-100">
                            <div class="card-header fw-bolder bg-white shadow-sm border-0 text-center">
                                {{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}
                                ({{ dateFormat(date_played) }})
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center text-center g-1">
                                    <div class="col-4"> {{ home_name }}</div>
                                    <div class="col-1 fs-6 fw-bold" :class="{ 'text-success ': winner == home_team }"> {{
                                        home_score }}
                                    </div>
                                    <div class="col-2">:</div>
                                    <div class="col-1 fs-6 fw-bold" :class="{ 'text-success ': winner == away_team }">{{
                                        away_score }}
                                    </div>
                                    <div class="col-4">{{ away_name }}</div>
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
import { useDateFormat } from '@vueuse/core';

const stats = useStatsStore();

const dateFormat = (date: any) => useDateFormat(new Date(date), 'DD/MM/YYYY').value

</script>

<style scoped>
@media screen and (max-width: 992px) {
    .card {
        font-size: 12px;
    }
}
</style>