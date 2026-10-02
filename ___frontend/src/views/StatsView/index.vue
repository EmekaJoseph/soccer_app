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
                            <img v-if="stats.tour_logo" class="logo-modern rounded-3" :src="fx.resolvePhotoSrc(stats.tour_logo)" alt="">
                            <img v-else class="logo-modern" src="/icons/soccer.svg" alt="">
                        </RouterLink>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button v-if="stats.tourTeamsInfo.length >= 3 && !hasPredicted" class="btn btn-sm btn-outline-info rounded-pill"
                            @click="predictionOpen = true">
                            <i class="bi bi-trophy"></i> <span class="d-none d-sm-inline">Predict</span>
                        </button>
                        <button class="btn btn-sm btn-outline-light rounded-pill" title="Send feedback" @click="feedbackOpen = true">
                            <i class="bi bi-chat-heart"></i>
                        </button>
                        <div class="text-end ms-2">
                            <div class="fw-bold text-gradient text-uppercase small ls-1">{{ stats.tour_title }}</div>
                            <div class="small text-white-50 mt-1 d-none d-md-block">{{ today_date }}</div>
                        </div>
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
                            <i class="bi bi-grid me-2"></i> {{ stats.tour_type == 'league' ? 'TABLE' : 'GROUPS' }}
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

        <predictionModal v-if="predictionOpen" :teams="stats.tourTeamsInfo" :tour_id="stats.tour_id"
            @close="predictionOpen = false" @done="onPredicted" />
        <feedbackModal v-if="feedbackOpen" :tour_id="stats.tour_id" :name="visitorName"
            @close="feedbackOpen = false" @done="feedbackOpen = false" />
    </div>
</template>

<script setup lang="ts">
import { computed, onUnmounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useNow, useDateFormat, useStorage, useVibrate } from '@vueuse/core'
import { useStatsStore } from '@/store/statsStore'
import { listenToTournament } from '@/lib/echo'
import fx from '@/store/useFunctions'
import StatsLayout from './StatsLayout.vue'
import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'
import LivePanel from './live.vue'
import InfoPanel from './informationCenter.vue'
import predictionModal from '@/components/modals/predictionModal.vue'
import feedbackModal from '@/components/modals/feedbackModal.vue'

const REFRESH_EVERY_MS = 3 * 60 * 1000

const today_date = useDateFormat(useNow(), 'dddd, DD/MM/YYYY')
const { vibrate } = useVibrate({ pattern: [300, 100, 300] })

const stats = useStatsStore()
const route = useRoute()
const currentShowing = ref(3)
const predictionOpen = ref(false)
const feedbackOpen = ref(false)

// Remember per tournament whether this browser already predicted.
const predictedTournaments = useStorage<string[]>('socc_predicted', [])
const visitorName = useStorage('socc_visitor', '')
const hasPredicted = computed(() => predictedTournaments.value.includes(stats.tour_id))

function onPredicted(name: string) {
    predictedTournaments.value = [...predictedTournaments.value, stats.tour_id]
    visitorName.value = name
    predictionOpen.value = false
    fx.toast.success(`Prediction saved. Good luck, ${name}!`)
}

function showPanel(index: number) {
    currentShowing.value = index
    window.scrollTo({ top: 0, behavior: 'smooth' })
}

function goalAlert() {
    fx.toast.success('Goooooooal!')
    new Audio('/audio/ping.mp3').play().catch(() => {}) // browsers block audio until the page is tapped
    vibrate()
}

let stopListening = () => {}

watch(() => route.params.tour_id as string, async (tourId) => {
    if (!tourId) return
    stopListening()
    await stats.load(tourId)
    if (stats.tourLives.length) currentShowing.value = 2

    stopListening = listenToTournament(tourId, {
        started: () => stats.getLiveMatches(),
        updated: (e) => {
            if (stats.applyLiveUpdate(e)) goalAlert()
        },
        ended: (e) => {
            stats.removeLive(e.live_id)
            stats.refresh() // the result may have been saved
        },
    })
}, { immediate: true })

const refreshTimer = setInterval(() => stats.refresh(), REFRESH_EVERY_MS)

onUnmounted(() => {
    clearInterval(refreshTimer)
    stopListening()
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
