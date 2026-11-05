<template>
    <BaseModal :title="isEditing ? 'Update tournament' : 'New tournament'" @close="emit('close')">
        <div class="modal-body">
            <div v-if="formError" class="small text-danger mb-3" role="alert">{{ formError }}</div>

            <form id="tournamentForm" class="row g-3" @submit.prevent="saveTournament">
                <div class="col-md-8">
                    <label class="w-100">Name
                        <input type="text" class="form-control" v-model.trim="form.tour_title" required minlength="2" />
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="w-100">Type
                        <select v-model="form.tour_type" class="form-select" :disabled="isEditing && (editingData?.teams_count ?? 0) > 0">
                            <option value="cup">Cup</option>
                            <option value="league">League</option>
                        </select>
                    </label>
                </div>
                <div class="col-12 small">
                    <span v-html="tourTypeInformation"></span>
                    <div v-if="isEditing && (editingData?.teams_count ?? 0) > 0">
                        The format is locked because teams have been added.
                    </div>
                </div>
                <div class="col-12">
                    <label class="w-100">Description
                        <input type="text" class="form-control" v-model="form.tour_desc" />
                    </label>
                </div>
                <div class="col-12">
                    <ImagePicker v-model="form.tour_logo" :current="editingData?.tour_logo" label="Logo" />
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button form="tournamentForm" type="submit" :disabled="isSaving" class="ui-btn ui-btn-primary ui-btn-lg w-100">
                <span v-if="isSaving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span v-else>{{ isEditing ? 'Update' : 'Create' }}</span>
            </button>
        </div>
    </BaseModal>
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
