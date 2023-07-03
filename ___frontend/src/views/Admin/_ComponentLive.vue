<template>
    <div class="col-md-6 col-lg-4">
        <fieldset class="border rounded-3 p-3 bg-white h-100">
            <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">LIVE MATCH:
            </legend>
            <div class="row g-3">
                <!-- <div v-if="current_type == 'cup'" class="col-md-12"> -->
                <div class="col-md-12">
                    <div class="card shadow-sm small">
                        <div class="card-body">

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="border-1 card p-2 text-center "
                                        :class="{ 'bg-body-secondary': liveData.timeIsPaused }">
                                        <div class="card-header bg-transparent border-0 m-0 p-0 d-flex justify-content-end">
                                            <button @click="liveData.timeIsPaused = !liveData.timeIsPaused"
                                                class="btn  m-0 p-0 btn-sm px-2"
                                                :class="!liveData.timeIsPaused ? 'btn-dark' : 'btn-success'">
                                                <i v-if="liveData.timeIsPaused" class="bi bi-play"></i>
                                                <i v-else class="bi bi-pause"></i>
                                            </button>
                                        </div>
                                        <div class="fw-bolder" style="font-size: 3rem;">
                                            {{ liveData.curr_time }}
                                            <span v-if="!liveData.timeIsPaused" class="text-success">'</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control form-control-sm" readonly
                                        :value="teamData.home_team">
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control form-control-sm"
                                        v-model="liveData.home_team_score">
                                </div>

                                <div class="col-8">
                                    <input type="text" class="form-control form-control-sm" readonly
                                        :value="teamData.away_team">
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control form-control-sm"
                                        v-model="liveData.away_team_score">
                                </div>
                            </div>

                            <div class=" mt-3 shadow-sm border-0">
                                <div class="text-center mb-2"> Update Time:</div>
                                <div class="row justify-content-center g-2">
                                    <!-- <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" :value="curr_time">
                                        </div> -->
                                    <div class="col-6">
                                        <button @click="liveData.curr_time--"
                                            class="w-100 btn btn-outline-secondary bg-light-subtle btn-sm">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button @click="liveData.curr_time++"
                                            class="w-100 btn btn-outline-success bg-light-subtle btn-sm">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-6 mt-3">
                    <button @click.prevent="updateLive" class="btn btn-primary w-100">Update</button>
                </div>
                <div class="col-6 mt-3">
                    <button @click.prevent="endLive" class="btn btn-danger w-100">End</button>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script setup lang="ts">
import { reactive, onUnmounted } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';

const prop = defineProps({
    teamData: {
        type: Object,
        required: true
    }
})


const liveData = reactive({
    away_team_score: prop.teamData.away_team_score,
    home_team_score: prop.teamData.home_team_score,
    live_id: prop.teamData.live_id,
    curr_time: prop.teamData.curr_time,
    timeIsPaused: false
})


const $toast = useToast();

const userData = useUserDataStore()


// TABLE #####################################


async function updateLive() {
    if (confirm('Update Live Match ?')) {
        try {
            let resp = await api.updateLiveMatch(liveData)
            if (resp.status == 200) {
                userData.getLiveMatches(prop.teamData.tour_id)
                $toast.default('Data Updated succesfully', { position: 'top-right' });
            }
        } catch (error) {
            console.log(error);
            $toast.error('Could not update, Internet Error', { position: 'top-right' });
        }
    }
}

async function endLive() {
    if (confirm('End Live Match ?')) {
        try {
            let resp = await api.endLiveMatch(liveData.live_id)
            if (resp.status == 200) {
                userData.getLiveMatches(prop.teamData.tour_id)
                $toast.default('Match ended succesfully', { position: 'top-right' });
            }
        } catch (error) {
            console.log(error);
            $toast.error('Operation not successfull, Internet Error', { position: 'top-right' });
        }
    }
}


// ################################################ FORM

// load live results every 1min(60secs)
let liveMatchInterval = setInterval(() => {
    if (!liveData.timeIsPaused) {
        liveData.curr_time += 1
    }
}, 60000)

onUnmounted(() => {
    clearInterval(liveMatchInterval)
})


</script>
