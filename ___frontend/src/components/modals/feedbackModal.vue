<template>
    <div>
        <div class="modal fade" id="feedbackModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-sm">
                <div class="modal-content">
                    <div class="modal-header border-0 bg-primary py-4">

                        <!-- <span class=" text-white">How far padi?</span> -->
                        <!-- <span class="float-end">
                            <button ref="btnX" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </span> -->
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center g-3">
                            <div class="small"> {{ greeting(prop.name) }}</div>
                            <!-- <div v-if="!name.length" class="small">You no make any predictions, but no wahala😒 </div> -->
                            <div class="fs-5 alert alert-light border-0 m-0 py-1">
                                <vue-writer
                                    :array="['How far na wetin you feel about this App? We just need feedback abeg. 😊']"
                                    :iterations='1' :typeSpeed="50" :start="500" />

                            </div>

                            <div class="col-12">
                                <textarea v-model="form.feedbackText" placeholder="oya type here..."
                                    class="form-control" rows="4"></textarea>
                            </div>

                            <div v-if="!name.length" class="col-12">
                                <input v-model="form.name" class=" form-control form-cont py-2" type="text"
                                    placeholder="Your name ( no be by force)">
                            </div>

                            <div class="col-12">
                                <button @click="sendFeedBack" v-if="!form.isSending"
                                    class="btn btn-lg btn-primary-theme w-100">Send am</button>
                                <button disabled v-else class="btn btn-lg btn-primary-theme w-100">Abeg wait..</button>
                            </div>

                            <div class="col-12">
                                <button ref="btnX" data-bs-dismiss="modal" class="btn btn-sm btn-link w-100">No be now,
                                    remind me later</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'

const prop = defineProps({
    tour_id: {
        type: String,
        required: true
    },
    name: {
        type: String,
        required: true
    }
})


const greeting = (name: string) => {
    var today = new Date()
    var curHr = today.getHours()
    let text = ''

    if (curHr < 12) text = `Good morning ${name},`
    else if (curHr < 18) text = `Good afternoon ${name},`
    else text = `Good evening ${name},`

    return text;
}

const emit = defineEmits(['done'])
const form = reactive({
    name: '',
    feedbackText: '',
    isSending: false,
    tour_id: ''
})

async function sendFeedBack() {
    if (!form.feedbackText) {
        alert('You no fit send empty message, Thank you')
        return;
    }
    form.name = prop.name ? prop.name : form.name
    form.tour_id = prop.tour_id
    form.isSending = true

    try {
        let resp = await api.sendFeedBack(form)
        console.log(resp);

        if (resp.status == 200) {
            emit('done')
        }

    } catch (error) {

    }
    finally {
        form.isSending = false
        btnX.value.click()
    }
}


const btnX: any = ref(null)
onBeforeRouteLeave(() => {
    btnX.value.click()
})
</script>