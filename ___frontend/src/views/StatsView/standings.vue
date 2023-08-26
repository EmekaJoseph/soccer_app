<template>
    <div v-if="!stats.tourStandings.length" class="min-vh-100">
        <emptyDataComponent>
            No standings
        </emptyDataComponent>
    </div>
    <!-- <div v-else class="animate__animated  animate__bounceInUp"> -->
    <div v-else class="min-vh-100">
        <div v-if="stats.tour_type == 'cup'">
            <standings_cupTable :data="stats.tourStandings" />
        </div>
        <div v-else-if="stats.tour_type == 'league'">
            <standings_leagueTable :data="stats.tourStandings" />
        </div>
        <div v-else>

        </div>
        <div class="backdrop "></div>
        <div class="card rounded-0">
            <div class="card-body text-center py-1">END</div>
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

.bg-transparen {
    background-color: rgba(136, 53, 53, 0.938);
}

.backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    width: 100vw;
    height: 100%;
    background-color: #00000000;
}
</style>