<template>
    <div class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog animate__animated animate__slideInDown animate__faster modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 px-lg-2">
                <div class="modal-header border-0">
                    <h5 class="modal-title">{{ isEditing ? 'UPDATE TOURNAMENT' : 'NEW TOURNAMENT' }}</h5>
                    <button @click="emit('close')" type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="formError" class="alert alert-danger border-0" role="alert">
                        <strong>{{ formError }}</strong>
                    </div>

                    <form id="tournamentForm" @submit.prevent="saveTournament" class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="tourTitle">Name:</label>
                            <input id="tourTitle" type="text" class="form-control" v-model.trim="form.tour_title"
                                placeholder="tournament name..." required minlength="2" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="tourType">Type:</label>
                            <select id="tourType" v-model="form.tour_type" class="form-select"
                                :disabled="isEditing && (editingData?.teams_count ?? 0) > 0">
                                <option value="cup">CUP</option>
                                <option value="league">LEAGUE</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <span v-html="tourTypeInformation" class="small text-muted"></span>
                            <div v-if="isEditing && (editingData?.teams_count ?? 0) > 0" class="small text-muted">
                                The format is locked because teams have been added.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="tourDesc">Description:</label>
                            <input id="tourDesc" type="text" class="form-control" v-model="form.tour_desc"
                                placeholder="brief description..." />
                        </div>

                        <div class="col-md-3">
                            <ImagePicker v-model="form.tour_logo" :current="editingData?.tour_logo" label="Logo" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button form="tournamentForm" type="submit" :disabled="isSaving"
                        class="btn btn-primary-theme float-end btn-lg" style="width: 200px;">
                        <span v-if="isSaving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span v-else>{{ isEditing ? 'Update' : 'Create' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import ImagePicker from '@/components/ImagePicker.vue'

const props = defineProps<{
    isEditing: boolean
    editingData?: any
}>()

const emit = defineEmits<{ close: []; done: [] }>()

const formError = ref('')
const isSaving = ref(false)

const form = reactive({
    tour_title: props.editingData?.tour_title ?? '',
    tour_type: props.editingData?.tour_type ?? 'cup',
    tour_desc: props.editingData?.tour_desc ?? '',
    tour_logo: null as File | null,
})

async function saveTournament() {
    formError.value = ''
    isSaving.value = true

    const fields = { ...form }

    try {
        if (props.isEditing) {
            await api.updateTournament(props.editingData.tour_id, fields)
        } else {
            await api.createTournament(fields)
        }
        fx.toast.success(props.isEditing ? 'Tournament updated' : 'Tournament created')
        emit('done')
        emit('close')
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}

const tourTypeInformation = computed(() =>
    form.tour_type == 'cup'
        ? `A <b>Cup</b> has group-stage tables followed by knock-out rounds. Losing teams in the
           knock-out rounds are eliminated; drawn knock-out games can go to penalties.`
        : `A <b>League</b> is a series of games played over a season. Every result counts
           towards a single table.`,
)
</script>
