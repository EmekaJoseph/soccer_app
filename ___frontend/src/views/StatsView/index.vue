<template>
    <div class="main general-body">
        <div class="fixed-top shadow-s">
            <div class="m-3 ">
                <RouterLink class="text-white me-1 me-lg-3" to="/"> <i class="bi bi-chevron-left"></i></RouterLink>
                <RouterLink to="/" class=""><img class="sm-logo" src="@/assets/images/dlam_academy.png" alt="">
                </RouterLink>
                <span class="fw-bolder text-uppercase float-end">{{ stats.tour_title }}</span>
            </div>
            <div class="mt-5 container">
                <div class="row justify-content-center ">
                    <div class="col-lg-6">
                        <div class="row">
                            <div @click="showPanel(0)" class="menu-item col-3"
                                :class="{ 'menu-active': currentShowing == 0 }">GROUPS</div>
                            <div @click="showPanel(1)" class="menu-item col-3"
                                :class="{ 'menu-active': currentShowing == 1 }">FIXTURES</div>
                            <div @click="showPanel(2)" class="menu-item col-3"
                                :class="{ 'menu-active': currentShowing == 2 }">LIVE
                                <!-- <div class="spinner-grow spinner-grow-sm"> </div> -->
                                <i v-if="stats.tourLives.length"
                                    class=" blinker small ms-1 bi bi-circle-fill text-success"></i>
                            </div>
                            <div @click="showPanel(3)" class="menu-item col-3"
                                :class="{ 'menu-active': currentShowing == 3 }">RESULTS</div>
                            <!-- <div class="col-1 menu-item text-info"><i class="bi bi-info-circle"></i></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="show-panel min-vh-100">
            <div class=" container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <StatsLayout>
                            <Swiper ref="theSwipe" @swiper="onSwiper" @slideChange="onSlideChange">
                                <swiper-slide>
                                    <StandingsPanel />
                                </swiper-slide>
                                <swiper-slide>
                                    <SchedulePanel />
                                </swiper-slide>
                                <swiper-slide>
                                    <LivePanel />
                                </swiper-slide>
                                <swiper-slide>
                                    <ResultsPanel />
                                </swiper-slide>
                            </Swiper>
                        </StatsLayout>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue";
import { useRoute } from 'vue-router';
import { onMounted, ref, onUnmounted } from 'vue'
import { useStatsStore } from '@/store/statsStore'
import StatsLayout from './StatsLayout.vue';
import { useToast } from 'vue-toast-notification';

import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'
import LivePanel from './live.vue'

const $toast = useToast();




const swiper = ref<any>(null)
const onSlideChange = (event: any) => {
    if (event) {
        window.scrollTo(0, 0);
        currentShowing.value = event.activeIndex
    }
};

const onSwiper = (swip: any) => {
    swiper.value = swip
};

function showPanel(sideIndex: number) {
    // if (swiper.value) 
    swiper.value.slideTo(sideIndex)
    currentShowing.value = sideIndex;
    window.scrollTo(0, 0);
}


const stats = useStatsStore()
const route = useRoute()

const currentShowing = ref(0)

onMounted(async () => {
    if (!stats.statsLoaded) {
        stats.tour_id = route.params.tour_id
        stats.apiLoading = true
        await stats.getTourDetails()
        loadAllData()
        stats.getLiveMatches()
        stats.statsLoaded = true
    }
})

function beep() {
    // let audio = new Audio('/audio/ping.mp3');
    // audio.muted = false;
    // audio.addEventListener("canplaythrough", () => {
    //     audio.play()
    // });
}


async function loadAllData() {
    await stats.getStandings()
    await stats.getSchedules()
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
/* .spinner-grow-sm {
    width: 9px;
    height: 9px;
    color: var(--bs-success);
} */

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
    text-align: center;
}

.menu-active {
    background-color: var(--theme-color-3bb);
    border-bottom: 4px solid #fff;

    /* background-color: #fff;
    color: #000;
    font-weight: bold; */
}

.sm-logo {
    width: 50px;
}

.show-panel {
    padding-top: 180px;
}

@media screen and (max-width: 992px) {
    .menu-item {
        padding: 10px;
        font-size: 11px;
    }

    .sm-logo {
        width: 30px;
    }

    .show-panel {
        padding-top: 160px;
    }
}



/* @media screen and (min-width: 992px) {
    .menu-item:hover {
        background-color: var(--theme-color-3bb);
    }
} */
</style>