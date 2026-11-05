<template>
    <BaseModal :title="player ? 'Edit player' : 'Add player'" @close="emit('close')">
        <div class="modal-body">
            <form id="playerForm" :key="formKey" class="row g-3" @submit.prevent="save">
                <div class="col-sm-6">
                    <label class="w-100">First name
                        <input v-model.trim="form.first_name" class="form-control" required>
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="w-100">Last name
                        <input v-model.trim="form.last_name" class="form-control" required>
                    </label>
                </div>
                <div class="col-12">
                    <label class="w-100">Team
                        <select v-model="form.team_id" class="form-select" required>
                            <option value="" disabled>Select</option>
                            <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                        </select>
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="w-100">Date of birth
                        <input v-model="form.dob" type="date" class="form-control" :max="today">
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="w-100">Position / note
                        <input v-model="form.info" class="form-control" maxlength="50" placeholder="e.g. Goalkeeper">
                    </label>
                </div>
                <div class="col-12">
                    <ImagePicker v-model="form.image" :current="player?.image" label="Photo" :size="60" />
                </div>
                <div v-if="formError" class="col-12 small text-danger" role="alert">{{ formError }}</div>
            </form>
        </div>
        <div class="modal-footer modal-actions">
            <button form="playerForm" type="submit" class="ui-btn ui-btn-primary" :disabled="isSaving" @click="addAnother = false">
                {{ isSaving ? 'Saving…' : player ? 'Update player' : 'Add player' }}
            </button>
            <button v-if="!player" form="playerForm" type="submit" class="ui-btn ui-btn-ghost" :disabled="isSaving" @click="addAnother = true">
                Save &amp; add another
            </button>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import ImagePicker from '@/components/ImagePicker.vue'

const props = defineProps<{
    /** Player being edited; omit to add a new one. */
    player?: any
    /** Team to preselect when adding. */
    teamId?: string
}>()

const emit = defineEmits<{ close: []; saved: [] }>()

const userData = useUserDataStore()
const isSaving = ref(false)
const formError = ref('')
const addAnother = ref(false)
const formKey = ref(0) // remounts the form (and image preview) when adding another
const today = new Date().toISOString().slice(0, 10)

const form = reactive({
    first_name: props.player?.first_name ?? '',
    last_name: props.player?.last_name ?? '',
    team_id: props.player?.team_id ?? props.teamId ?? '',
    dob: props.player?.dob ?? '',
    info: props.player?.info ?? '',
    image: null as File | null,
})

async function save() {
    formError.value = ''
    isSaving.value = true

    try {
        if (props.player) await api.updatePlayer(props.player.player_id, { ...form })
        else await api.createPlayer({ ...form })
        fx.toast.success(props.player ? 'Player updated' : 'Player added')
        emit('saved')
        if (addAnother.value) {
            // Keep the team: squads are usually entered one team at a time.
            Object.assign(form, { first_name: '', last_name: '', dob: '', info: '', image: null })
            formKey.value++
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
