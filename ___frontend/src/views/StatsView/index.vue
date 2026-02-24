<template>
    <div class="main general-body stats-page">
        <!-- Modern Header -->
        <div class="fixed-top glass-header">
            <div class="container py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <RouterLink class="text-white me-3 hover-scale" to="/">
                            <i class="bi bi-chevron-left fs-4"></i>
                        </RouterLink>
                        <RouterLink to="/" class="d-none d-sm-block">
                            <img class="logo-modern" src="@/assets/images/dlam_academy.png" alt="Logo">
                        </RouterLink>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-gradient text-uppercase small ls-1">{{ stats.tour_title }}</div>
                        <div class="small text-white-50 mt-1 d-none d-md-block">{{ today_date }}</div>
                    </div>
                </div>

                <!-- Modern Navigation Pills -->
                <div class="navigation-wrapper mt-4">
                    <div class="nav-pills-modern">
                        <div @click="showPanel(1)" :class="{ 'active': currentShowing == 1 }" class="nav-pill">
                            <i class="bi bi-calendar-event me-2"></i> FIXTURES
                        </div>
                        <div @click="showPanel(2)" :class="{ 'active': currentShowing == 2 }" class="nav-pill">
                            <i class="bi bi-broadcast me-2"></i> LIVE
                            <span v-if="stats.tourLives.length" class="pulse-indicator"></span>
                        </div>
                        <div @click="showPanel(3)" :class="{ 'active': currentShowing == 3 }" class="nav-pill">
                            <i class="bi bi-trophy me-2"></i> RESULTS
                        </div>
                        <div @click="showPanel(4)" :class="{ 'active': currentShowing == 4 }" class="nav-pill">
                            <i class="bi bi-people me-2"></i> TEAMS
                        </div>
                        <div @click="showPanel(0)" :class="{ 'active': currentShowing == 0 }" class="nav-pill">
                            <i class="bi bi-grid me-2"></i> GROUPS
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-area container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <StatsLayout>
                        <div class="panel-container animate__animated animate__fadeIn">
                            <SchedulePanel v-if="currentShowing == 1" />
                            <LivePanel v-if="currentShowing == 2" />
                            <ResultsPanel v-if="currentShowing == 3" />
                            <InfoPanel v-if="currentShowing == 4" />
                            <StandingsPanel v-if="currentShowing == 0" />
                        </div>
                    </StatsLayout>
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
const today_date = useDateFormat(useNow(), `dddd, DD/MM/YYYY`);
const { vibrate } = useVibrate({ pattern: [300, 100, 300] })

const stats = useStatsStore()
const route = useRoute()
const router = useRouter()
const currentShowing = ref(3)

function showPanel(index: number) {
    if (index == 4) {
        stats.getTourTeamsInfo()
    }
    currentShowing.value = index;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

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

let allDataInterval = setInterval(() => {
    loadAllData()
}, 180000)

onUnmounted(() => {
    clearInterval(allDataInterval)
})

// @ts-ignore
window.Echo.channel('liveMatch').listen('liveScore', async (e) => {
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
    stats.tourLives = stats.tourLives.filter((x) => x.live_id != e.live_id)
})

// @ts-ignore
window.Echo.channel('startMatch').listen('startMatch', async (e) => {
    await stats.getLiveMatches()
})
</script>

<style scoped>
.stats-page {
    background: var(--primary-gradient);
    min-height: 100vh;
    padding-bottom: 50px;
}

.glass-header {
    background: rgba(15, 32, 39, 0.8);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1020;
}

.logo-modern {
    height: 40px;
    filter: drop-shadow(0 0 8px rgba(0, 242, 254, 0.3));
}

.navigation-wrapper {
    overflow-x: auto;
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.navigation-wrapper::-webkit-scrollbar {
    display: none;
}

.nav-pills-modern {
    display: flex;
    justify-content: center;
    gap: 12px;
    padding: 4px;
}

@media (max-width: 768px) {
    .nav-pills-modern {
        justify-content: flex-start;
    }
}

.nav-pill {
    padding: 8px 20px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.6);
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
}

.nav-pill.active {
    background: var(--accent-gradient);
    border-color: transparent;
    color: #0f2027;
    box-shadow: 0 4px 15px rgba(0, 242, 254, 0.3);
    transform: translateY(-2px);
}

.nav-pill:hover:not(.active) {
    background: rgba(255, 255, 255, 0.12);
    color: white;
}

.pulse-indicator {
    width: 8px;
    height: 8px;
    background: #4caf50;
    border-radius: 50%;
    margin-left: 8px;
    box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
    }

    70% {
        transform: scale(1);
        box-shadow: 0 0 0 10px rgba(76, 175, 80, 0);
    }

    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
    }
}

.content-area {
    padding-top: 180px;
}

.ls-1 {
    letter-spacing: 1px;
}

.hover-scale:hover {
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .content-area {
        padding-top: 160px;
    }

    .logo-modern {
        height: 30px;
    }
}
</style>
