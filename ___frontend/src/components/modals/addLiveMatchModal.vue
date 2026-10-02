<template>
    <div class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-scrollable animate__animated animate__slideInDown animate__faster">
            <div class="modal-content">
                <div class="modal-header border-0 bg-light">
                    <span class="fw-bold">New live match</span>
                    <button class="btn btn-close" aria-label="Close" @click="emit('close')"></button>
                </div>
                <div class="modal-body p-sm-4">
                    <componentLoadingSpinner v-if="isLoading" />
                    <form v-else class="row g-3" @submit.prevent="start">
                        <div class="col-12">
                            <label class="w-100">Select match:
                                <select v-model="selectedMatch" class="form-select text-uppercase" required>
                                    <option :value="null" disabled>
                                        {{ startable.length ? '-- select --' : 'No scheduled matches left' }}
                                    </option>
                                    <option v-for="m in startable" :key="m.match_id" :value="m">
                                        {{ m.home_team.team_name }} VS {{ m.away_team.team_name }}
                                        <template v-if="m.match_stage">({{ fx.stageLabel(m.match_stage) }})</template>
                                    </option>
                                </select>
                            </label>
                        </div>
                        <div v-if="error" class="col-12 small text-danger">{{ error }}</div>
                        <div class="col-md-12 mt-4">
                            <button type="submit" :disabled="!selectedMatch || isSaving" class="btn btn-primary-theme btn-lg w-100">
                                {{ isSaving ? 'Starting…' : 'Start now!' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'

const props = defineProps<{ tour: any }>()
const emit = defineEmits<{ close: []; started: [] }>()

const userData = useUserDataStore()
const selectedMatch = ref<any>(null)
const isLoading = ref(true)
const isSaving = ref(false)
const error = ref('')

onMounted(async () => {
    await userData.getTournamentMatches(props.tour.tour_id)
    isLoading.value = false
})

const startable = computed(() => userData.tournamentMatches.filter((m) => !m.result && !m.live && m.home_team && m.away_team))

async function start() {
    error.value = ''
    isSaving.value = true
    try {
        await api.startLiveMatch(selectedMatch.value.match_id)
        fx.toast.success('Live match started')
        emit('started')
        emit('close')
    } catch (e) {
        error.value = apiErrorMessage(e)
    } finally {
        isSaving.value = false
    }
}
</script>
