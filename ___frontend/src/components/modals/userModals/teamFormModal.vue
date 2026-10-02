<template>
    <div class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animate__animated animate__slideInDown animate__faster" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">{{ team ? `Edit ${team.team_name}` : 'New team' }}</h5>
                    <button type="button" class="btn-close" aria-label="Close" @click="emit('close')"></button>
                </div>
                <div class="modal-body">
                    <div v-if="formError" class="alert alert-danger border-0 small">{{ formError }}</div>

                    <form id="teamForm" class="row g-3" @submit.prevent="save">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input v-model.trim="form.team_name" type="text" class="form-control" id="teamName" placeholder="" required />
                                <label for="teamName">Team name</label>
                            </div>
                        </div>

                        <div v-if="tournament.type == 'cup'" class="col-6">
                            <div class="form-floating">
                                <select id="teamGroup" v-model="form.group_in" class="form-select text-uppercase" required>
                                    <option v-for="g in GROUPS" :key="g" :value="g">{{ g }}</option>
                                </select>
                                <label for="teamGroup">Group</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <label class="card h-100 px-3 d-flex flex-row align-items-center justify-content-between cursor-pointer">
                                <span>Team colour</span>
                                <input v-model="form.team_color" type="color" class="form-control form-control-color border-0">
                            </label>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input v-model="form.manager" type="text" class="form-control" id="teamManager" placeholder="" />
                                <label for="teamManager">Manager's name</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input v-model="form.address" type="text" class="form-control" id="teamAddress" placeholder="" />
                                <label for="teamAddress">Home ground / address (optional)</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea id="teamBrief" v-model="form.team_brief" style="height: 120px;" class="form-control" placeholder=""></textarea>
                                <label for="teamBrief">About the team (optional)</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <ImagePicker v-model="form.team_badge" :current="team?.team_badge" label="Badge" :size="60" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button form="teamForm" type="submit" :disabled="isSaving" class="btn btn-primary-theme w-100">
                        <span v-if="isSaving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        {{ isSaving ? 'Saving…' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
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
        emit('close')
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}
</script>
