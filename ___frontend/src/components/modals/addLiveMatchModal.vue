<template>
    <div>
        <div class="modal fade" id="addLiveMatchModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-sm">
                <div class="modal-content">
                    <div class="modal-header border-0 bg-light">
                        <span class="fw-bold">New Live Match:</span>
                        <span class="float-end">
                            <button ref="btnX" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </span>
                    </div>
                    <div class="modal-body p-sm-4">
                        <div class="row justify-content-center gy-3">
                            <div class="col-md-12 ">
                                <form class="row g-3">
                                    <div class="col-md-12">
                                        <label>Home Team: </label>
                                        <select class="form-select" v-model="form.home_team">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.tournamentTeams" :key="i" :value="i.team_id">{{
                                                i.team_name
                                            }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Away Team: </label>
                                        <select class="form-select  " v-model="form.away_team">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in awayDropdown" :key="i" :value="i.team_id">{{
                                                i.team_name
                                            }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Match Stage: </label>
                                        <select class="form-select" v-model="form.match_stage">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.match_stages" :key="i" :value="i">{{
                                                i
                                            }}
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-md-12 mt-3">
                                        <button v-if="!isSaving" @click.prevent="start"
                                            class="btn btn-primary btn w-100">Start !</button>
                                        <button v-else class="float-end theme-btn btn w-100" disabled>Starting...</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';

watch(() => prop.tour, () => {
    form.tour_id = prop.tour.id
})

const prop = defineProps({
    tour: {
        type: Object,
        required: true
    }
})

const $toast = useToast();
const userData = useUserDataStore()
const isSaving = ref(false)

const form = reactive({
    home_team: '',
    away_team: '',
    match_stage: '',
    tour_id: prop.tour.id,
    creator: '',
})

const awayDropdown = computed(() => {
    return userData.tournamentTeams.filter((x: any) => x.team_id !== form.home_team)
})


async function start() {
    if (!form.home_team) {
        $toast.default('select home team', { position: 'top-right' });
        return;
    }

    if (!form.away_team) {
        $toast.default('select away team', { position: 'top-right' });
        return;
    }

    if (!form.match_stage) {
        $toast.default('select match stage', { position: 'top-right' });
        return;
    }

    isSaving.value = true

    try {
        let resp = await api.startLiveMatch(form)
        if (resp.status == 200) {
            $toast.default('Live match Started', { position: 'top-right' });
            isSaving.value = false
            form.home_team = "";
            form.away_team = "";
            form.match_stage = "";
            btnX.value.click()
            userData.getLiveMatchesByUser(form.tour_id)
        }
    } catch (error) {
        console.log(error);

        $toast.error('Error occoured', { position: 'top-right' });
        isSaving.value = false
    }
}

const btnX: any = ref(null)
onBeforeRouteLeave(() => {
    btnX.value.click()
})
</script>