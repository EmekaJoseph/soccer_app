<template>
    <div class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-sm animate__animated animate__slideInDown animate__faster">
            <div class="modal-content">
                <div class="modal-header border-0 bg-primary py-3 text-white">
                    <span class="fw-bold">Feedback</span>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close" @click="emit('close')"></button>
                </div>
                <form class="modal-body row justify-content-center g-3" @submit.prevent="sendFeedBack">
                    <div class="small">{{ fx.greet() }}{{ name ? `, ${name}` : '' }}!</div>
                    <div class="fs-6 alert alert-light border-0 m-0 py-1">
                        What do you think of this page? Tell the organisers 😊
                    </div>
                    <div class="col-12">
                        <textarea v-model.trim="form.feedbackText" placeholder="Type here…" class="form-control" rows="4" maxlength="2000" required></textarea>
                    </div>
                    <div v-if="!name" class="col-12">
                        <input v-model.trim="form.name" class="form-control py-2" type="text" placeholder="Your name (optional)" maxlength="255">
                    </div>
                    <div v-if="error" class="col-12 small text-danger">{{ error }}</div>
                    <div class="col-12">
                        <button type="submit" :disabled="isSending" class="btn btn-lg btn-primary-theme w-100">
                            {{ isSending ? 'Sending…' : 'Send' }}
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
import fx from '@/store/useFunctions'

const props = defineProps<{ tour_id: string; name: string }>()
const emit = defineEmits<{ close: []; done: [] }>()

const form = reactive({ name: '', feedbackText: '' })
const isSending = ref(false)
const error = ref('')

async function sendFeedBack() {
    error.value = ''
    isSending.value = true
    try {
        await api.sendFeedBack(props.tour_id, { name: props.name || form.name || null, feedbackText: form.feedbackText })
        fx.toast.success('Thanks for your feedback!')
        emit('done')
    } catch (e) {
        error.value = apiErrorMessage(e)
    } finally {
        isSending.value = false
    }
}
</script>
