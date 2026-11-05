<template>
    <main class="public-page stats-page" data-bs-theme="dark">
        <div class="stats-wrap topbar">
            <RouterLink to="/" class="d-inline-flex text-decoration-none" aria-label="SoccerApp home">
                <BrandMark />
            </RouterLink>
            <div class="d-flex gap-2">
                <button v-if="stats.tourTeamsInfo.length >= 3 && !hasPredicted" class="ui-btn ui-btn-ghost" @click="predictionOpen = true">
                    <i class="bi bi-trophy" aria-hidden="true"></i> Predict
                </button>
                <button class="ui-btn ui-btn-ghost" title="Send feedback" aria-label="Send feedback" @click="feedbackOpen = true">
                    <i class="bi bi-chat" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <template v-if="!stats.notFound">
            <header v-if="!stats.apiLoading" class="stats-wrap tour-head">
                <img v-if="stats.tour_logo" class="tour-logo" :src="fx.resolvePhotoSrc(stats.tour_logo)" alt="">
                <div class="tour-text">
                    <h1>{{ stats.tour_title }}</h1>
                    <p class="tour-meta"><span class="tour-type">{{ stats.tour_type }}</span> · {{ today_date }}</p>
                    <p v-if="stats.tour_desc" class="tour-desc">{{ stats.tour_desc }}</p>
                </div>
            </header>

            <nav class="tabs" aria-label="Tournament sections">
                <div class="stats-wrap tabs-inner">
                    <button v-for="tab in tabs" :key="tab.id" type="button" class="tab" :class="{ active: currentShowing == tab.id }"
                        :aria-current="currentShowing == tab.id ? 'page' : undefined" @click="showPanel(tab.id)">
                        <span v-if="tab.id == 'live' && stats.tourLives.length" class="ui-live-dot"></span>
                        {{ tab.label }}
                    </button>
                </div>
            </nav>
        </template>

        <div class="stats-wrap content">
            <StatsLayout>
                <div :key="currentShowing" class="animate__animated animate__fadeIn animate__faster">
                    <component :is="panels[currentShowing]" />
                </div>
            </StatsLayout>
        </div>

        <predictionModal v-if="predictionOpen" :teams="stats.tourTeamsInfo" :tour_id="stats.tour_id"
            @close="predictionOpen = false" @done="onPredicted" />
        <feedbackModal v-if="feedbackOpen" :tour_id="stats.tour_id" :name="visitorName"
            @close="feedbackOpen = false" @done="feedbackOpen = false" />
    </main>
</template>

<script setup lang="ts">
import { computed, onUnmounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useNow, useDateFormat, useStorage, useVibrate } from '@vueuse/core'
import { useStatsStore } from '@/store/statsStore'
import { listenToTournament } from '@/lib/echo'
import fx from '@/store/useFunctions'
import BrandMark from '@/components/BrandMark.vue'
import StatsLayout from './StatsLayout.vue'
import StandingsPanel from './standings.vue'
import ResultsPanel from './results.vue'
import SchedulePanel from './schedules.vue'
import LivePanel from './live.vue'
import InfoPanel from './informationCenter.vue'
import predictionModal from '@/components/modals/predictionModal.vue'
import feedbackModal from '@/components/modals/feedbackModal.vue'

const REFRESH_EVERY_MS = 3 * 60 * 1000

const panels = { live: LivePanel, fixtures: SchedulePanel, results: ResultsPanel, table: StandingsPanel, teams: InfoPanel }
type PanelId = keyof typeof panels

const today_date = useDateFormat(useNow(), 'ddd D MMM YYYY')
const { vibrate } = useVibrate({ pattern: [300, 100, 300] })

const stats = useStatsStore()
const route = useRoute()
const currentShowing = ref<PanelId>('results')
const predictionOpen = ref(false)
const feedbackOpen = ref(false)

const tabs = computed<{ id: PanelId; label: string }[]>(() => [
    { id: 'live', label: 'Live' },
    { id: 'fixtures', label: 'Fixtures' },
    { id: 'results', label: 'Results' },
    { id: 'table', label: stats.tour_type == 'league' ? 'Table' : 'Groups' },
    { id: 'teams', label: 'Teams' },
])

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

function showPanel(id: PanelId) {
    currentShowing.value = id
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
    if (stats.tourLives.length) currentShowing.value = 'live'

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
    min-height: 100vh;
    padding-bottom: 72px;
}

.stats-wrap {
    width: 100%;
    max-width: 800px;
    margin-inline: auto;
    padding-inline: 16px;
}

.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
}

.tour-head {
    display: flex;
    align-items: center;
    gap: 18px;
    padding-block: 24px 28px;
}

.tour-logo {
    flex: none;
    width: 64px;
    height: 64px;
    border-radius: 14px;
    object-fit: cover;
}

.tour-text {
    min-width: 0;
    overflow-wrap: anywhere;
}

.public-page .tour-text h1 {
    margin: 0;
    font-size: clamp(1.75rem, 5vw, 2.75rem);
    line-height: 1.08;
    letter-spacing: -0.04em;
}

.tour-meta {
    margin: 8px 0 0;
    font-family: var(--font-mono);
    font-size: 0.75rem;
}

.tour-type {
    color: var(--volt);
    text-transform: capitalize;
}

.tour-desc {
    max-width: 60ch;
    margin: 12px 0 0;
    font-size: 0.9375rem;
    line-height: 1.55;
}

.tabs {
    position: sticky;
    top: 0;
    z-index: 1020;
    border-block: 1px solid var(--line);
    background: rgba(10, 12, 11, 0.82);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
}

.tabs-inner {
    display: flex;
    gap: 4px;
    overflow-x: auto;
    scrollbar-width: none;
}

.tabs-inner::-webkit-scrollbar {
    display: none;
}

.tab {
    display: inline-flex;
    flex: none;
    align-items: center;
    gap: 8px;
    padding: 14px 12px;
    border: 0;
    border-bottom: 2px solid transparent;
    background: none;
    color: var(--fg-muted);
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.15s ease;
}

.tab:hover {
    color: var(--fg);
}

.tab.active {
    border-bottom-color: var(--volt);
    color: var(--fg);
}

/* Inset, so the tab strip's scroll container cannot clip the ring. */
.public-page .tab:focus-visible {
    outline-offset: -3px;
}

.public-page .tab:focus {
    box-shadow: none !important;
}

.content {
    padding-top: 28px;
}

@media (max-width: 575.98px) {
    .tab {
        padding-inline: 10px;
    }

    .tour-logo {
        width: 52px;
        height: 52px;
    }
}
</style>
