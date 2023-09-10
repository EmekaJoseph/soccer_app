<template>
    <div class="container px-3">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row gy-4">
                <div class="col-lg-4 mb-3">
                    <!-- <label>Tournament: </label> -->
                    <select v-model="selectedTournament"
                        class="form-select text-uppercase rounded-0 border-end-0 border-start-0 border-top-0  border-bottom-3 cursor-pointer"
                        @change="loadScheduleData">
                        <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}</option>
                    </select>
                </div>
                <div class="col-lg-12">
                    <div class="row gy-3">
                        <div class="col-lg-4">
                            <fieldset class="border rounded-3 p-3 bg-white shadow">
                                <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">NEW SCHEDULE:
                                </legend>
                                <div class="row g-3">
                                    <!-- <div v-if="current_type == 'cup'" class="col-md-12"> -->
                                    <div class="col-md-12">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    <div class="col-md-">
                                                        <label>Home Team: </label>
                                                        <select v-model="form.homeTeam" class="form-select  text-uppercase">
                                                            <option value="" selected disabled></option>
                                                            <option v-for="i in userData.tournamentTeams" :key="i.team_id"
                                                                :value="i.team_id">{{
                                                                    i.team_name }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class=" text-center fw-bold mt-3 text-secondary">VS</div>

                                                    <div class="col-md-">
                                                        <label>Away Team: </label>
                                                        <select v-model="form.awayTeam" class="form-select  text-uppercase">
                                                            <option value="" selected disabled></option>
                                                            <option v-for="i in awayTeamDrop" :key="i.team_id"
                                                                :value="i.team_id">{{
                                                                    i.team_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Venue:</label>
                                        <textarea v-model="form.venue" rows="2" class="form-control"></textarea>
                                    </div>



                                    <div class="col-md-12">
                                        <label>Kick-Off:</label>
                                        <VueDatePicker :preview-format="format" :minDate="today" v-model="form.kick_off"
                                            hideInputIcon :enableTimePicker="true" :is-24="false" :clearable="false"
                                            placeholder="date.." autoApply>
                                        </VueDatePicker>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Match Type:</label>
                                        <select :disabled="selectedTournament.type != 'cup'" v-model="form.match_stage"
                                            class="form-select  text-">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.match_stages" :key="i" :value="i">{{ i }}
                                            </option>
                                        </select>
                                    </div>


                                    <div v-if="userData.tournamentTeams.length" class="col-md-12 mt-4">
                                        <button v-if="!form.isSaving" @click.prevent="save"
                                            class="btn btn-primary btn w-100">Save</button>
                                        <button v-else class="float-end theme-btn btn w-100" disabled>Saving...</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-lg-8">
                            <fieldset class="border rounded-3 p-3 bg-light-subtle h-100">
                                <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">LIST</legend>

                                <div class="col-md-12 mt-3">
                                    <div class="card border-0 p-0">
                                        <div class="card-body p-1 m-1">
                                            <div v-if="userData.tournamentShedules">
                                                <EasyDataTable class="border-0" :headers="tableHeaders"
                                                    :items="userData.tournamentShedules">

                                                    <template #item-homeVsAway="item">
                                                        <div>
                                                            {{ item.home_team }}
                                                            <span class=" fw-bolder"> VS </span>
                                                            {{ item.away_team }}
                                                        </div>
                                                    </template>

                                                    <template #item-kick_="item">
                                                        <div class=" fw-bolder"> {{ (new Date(item.kick_off)).toDateString()
                                                        }} </div>
                                                    </template>



                                                    <template #item-delete="item">
                                                        <div class="operation-wrapper">
                                                            <span @click="deleteSchedule(item)"
                                                                class="operation-icon cursor-pointer">
                                                                <i class="bi bi-trash3 text-danger"></i>
                                                            </span>
                                                        </div>
                                                    </template>
                                                </EasyDataTable>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header, Item, SortType } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';


const $toast = useToast();
const today = new Date();
const format = (date: Date) => `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;


const userData = useUserDataStore()
const selectedTournament = ref<any>({})

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadScheduleData()
    }

})

function loadScheduleData() {
    userData.getTournamentTeams(selectedTournament.value.id)
    loadTournamentSchedules()
}


function loadTournamentSchedules() {
    userData.getTournamentSchedules(selectedTournament.value.id)
}

// TABLE #####################################
const tableHeaders: Header[] = [
    { text: "", value: "homeVsAway" },
    { text: "", value: "match_stage" },
    { text: "", value: "kick_" },
    { text: "", value: "delete" },
];

async function deleteSchedule(schedule: any) {
    if (confirm('Delete this scheduled match ?')) {
        try {
            let resp = await api.deleteSchedule(schedule.schedule_id)
            if (resp.status == 200) {
                loadTournamentSchedules()
                $toast.default('Match deleted successfuly', { position: 'top-right' });
            }
        } catch (error) {
            console.log(error);
            $toast.error('Could not delete, Internet Error', { position: 'top-right' });
        }
    }
}


// ################################################ FORM
const form: any = reactive({
    isSaving: false,
    homeTeam: '',
    awayTeam: '',
    kick_off: new Date(),
    venue: '',
    match_stage: '',
})

const awayTeamDrop = computed(() => {
    let data = userData.tournamentTeams.filter((x: { team_id: any; }) => x.team_id !== form.homeTeam)
    return data;
})

async function save() {

    if (!form.homeTeam) {
        $toast.error('Select Home Team', { position: 'top-right' });
        return;
    }

    if (!form.awayTeam) {
        $toast.error('Select Away Team', { position: 'top-right' });
        return;
    }

    if (!form.venue) {
        $toast.error('Venue cannnot be empty', { position: 'top-right' });
        return;
    }

    if (selectedTournament.value.type == 'cup') {
        if (!form.match_stage) {
            $toast.error('Please select match stage', { position: 'top-right' });
            return;
        }
    }

    let obj: any = {};
    obj.homeTeam = form.homeTeam;
    obj.awayTeam = form.awayTeam;
    obj.venue = form.venue;
    obj.kick_off = (form.kick_off).toISOString();
    obj.tour_id = selectedTournament.value.id;
    obj.match_stage = selectedTournament.value.type == 'cup' ? form.match_stage : null

    form.isSaving = true

    try {
        let resp = await api.createSchedule(obj)
        if (resp.status == 203) {
            $toast.error('Duplicate entry', { position: 'top-right' });
            form.isSaving = false
            return;
        }
        $toast.default('New match added', { position: 'top-right' });
        userData.getTournamentSchedules(selectedTournament.value.id)
        form.isSaving = false

        form.awayTeam = "";
        form.homeTeam = "";
        form.match_stage = "";
        form.venue = "";
    } catch (error) {
        $toast.error('Network Error', { position: 'top-right' });
        form.isSaving = false
    }
}
</script>
