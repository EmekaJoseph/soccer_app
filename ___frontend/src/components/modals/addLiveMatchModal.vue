<template>
    <BaseModal title="New live match" @close="emit('close')">
        <div class="modal-body">
            <componentLoadingSpinner v-if="isLoading" />
            <form v-else class="row g-3" @submit.prevent="start">
                <div class="col-12">
                    <label class="w-100">Select match
                        <select v-model="selectedMatch" class="form-select" required>
                            <option :value="null" disabled>
                                {{ startable.length ? 'Select' : 'No scheduled matches left' }}
                            </option>
                            <option v-for="m in startable" :key="m.match_id" :value="m">
                                {{ m.home_team.team_name }} v {{ m.away_team.team_name }}
                                <template v-if="m.match_stage">({{ fx.stageLabel(m.match_stage) }})</template>
                            </option>
                        </select>
                    </label>
                </div>
                <div v-if="error" class="col-12 small text-danger">{{ error }}</div>
                <div class="col-12">
                    <button type="submit" :disabled="!selectedMatch || isSaving" class="ui-btn ui-btn-primary ui-btn-lg w-100">
                        {{ isSaving ? 'Starting…' : 'Start now' }}
                    </button>
                </div>
            </form>
        </div>
    </BaseModal>
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
