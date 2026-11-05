<template>
    <BaseModal :title="match ? 'Edit match' : 'Add match'" @close="emit('close')">
        <div class="modal-body">
            <form id="matchForm" class="row g-3" @submit.prevent="save">
                <div class="col-sm-6">
                    <label class="w-100">Home team
                        <select v-model="form.homeTeam" class="form-select" :disabled="!!match" required>
                            <option value="" disabled>Select</option>
                            <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                        </select>
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="w-100">Away team
                        <select v-model="form.awayTeam" class="form-select" :disabled="!!match" required>
                            <option value="" disabled>Select</option>
                            <option v-for="t in awayTeamOptions" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                        </select>
                    </label>
                </div>
                <div class="col-12">
                    <label class="w-100">Venue
                        <input v-model.trim="form.venue" class="form-control" required>
                    </label>
                </div>
                <div class="col-12">
                    <label class="w-100">Kick-off
                        <input v-model="form.kick_off" type="datetime-local" class="form-control" required>
                    </label>
                </div>
                <div v-if="tournament.type == 'cup'" class="col-12">
                    <label class="w-100">Stage
                        <select v-model="form.match_stage" class="form-select" required :disabled="!!match?.result">
                            <option value="" disabled>Select</option>
                            <option v-for="s in userData.match_stages" :key="s" :value="s">{{ fx.stageLabel(s) }}</option>
                        </select>
                    </label>
                </div>
                <div v-if="formError" class="col-12 small text-danger" role="alert">{{ formError }}</div>
            </form>
        </div>
        <div class="modal-footer modal-actions">
            <button form="matchForm" type="submit" :disabled="isSaving" class="ui-btn ui-btn-primary" @click="addAnother = false">
                <span v-if="isSaving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                {{ isSaving ? 'Saving…' : match ? 'Update match' : 'Save match' }}
            </button>
            <button v-if="!match" form="matchForm" type="submit" :disabled="isSaving" class="ui-btn ui-btn-ghost" @click="addAnother = true">
                Save &amp; add another
            </button>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'

const props = defineProps<{
    tournament: any
    /** Match being edited; omit to add a new one. */
    match?: any
}>()

const emit = defineEmits<{ close: []; saved: [] }>()

const userData = useUserDataStore()
const isSaving = ref(false)
const formError = ref('')
const addAnother = ref(false)

const nextHour = () => {
    const d = new Date()
    d.setHours(d.getHours() + 1, 0, 0, 0)
    return fx.toDatetimeLocal(d)
}

const form = reactive({
    homeTeam: props.match?.home_team?.team_id ?? '',
    awayTeam: props.match?.away_team?.team_id ?? '',
    venue: props.match?.venue ?? '',
    kick_off: props.match ? fx.toDatetimeLocal(props.match.kick_off) : nextHour(),
    match_stage: props.match?.match_stage ?? '',
})

const awayTeamOptions = computed(() => userData.tournamentTeams.filter((t) => t.team_id !== form.homeTeam))

async function save() {
    formError.value = ''
    isSaving.value = true

    const payload = {
        tour_id: props.tournament.tour_id,
        homeTeam: form.homeTeam,
        awayTeam: form.awayTeam,
        venue: form.venue,
        kick_off: new Date(form.kick_off).toISOString(),
        match_stage: props.tournament.type == 'cup' ? form.match_stage : null,
    }

    try {
        if (props.match) await api.updateMatch(props.match.match_id, payload)
        else await api.createMatch(payload)
        fx.toast.success(props.match ? 'Match updated' : 'Match scheduled')
        emit('saved')
        if (addAnother.value) {
            // Keep venue and kick-off: fixtures are usually entered in batches at the same ground.
            Object.assign(form, { homeTeam: '', awayTeam: '', match_stage: '' })
        } else {
            emit('close')
        }
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}
</script>
