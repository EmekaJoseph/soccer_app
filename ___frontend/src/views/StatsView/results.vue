<template>
    <div v-if="stats.apiLoading" class="d-flex justify-content-center align-items-center mt-5">
        <img src="@/assets/images/loader.gif" width="40" alt="">
    </div>
    <div v-else>
        <div v-if="stats.apiError" class="d-flex justify-content-center align-items-center mt-5">
            Error fetching data, Try reloading the page or come back later.
        </div>
        <div v-else>
            <div v-if="!stats.tourResults.length" class="d-flex justify-content-center align-items-center mt-5">
                No matches to show
            </div>
            <div v-else class="animate__animated  animate__bounceInUp">
                <div v-for="({ match_stage, home_team, away_team, home_name, away_name, away_score, home_score, winner, date_played }, i) in stats.tourResults"
                    :key="i" class="card bg-white rounded-0 mb-4">
                    <div class="card-header fw-bolder text-center">
                        {{ match_stage ? match_stage : 'Match' }} -
                        {{ dateFormat(date_played) }},
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 fw-bolder text-uppercase" :class="{ 'winner': winner == home_team }">
                                {{ home_name }}
                                <span class="float-end  " :class="{ 'text-success fw-bolder': winner == home_team }">{{
                                    home_score }}</span>
                            </div>

                            <div class="col-12 fw-bolder text-uppercase" :class="{ 'winner': winner == away_team }">
                                {{ away_name }}
                                <span class="float-end " :class="{ 'text-success fw-bolder': winner == away_team }">{{
                                    away_score }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from './statsStore';
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

.winner {
    border-right: 2px solid green;
}
</style>