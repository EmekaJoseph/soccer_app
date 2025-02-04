<template>
    <div>
        <div class="modal fade" id="addLiveMatchModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0 bg-light">
                        <span class="fw-bold">New Live Match:</span>
                        <button ref="btnX" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-sm-4">
                        <div class="row justify-content-center gy-3">
                            <div class="col-md-12 ">
                                <form class="row g-3">
                                    <div class="col-12">
                                        <label>Select Match:</label>
                                        <select v-model="selectedMatch" class="form-select  text-uppercase">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.tournamentMatches" :key="i" :value="i">
                                                {{ i.home_team.team_name + ' VS ' + i.away_team.team_name }} ({{
                                                    i.match_stage }})
                                            </option>
                                        </select>
                                    </div>



                                    <div class="col-md-12 mt-4">
                                        <button v-if="!isSaving" @click.prevent="start"
                                            class="btn btn-primary-theme btn-lg btn w-100">Start Now !</button>
                                        <button v-else class="float-end theme-btn btn btn-lg w-100"
                                            disabled>Starting...</button>
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
import { reactive, ref, watch, computed, onMounted } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';



const prop = defineProps({
    tour: {
        type: Object,
        required: true,
        default: {}
    }
})

const userData = useUserDataStore()
const selectedMatch = ref<any>('')


watch(() => prop.tour, () => {
    if (prop.tour?.id) userData.getTournamentMatches(prop.tour.id)
}, { deep: true })

const $toast = useToast();
const isSaving = ref(false)


async function start() {
    if (!selectedMatch.value.match_id) return;

    isSaving.value = true

    try {
        let resp = await api.startLiveMatch({ match_id: selectedMatch.value.match_id })
        if (resp.status == 200) {
            $toast.default('Live match Started', { position: 'top-right' });
            isSaving.value = false

            btnX.value.click()
            userData.getLiveMatchesByUser(prop.tour.id)
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