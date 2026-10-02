<template>
    <div class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animate__animated animate__slideInDown animate__faster">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bolder"><i class="bi bi-trophy"></i> PREDICT THE TOP 3</h1>
                    <button type="button" class="btn-close" aria-label="Close" @click="emit('close')"></button>
                </div>
                <form @submit.prevent="submit">
                    <div class="modal-body p-4">
                        <div class="alert alert-warning text-center border-0 py-2 small">
                            <i class="bi bi-exclamation-circle"></i> One prediction per phone number.
                        </div>
                        <div class="row gy-3">
                            <div v-for="(slot, i) in slots" :key="slot.key" class="col-12">
                                <label class="w-100">{{ slot.label }}:
                                    <select v-model="form[slot.key]" class="form-select" required>
                                        <option value="" disabled>-- choose a team --</option>
                                        <option v-for="team in optionsFor(i)" :key="team.team_id" :value="team.team_id">{{ team.team_name }}</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="w-100">Your name:
                                    <input v-model.trim="form.full_name" type="text" class="form-control" maxlength="100" required>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="w-100">Phone number:
                                    <input v-model.trim="form.phone_number" type="tel" inputmode="tel" class="form-control"
                                        pattern="\+?[0-9 ]{7,20}" required>
                                </label>
                                <div class="small text-muted">Used to contact you if you win.</div>
                            </div>
                        </div>
                        <div v-if="error" class="text-center text-danger mt-3 small">{{ error }}</div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-soft" @click="emit('close')">Not now</button>
                        <button type="submit" style="width: 120px;" class="btn btn-primary-theme" :disabled="isLoading">
                            <span v-if="isLoading" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span v-else>Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import api, { apiErrorMessage } from '@/store/axiosManager'

const props = defineProps<{ teams: any[]; tour_id: string }>()
const emit = defineEmits<{ close: []; done: [name: string] }>()

const slots = [
    { key: 'first_place', label: 'Winner' },
    { key: 'second_place', label: '2nd place' },
    { key: 'third_place', label: '3rd place' },
] as const

const form = reactive({ first_place: '', second_place: '', third_place: '', full_name: '', phone_number: '' })
const isLoading = ref(false)
const error = ref('')

/** Each position can only pick teams not already chosen for a higher one. */
function optionsFor(position: number) {
    const taken = slots.slice(0, position).map((s) => form[s.key])
    return props.teams.filter((t) => !taken.includes(t.team_id))
}

async function submit() {
    error.value = ''
    isLoading.value = true
    try {
        await api.savePrediction(props.tour_id, form)
        emit('done', form.full_name)
    } catch (e) {
        error.value = apiErrorMessage(e)
    } finally {
        isLoading.value = false
    }
}
</script>
