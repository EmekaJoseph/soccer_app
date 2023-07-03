<template>
    <div class="main general-body">
        <div class="fixed-top shadow-sm">
            <div class="m-3 fw-bolder text-uppercase">
                <RouterLink class="text-white me-1 me-lg-3" to="/"> <i class="bi bi-arrow-left"></i></RouterLink>
                {{ stats.tour_title }}
                <span class="float-end"><img class="sm-logo" src="@/assets/images/dlam_academy.png" alt=""></span>
            </div>
            <div class="mt-5 container">
                <div class="row justify-content-center ">
                    <div class="col-md-10 col-lg-6">
                        <div class="d-flex justify-content-between menu mb-0">
                            <div @click="showPanel('standings')" class="menu-item"
                                :class="{ ' shadow-sm menu-active': currentShowing == 'standings' }">STANDINGS
                            </div>
                            <div @click="showPanel('matches')" class="menu-item"
                                :class="{ ' shadow-sm menu-active': currentShowing == 'matches' }">MATCHES</div>
                            <div @click="showPanel('results')" class="menu-item"
                                :class="{ 'shadow-sm menu-active': currentShowing == 'results' }">RESULTS</div>
                            <div @click="showPanel('live')" class="menu-item"
                                :class="{ 'shadow-sm menu-active': currentShowing == 'live' }">LIVE
                                <i v-if="stats.tourLives.length" class="bi bi-circle-fill text-success small"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="show-panel min-vh-100" ref="show_panel">
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
                        <div v-if="currentShowing == 'live'">
                            <LivePanel />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
import { onMounted, ref, onUnmounted, watch } from 'vue'
import { useStatsStore } from '@/store/statsStore'

import { useSwipe } from '@vueuse/core'

import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'
import LivePanel from './live.vue'

const stats = useStatsStore()
const route = useRoute()

const currentShowing = ref('standings')

const show_panel = ref<any>(null)
const { direction } = useSwipe(show_panel, {
    onSwipe() {
        if (direction.value == 'left') {
            if (currentShowing.value == 'standings') {
                currentShowing.value = 'matches'
            }
            else if (currentShowing.value == 'matches') {
                currentShowing.value = 'results'
            }
            else if (currentShowing.value == 'results') {
                currentShowing.value = 'live'
            }
        }
        else if (direction.value == 'right') {
            if (currentShowing.value == 'live') {
                currentShowing.value = 'results'
            }
            else if (currentShowing.value == 'results') {
                currentShowing.value = 'matches'
            }
            else if (currentShowing.value == 'matches') {
                currentShowing.value = 'standings'
            }
        }
    }
})

onMounted(async () => {
    stats.tour_id = route.params.tour_id
    stats.apiLoading = true
    await stats.getTourDetails()
    loadAllData()
    stats.getLiveMatches()
})

async function loadAllData() {
    await stats.getStandings()
    await stats.getSchedules()
    stats.getResults()
}

// load all data every 3min(180secs)
let allDataInterval = setInterval(() => {
    loadAllData()
}, 180000)

// load live results every 1min(60secs)
let liveMatchInterval = setInterval(() => {
    stats.getLiveMatches()
}, 60000)

onUnmounted(() => {
    clearInterval(allDataInterval)
    clearInterval(liveMatchInterval)
})

function showPanel(name: string) {
    currentShowing.value = name;
    window.scrollTo(0, 0);
}
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

.sm-logo {
    width: 50px;
}

@media screen and (max-width: 992px) {
    .menu-item {
        padding: 10px;
        font-size: 10px;
    }

    .sm-logo {
        width: 30px;
    }
}

.show-panel {
    padding-top: 160px;
}
</style>