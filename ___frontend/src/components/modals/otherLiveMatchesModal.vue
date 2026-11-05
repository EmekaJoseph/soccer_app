<template>
    <EndLiveMatchModal v-if="ending" :live="ending" @close="ending = null" @ended="onEnded" />
    <BaseModal v-else title="All live matches" @close="emit('close')">
        <div class="modal-body">
            <componentLoadingSpinner v-if="isLoading" />
            <div v-else-if="error" class="text-danger text-center my-4">{{ error }}</div>
            <ul v-else-if="liveMatchesList.length" class="live-list">
                <li v-for="live in liveMatchesList" :key="live.live_id">
                    <div class="live-teams">{{ live.home_team }} v {{ live.away_team }} <span class="tag tag-live">{{ live.curr_time }}'</span></div>
                    <div class="live-score">{{ live.home_team_score }}–{{ live.away_team_score }}</div>
                    <small>Scored by {{ live.isMe ?? live.creator?.email ?? 'a removed scorer' }}</small>
                    <div class="mt-2">
                        <button type="button" class="ui-btn ui-btn-danger ui-btn-sm" @click="ending = live">End match</button>
                    </div>
                </li>
            </ul>
            <emptyDataComponent v-else icon="bi bi-broadcast">No live matches</emptyDataComponent>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'
import EndLiveMatchModal from './EndLiveMatchModal.vue'

const props = defineProps<{ tour: any }>()
const emit = defineEmits<{ close: []; changed: [] }>()

const liveMatchesList = ref<any[]>([])
const isLoading = ref(true)
const error = ref('')
const ending = ref<any>(null)

onMounted(loadList)

function onEnded() {
    ending.value = null
    emit('changed')
    loadList()
}

async function loadList() {
    error.value = ''
    isLoading.value = true
    try {
        liveMatchesList.value = (await api.getLiveMatchesForAdmin(props.tour.tour_id)).data
    } catch (e) {
        error.value = apiErrorMessage(e)
    } finally {
        isLoading.value = false
    }
}
</script>

<style scoped>
.live-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.live-list li {
    padding-block: 0.85rem;
    text-align: center;
}

.live-list li + li {
    border-top: 1px solid var(--line);
}

.live-teams {
    color: var(--fg);
    overflow-wrap: anywhere;
}

.live-score {
    margin-block: 0.25rem;
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 1.25rem;
    font-weight: 500;
}
</style>
