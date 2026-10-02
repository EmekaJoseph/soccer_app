<template>
    <div class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-scrollable animate__animated animate__slideInDown animate__faster">
            <div class="modal-content">
                <div class="modal-header border-0 bg-light">
                    <span class="fw-bold">All live matches</span>
                    <button class="btn btn-close" aria-label="Close" @click="emit('close')"></button>
                </div>
                <div class="modal-body">
                    <componentLoadingSpinner v-if="isLoading" />
                    <div v-else-if="error" class="text-danger text-center my-4">{{ error }}</div>
                    <ul v-else-if="liveMatchesList.length" class="list-group list-group-flush">
                        <li v-for="live in liveMatchesList" :key="live.live_id" class="list-group-item text-center">
                            <div>
                                {{ live.home_team }} <span class="fw-bold">VS</span> {{ live.away_team }}
                                ({{ live.curr_time }}')
                            </div>
                            <div class="fs-5 fw-bold">{{ live.home_team_score }} : {{ live.away_team_score }}</div>
                            <small class="text-muted">scored by: </small>
                            <span>{{ live.isMe ?? live.creator?.email ?? 'unknown' }}</span>
                        </li>
                    </ul>
                    <div v-else class="text-center my-5">NO LIVE MATCHES</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'

const props = defineProps<{ tour: any }>()
const emit = defineEmits<{ close: [] }>()

const liveMatchesList = ref<any[]>([])
const isLoading = ref(true)
const error = ref('')

onMounted(async () => {
    try {
        liveMatchesList.value = (await api.getLiveMatchesForAdmin(props.tour.tour_id)).data
    } catch (e) {
        error.value = apiErrorMessage(e)
    } finally {
        isLoading.value = false
    }
})
</script>
