<template>
    <div class="main general-body">
        <div class="fixed-top shadow-s">
            <div class="m-3 ">
                <RouterLink class="text-white me-1 me-lg-3" to="/"> <i class="bi bi-chevron-left"></i></RouterLink>
                <RouterLink to="/" class=""><img class="sm-logo" src="@/assets/images/dlam_academy.png" alt="">
                </RouterLink>
                <span class="fw-bolder text-uppercase float-end">{{ stats.tour_title }}</span>
            </div>
            <div class="mt-4 container">

                <div class="row justify-content-center ">
                    <div class="col-md-5 col-12">
                        <div class="small text-dim scrolling-text"> {{ today_date }}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="menu-div ">
                            <div class="d-flex justify-content-between">

                                <div @click="showPanel(1)" :class="{ 'menu-active': currentShowing == 1 }"
                                    class=" menu-item">
                                    <i class="bi bi-calendar-event"></i> FIXTURES
                                </div>
                                <div @click="showPanel(2)" :class="{ 'menu-active': currentShowing == 2 }"
                                    class=" menu-item">
                                    <i class="bi bi-tv"></i> LIVE
                                    <i v-if="stats.tourLives.length"
                                        class=" blinker small ms-1 bi bi-circle-fill text-success"></i>
                                </div>
                                <div @click="showPanel(3)" :class="{ 'menu-active': currentShowing == 3 }"
                                    class=" menu-item">
                                    <i class="bi bi-flag"></i> RESULTS
                                </div>
                                <div @click="showPanel(4)" :class="{ 'menu-active': currentShowing == 4 }"
                                    class=" menu-item">
                                    <i class="bi bi-people"></i> TEAMS
                                </div>
                                <div @click="showPanel(0)" :class="{ 'menu-active': currentShowing == 0 }"
                                    class=" menu-item">
                                    <i class="bi bi-collection"></i> GROUPS
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="min-vh-10">
            <div class=" show-panel container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <StatsLayout>
                            <StandingsPanel v-show="currentShowing == 0" />
                            <SchedulePanel v-show="currentShowing == 1" />
                            <LivePanel v-show="currentShowing == 2" />
                            <ResultsPanel v-show="currentShowing == 3" />
                            <InfoPanel v-show="currentShowing == 4" />
                        </StatsLayout>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref, onUnmounted } from 'vue'
import { useStatsStore } from '@/store/statsStore'
import StatsLayout from './StatsLayout.vue';
import { useToast } from 'vue-toast-notification';
import { useNow, useDateFormat, useVibrate } from '@vueuse/core';

import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'
import LivePanel from './live.vue'
import InfoPanel from './informationCenter.vue'

const $toast = useToast();

const today_date = useDateFormat(useNow(), `dddd, DD/MMMM/YYYY , H:mm aa`);
const { vibrate, stop } = useVibrate({ pattern: [300, 100, 300] })



function showPanel(Index: number) {
    console.log(Index);

    if (Index == 4) {
        stats.getTourTeamsInfo()
    }
    currentShowing.value = Index;
    window.scrollTo(0, 0);
}


const stats = useStatsStore()
const route = useRoute()
const router = useRouter()

const currentShowing = ref(3)

onMounted(async () => {
    if (!stats.statsLoaded) {
        stats.tour_id = route.params.tour_id
        stats.apiLoading = true
        await stats.getTourDetails()
        loadAllData()
        stats.getLiveMatches()
        stats.getTourTeamsInfo()
        stats.statsLoaded = true
    }
})

function beep() {
    var audio = new Audio('/audio/ping.mp3');
    audio.play()
    vibrate()
}

async function loadAllData() {
    await stats.getStandings()
    await stats.getMatches()
    await stats.getResults()
}

// load all data every 180secs(3mins)
let allDataInterval = setInterval(() => {
    loadAllData()
}, 180000)

// load live results every 10secs(0.10mins)
// let liveMatchInterval = setInterval(() => {
//     stats.getLiveMatches()
// }, 10000)

onUnmounted(() => {
    clearInterval(allDataInterval)
    // clearInterval(liveMatchInterval)
})


// #################### Listen to websocet ################
// @ts-ignore
window.Echo.channel('liveMatch').listen('liveScore', async (e) => {
    // console.log(e); // the data from the server
    let liveMatch = stats.tourLives.find((x) => x.live_id == e.live_id)
    if (!liveMatch) {
        await stats.getLiveMatches()
    }

    if (e.results.home_team_score > liveMatch.home_team_score || e.results.away_team_score > liveMatch.away_team_score) {
        $toast.success('Goooooooal!', { position: 'top-right' });
        beep()
    }

    liveMatch.home_team_score = e.results.home_team_score
    liveMatch.away_team_score = e.results.away_team_score
    liveMatch.curr_time = e.results.curr_time
})

// @ts-ignore
window.Echo.channel('endMatch').listen('endMatch', (e) => {
    // console.log(e); // the data from the server
    stats.tourLives = stats.tourLives.filter((x) => x.live_id != e.live_id)
})

// @ts-ignore
window.Echo.channel('startMatch').listen('startMatch', async (e) => {
    await stats.getLiveMatches()
})
// #################### Listen to websocet ################

</script>

<style scoped>
.fixed-top {
    background-color: var(--theme-color-3b);
    color: #fff !important;
    /* height: 100px; */
}

.menu-div {
    /* margin: 10px; */
    padding-top: 10px;
    padding-bottom: 20px;
    width: 100%;
    overflow-x: auto;
    white-space: nowrap;
    overflow-y: hidden;
}

.menu-div .menu-item {
    /* display: inline-block; */
    font-size: 13px;
    margin-right: 10px;
    padding: 5px 14px;
    border-radius: 30px;
    cursor: pointer;
    background-color: var(--theme-color-3bb);
    color: #c7c790;
    border: solid 1px var(--theme-color-3);
}

.menu-active {
    color: #ffff00 !important;
    font-weight: bolder;
    background-color: #02496d !important;
    /* background-color: #fff !important; */
}


.sm-logo {
    width: 50px;
}

.show-panel {
    padding-top: 200px;
}

@media screen and (max-width: 992px) {
    .menu-item {
        padding: 10px;
        font-size: 14px;
    }

    .sm-logo {
        width: 30px;
    }

    /* .show-panel {
        padding-top: 160px;
    } */
}
</style>