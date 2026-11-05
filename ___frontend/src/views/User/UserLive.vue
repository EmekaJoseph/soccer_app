<template>
    <TournamentPage v-model="selectedTournament" :loading="loading" @change="changed">
        <template #actions>
            <button v-if="authStore.isAdmin" class="ui-btn ui-btn-ghost" @click="othersModal = true">
                <i class="bi bi-people"></i> All live matches
            </button>
            <button class="ui-btn ui-btn-primary" @click="startModal = true">
                <i class="bi bi-plus-lg"></i> Start live match
            </button>
        </template>

        <emptyDataComponent v-if="!userData.tournamentLive.length" icon="bi bi-broadcast">
            No live matches. Start one when your match kicks off.
        </emptyDataComponent>
        <div v-else class="row g-4">
            <div class="col-12">
                <div class="live-banner">
                    <span class="ui-live-dot"></span>
                    <span><b>You are live.</b> The match clock keeps running even if you leave this page. Come back here to update the score.</span>
                </div>
            </div>
            <ComponentLive v-for="liveData in userData.tournamentLive" :key="liveData.live_id"
                :team-data="liveData" @ended="reload" />
        </div>
    </TournamentPage>

    <addLiveMatchModal v-if="startModal" :tour="selectedTournament" @close="startModal = false" @started="reload" />
    <otherLiveMatchesModal v-if="othersModal" :tour="selectedTournament" @close="othersModal = false" @changed="reload" />
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
.live-banner {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-sm);
    background: var(--volt-soft);
    color: var(--fg);
    font-size: 0.875rem;
}

.live-banner b {
    font-weight: 600;
}
</style>
