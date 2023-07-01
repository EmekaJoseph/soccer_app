<template>
    <div v-if="stats.apiLoading" class="d-flex justify-content-center align-items-center mt-5">
        <img src="@/assets/images/loader.gif" width="40" alt="">
    </div>
    <div v-else>
        <div v-if="stats.apiError" class="mt-5">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div v-if="!stats.tourStandings.length">
                <emptyDataComponent>
                    No standings
                </emptyDataComponent>
            </div>
            <!-- <div v-else class="animate__animated  animate__bounceInUp"> -->
            <div v-else>
                <div v-if="stats.tour_type == 'cup'">
                    <standings_cupTable :data="stats.tourStandings" />
                </div>
                <div v-else-if="stats.tour_type == 'league'">
                    <standings_leagueTable :data="stats.tourStandings" />
                </div>
                <div v-else>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore';
import standings_cupTable from './standings_cupTable.vue';
import standings_leagueTable from './standings_leagueTable.vue';

const stats = useStatsStore();

</script>

<style scoped>
@media screen and (max-width: 992px) {
    .card {
        font-size: 12px;
    }
}
</style>