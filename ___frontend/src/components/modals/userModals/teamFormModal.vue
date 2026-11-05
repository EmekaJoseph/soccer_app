<template>
    <BaseModal :title="team ? `Edit ${team.team_name}` : 'New team'" @close="emit('close')">
        <div class="modal-body">
            <div v-if="formError" class="small text-danger mb-3" role="alert">{{ formError }}</div>

            <form id="teamForm" class="row g-3" @submit.prevent="save">
                <div class="col-12">
                    <label class="w-100">Team name
                        <input v-model.trim="form.team_name" type="text" class="form-control" required />
                    </label>
                </div>
                <div v-if="tournament.type == 'cup'" class="col-6">
                    <label class="w-100">Group
                        <select v-model="form.group_in" class="form-select" required>
                            <option v-for="g in GROUPS" :key="g" :value="g">{{ g }}</option>
                        </select>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100">Team colour
                        <input v-model="form.team_color" type="color" class="form-control form-control-color w-100">
                    </label>
                </div>
                <div class="col-12">
                    <label class="w-100">Manager's name
                        <input v-model="form.manager" type="text" class="form-control" />
                    </label>
                </div>
                <div class="col-12">
                    <label class="w-100">Home ground / address (optional)
                        <input v-model="form.address" type="text" class="form-control" />
                    </label>
                </div>
                <div class="col-12">
                    <label class="w-100">About the team (optional)
                        <textarea v-model="form.team_brief" rows="3" class="form-control"></textarea>
                    </label>
                </div>
                <div class="col-12">
                    <ImagePicker :key="pickerKey" v-model="form.team_badge" :current="team?.team_badge" label="Badge" :size="60" />
                </div>
            </form>
        </div>
        <div class="modal-footer modal-actions">
            <button form="teamForm" type="submit" :disabled="isSaving" class="ui-btn ui-btn-primary" @click="addAnother = false">
                <span v-if="isSaving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                {{ isSaving ? 'Saving…' : 'Save team' }}
            </button>
            <button v-if="!team" form="teamForm" type="submit" :disabled="isSaving" class="ui-btn ui-btn-ghost" @click="addAnother = true">
                Save &amp; add another
            </button>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { GROUPS } from '@/store/userDataStore'
import ImagePicker from '@/components/ImagePicker.vue'

const props = defineProps<{
    tournament: any
    /** Team being edited; omit to create a new one. */
    team?: any
}>()

const emit = defineEmits<{ close: []; saved: [team: any] }>()

const isSaving = ref(false)
const formError = ref('')
const addAnother = ref(false)
const pickerKey = ref(0) // clears the badge preview when adding another

const form = reactive({
    team_name: props.team?.team_name ?? '',
    manager: props.team?.manager ?? '',
    address: props.team?.address ?? '',
    team_brief: props.team?.team_brief ?? '',
    team_color: props.team?.team_color ?? '#1f6f8b',
    group_in: props.team?.group_in ?? 'A',
    team_badge: null as File | null,
})

async function save() {
    formError.value = ''
    isSaving.value = true

    const fields = {
        ...form,
        tour_id: props.tournament.tour_id,
        group_in: props.tournament.type == 'cup' ? form.group_in : null,
    }

    try {
        const { data } = props.team
            ? await api.updateTeam(props.team.team_id, fields)
            : await api.createTeam(fields)
        fx.toast.success(props.team ? 'Team updated' : 'Team added')
        emit('saved', data)
        if (addAnother.value) {
            // Keep the group: teams are usually entered one group at a time.
            Object.assign(form, { team_name: '', manager: '', address: '', team_brief: '', team_badge: null })
            pickerKey.value++
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
