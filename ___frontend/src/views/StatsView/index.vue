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
                            <div @click="showPanel(0)" class="menu-item" :class="{ 'menu-active': currentShowing == 0 }">
                                STANDINGS
                            </div>
                            <div @click="showPanel(1)" class="menu-item" :class="{ 'menu-active': currentShowing == 1 }">
                                SCHEDULES</div>
                            <div @click="showPanel(2)" class="menu-item" :class="{ 'menu-active': currentShowing == 2 }">
                                RESULTS</div>
                            <div @click="showPanel(3)" class="menu-item" :class="{ 'menu-active': currentShowing == 3 }">
                                LIVE
                                <i v-if="stats.tourLives.length" style="font-size: 7px;"
                                    class="float-end mt-1 ms-1 bi bi-circle-fill text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="show-panel min-vh-100">
            <div class=" container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <StatsLayout>
                            <Swiper ref="theSwipe" @swiper="onSwiper" @slideChange="onSlideChange">
                                <swiper-slide>
                                    <StandingsPanel />
                                </swiper-slide>
                                <swiper-slide>
                                    <SchedulePanel />
                                </swiper-slide>
                                <swiper-slide>
                                    <ResultsPanel />
                                </swiper-slide>
                                <swiper-slide>
                                    <LivePanel />
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

import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'
import LivePanel from './live.vue'


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


.menu-item {
    font-size: 12px;
    cursor: pointer;
    padding: 10px 30px;
    border-radius: 0px;
}

.menu-active {
    background-color: var(--theme-color-3bb);
    border-bottom: 4px solid #fff;
}

.sm-logo {
    width: 50px;
}

@media screen and (max-width: 992px) {
    .menu-item {
        padding: 10px;
        font-size: 12px;
    }

    .sm-logo {
        width: 30px;
    }
}

.show-panel {
    padding-top: 160px;
}

/* @media screen and (min-width: 992px) {
    .menu-item:hover {
        background-color: var(--theme-color-3bb);
    }
} */
</style>