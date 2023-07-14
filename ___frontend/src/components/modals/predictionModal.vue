<template>
    <div>


        <!-- Button trigger modal -->
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
      Launch static backdrop modal
    </button> -->

        <!-- Modal -->
        <div class="modal fade" id="predictionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-light-subtle">
                        <h1 class="modal-title fs-5 fw-bolder">PREDICT</h1>
                        <button ref="btnX" type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning text-center border-0 py-3">
                            <i class="bi bi-exclamation-circle"></i> You can only predict ONCE.
                        </div>
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="row gy-3">
                                            <div class="col-12">
                                                <label>Cup winner:</label>
                                                <select v-model="form.first_place" class="form-select">
                                                    <option v-for="val in prop.teams" :value="val.team_id">{{ val.team_name
                                                    }}</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label>2nd Place:</label>
                                                <select v-model="form.second_place" class="form-select">
                                                    <option v-for="val in second_place_dropdown" :value="val.team_id">{{
                                                        val.team_name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label>3rd Place:</label>
                                                <select v-model="form.third_place" class="form-select">
                                                    <option v-for="val in third_place_dropdown" :value="val.team_id">{{
                                                        val.team_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label>Your Name:</label>
                                <input v-model="form.full_name" type="text" class="form-control ">
                            </div>
                            <div class="col-12">
                                <label>Phone Number:</label>
                                <input @input="replace" v-model="form.phone_number" type="number" class="form-control">
                            </div>
                        </div>
                        <div v-if="form.inComplete" class="text-center text-danger mt-2">Please complete the form!</div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not now</button>
                        <button v-if="!form.isLoading" style="width: 120px;" @click="submit" type="button"
                            class="btn btn-primary">Submit
                        </button>
                        <button v-else style="width: 120px;" class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="visually-hidden">Loading...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup lang="ts">
import { ref, computed, reactive } from 'vue';
import api from '@/store/axiosManager'
import { onBeforeRouteLeave } from 'vue-router';
const prop = defineProps({
    teams: {
        type: Object,
        required: true
    },
    tour_id: {
        type: String,
        required: true
    }
})

const emit = defineEmits(['done'])


const form = reactive({
    first_place: '',
    second_place: '',
    third_place: '',
    full_name: '',
    phone_number: '',
    email: 'default',
    isLoading: false,
    inComplete: false,
    tour_id: prop.tour_id
})

const second_place_dropdown = computed(() => {
    return prop.teams.filter((x: any) => x.team_id !== form.first_place)
})

const third_place_dropdown = computed(() => {
    return prop.teams.filter((x: any) => (x.team_id !== form.first_place) && (x.team_id !== form.second_place))
})

function replace(e: any) {
    let val = e.target.value
    form.phone_number = val.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');

}

async function submit() {
    form.inComplete = false;
    if (!form.first_place ||
        !form.second_place ||
        !form.third_place ||
        !form.full_name ||
        !form.phone_number) {
        form.inComplete = true;
        return;
    }

    form.isLoading = true


    try {
        let resp = await api.savePrediction(form)
        if (resp.status == 200) {
            emit('done', form.full_name)
            form.isLoading = false
            btnX.value.click()
        }
    } catch (error) {
        btnX.value.click()
        form.isLoading = false
        console.log(error);
    }
}


const btnX: any = ref(null)
onBeforeRouteLeave(() => {
    btnX.value.click()
})
</script>