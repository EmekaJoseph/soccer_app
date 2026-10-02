<template>
    <div class="container px-3">
        <componentLoadingSpinner v-if="loading" />
        <internetErrorComponent v-else-if="userData.apiError" />
        <div v-else class="row g-4">
            <tourDropdownSelect v-model="selectedTournament" @change="changed" />

            <div v-if="!selectedTournament" class="col-12">
                <emptyDataComponent>Create a tournament on the dashboard first.</emptyDataComponent>
            </div>

            <div v-else class="col-lg-12">
                <emptyDataComponent v-if="!userData.tournamentLive.length">
                    No live matches. Press <i class="bi bi-plus-lg"></i> to start one.
                </emptyDataComponent>
                <div class="row gy-5">
                    <div v-if="userData.tournamentLive.length" class="col-12">
                        <div class="live-banner">
                            <span class="live-dot"></span>
                            <span><b>You are live.</b> Keep this page open so the match clock keeps running.</span>
                        </div>
                    </div>
                    <ComponentLive v-for="liveData in userData.tournamentLive" :key="liveData.live_id"
                        :team-data="liveData" @ended="reload" />
                </div>
            </div>
        </div>

        <addLiveMatchModal v-if="startModal" :tour="selectedTournament" @close="startModal = false" @started="reload" />
        <otherLiveMatchesModal v-if="othersModal" :tour="selectedTournament" @close="othersModal = false" />

        <template v-if="selectedTournament">
            <div class="fab-stack">
                <button v-if="authStore.isAdmin" class="fab secondary" title="All live matches in this tournament" @click="othersModal = true">
                    <i class="bi bi-people"></i>
                </button>
                <button class="fab" title="Start a live match" @click="startModal = true">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import { useAuthStore } from '@/store/authStore'
import { useTournamentPicker } from '@/composables/useTournamentPicker'
import ComponentLive from './ComponentLive.vue'
import addLiveMatchModal from '@/components/modals/addLiveMatchModal.vue'
import otherLiveMatchesModal from '@/components/modals/otherLiveMatchesModal.vue'

const userData = useUserDataStore()
const authStore = useAuthStore()
const startModal = ref(false)
const othersModal = ref(false)

const { selectedTournament, changed, loading } = useTournamentPicker((tourId) => userData.getLiveMatchesByUser(tourId))

function reload() {
    userData.getLiveMatchesByUser(selectedTournament.value.tour_id)
}
</script>

<style scoped>
.fab-stack {
    position: fixed;
    right: 24px;
    bottom: 32px;
    z-index: 999;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.live-banner {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1.1rem;
    border-radius: var(--radius-md);
    background: #fff1f1;
    color: #a61b20;
    border: 1px solid #fbd0d1;
}

.live-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--danger);
    box-shadow: 0 0 0 0 rgba(229, 72, 77, 0.6);
    animation: pulse 1.6s infinite;
}

@keyframes pulse {
    70% { box-shadow: 0 0 0 10px rgba(229, 72, 77, 0); }
    100% { box-shadow: 0 0 0 0 rgba(229, 72, 77, 0); }
}
</style>
