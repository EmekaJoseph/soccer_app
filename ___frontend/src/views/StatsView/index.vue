<template>
    <div class="main general-body">
        <div class="fixed-top shadow-sm">
            <div class="m-3 fw-bolder text-uppercase">
                <RouterLink class="text-white me-1 me-lg-3" to="/"> <i class="bi bi-arrow-left"></i></RouterLink>
                {{ stats.tour_title }}
            </div>
            <div class="mt-5 container">
                <div class="row justify-content-center ">
                    <div class="col-md-10 col-lg-6">
                        <div class="d-flex justify-content-between menu mb-0">
                            <div @click="currentShowing = 'standings'" class="menu-item"
                                :class="{ ' shadow-sm menu-active': currentShowing == 'standings' }">STANDINGS
                            </div>
                            <div @click="currentShowing = 'matches'" class="menu-item"
                                :class="{ ' shadow-sm menu-active': currentShowing == 'matches' }">MATCHES</div>
                            <div @click="currentShowing = 'results'" class="menu-item"
                                :class="{ 'shadow-sm menu-active': currentShowing == 'results' }">RESULTS</div>
                            <!-- <div class="menu-item">LIVE</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="show-panel min-vh-100">
            <div class=" container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div v-if="currentShowing == 'standings'">
                            <StandingsPanel />
                        </div>
                        <div v-if="currentShowing == 'matches'">
                            <SchedulePanel />
                        </div>
                        <div v-if="currentShowing == 'results'">
                            <ResultsPanel />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue'
import { useStatsStore } from './statsStore'

import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'

const stats = useStatsStore()
const route = useRoute()

const currentShowing = ref('standings')

onMounted(() => {
    stats.tour_id = route.params.tour_id
    stats.apiLoading = true
    stats.getStandings()
    stats.getResults()
    stats.getSchedules()
    stats.getTourDetails()
})
</script>

<style scoped>
.fixed-top {
    background-color: var(--theme-color-3b);
    color: #fff !important;
    /* height: 100px; */
}


.menu-item {
    font-size: 12px;
    cursor: pointer;
    padding: 10px 30px;
    border-radius: 0px;
}




.menu-item:hover {
    background-color: var(--theme-color-3bb);
}

.menu-active {
    background-color: var(--theme-color-3bb);
    border-bottom: 3px solid #fff;
}

@media screen and (max-width: 992px) {
    .menu-item {
        padding: 10px;
    }
}

.show-panel {
    padding-top: 160px;
}
</style>