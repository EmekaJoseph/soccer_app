<template>
    <BaseModal title="Feedback" @close="emit('close')">
        <div class="modal-body">
            <form class="row g-3" @submit.prevent="sendFeedBack">
                <div class="col-12">
                    <div class="small mb-1">{{ fx.greet() }}{{ name ? `, ${name}` : '' }}!</div>
                    <p class="m-0">What do you think of this page? Tell the organisers.</p>
                </div>
                <div class="col-12">
                    <textarea v-model.trim="form.feedbackText" placeholder="Type here…" aria-label="Your feedback" class="form-control" rows="4" maxlength="2000" required></textarea>
                </div>
                <div v-if="!name" class="col-12">
                    <input v-model.trim="form.name" class="form-control py-2" type="text" placeholder="Your name (optional)" aria-label="Your name (optional)" maxlength="255">
                </div>
                <div v-if="error" class="col-12 small text-danger">{{ error }}</div>
                <div class="col-12">
                    <button type="submit" :disabled="isSending" class="ui-btn ui-btn-primary ui-btn-lg w-100">
                        {{ isSending ? 'Sending…' : 'Send' }}
                    </button>
                </div>
            </form>
        </div>
    </BaseModal>
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
