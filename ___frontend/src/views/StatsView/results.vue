<template>
    <div v-if="!stats.tourResults.length" class="min-vh-100">
        <emptyDataComponent>
            No Results recorded.
        </emptyDataComponent>
    </div>
    <!-- <div v-else class="animate__animated  animate__bounceInUp"> -->
    <div v-else class="min-vh-100">
        <div class="row justify-content-ce gy-3">

            <div v-for="({ match_stage, home_team, away_team, home_name, away_name, away_score, home_score, winner, date_played, home_score_pen, away_score_pen }, i) in stats.tourResults"
                class="col-6">
                <div class="card bg-white h-100 small ">
                    <div class="card-header fw-bolder rounded-0 bg-transparent text-center small p-0 py-1 mx-2">
                        {{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}
                        ({{ dateFormat(date_played) }})
                    </div>
                    <div class="card-body py-2">
                        <div :class="{ 'text-muted ': winner == away_team }">{{ home_name }}:
                            <span class="float-end" :class="{ 'fw-bold ': winner == home_team }">
                                {{ home_score }}
                            </span>
                        </div>
                        <div :class="{ 'text-muted ': winner == home_team }">{{ away_name }}:
                            <span class="float-end " :class="{ 'fw-bold ': winner == away_team }">
                                {{ away_score }}
                            </span>
                        </div>
                    </div>
                    <div v-show="home_score_pen" class="card-footer m-0 border-0">
                        PEN: <span class="float-end">
                            <span :class="{ 'fw-bold ': (home_score_pen > away_score_pen) }"> {{ home_score_pen }} </span> :
                            <span :class="{ 'fw-bold ': away_score_pen > home_score_pen }"> {{ away_score_pen }} </span>
                        </span>
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