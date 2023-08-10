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
                        @change="loadResultsData">
                        <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}</option>
                    </select>
                </div>
                <div class="col-lg-12">
                    <div class="row gy-3">
                        <div class="col-lg-4">
                            <fieldset class="border rounded-3 p-3 bg-white h-100">
                                <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">NEW RESULT:
                                </legend>
                                <div class="row g-3">
                                    <div v-if="selectedTournament.type == 'cup'" class="col-md-12">
                                        <label>Match Type:</label>
                                        <select v-model="form.match_stage" class="form-select  text-">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.match_stages" :key="i" :value="i">{{ i }}
                                            </option>
                                        </select>
                                    </div>

                                    <div v-if="selectedTournament.type == 'cup' && form.match_stage == 'Group_Stage'"
                                        class="col-md-12">
                                        <label>Group: </label>
                                        <select v-model="form.group_in" class="form-select  text-uppercase">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.valid_groups" :key="i" :value="i">{{ i }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-9">
                                                        <label>Home Team: </label>
                                                        <select v-model="form.homeTeam" class="form-select  text-uppercase">
                                                            <option value="" selected disabled></option>
                                                            <option v-for="i in homeTeamDrop" :key="i.team_id"
                                                                :value="i.team_id">{{
                                                                    i.team_name }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label class="small">score:</label>
                                                        <input v-model="form.homeTeam_score" type="number"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-9">
                                                        <label>Away Team: </label>
                                                        <select v-model="form.awayTeam" class="form-select  text-uppercase">
                                                            <option value="" selected disabled></option>
                                                            <option v-for="i in awayTeamDrop" :key="i.team_id"
                                                                :value="i.team_id">{{
                                                                    i.team_name }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label class="small">score:</label>
                                                        <input v-model="form.awayTeam_score" type="number"
                                                            class="form-control ">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <label>Date Played:</label>
                                        <VueDatePicker :format="format" :maxDate="today" v-model="form.date_played"
                                            hideInputIcon :enableTimePicker="false" :is-24="false" :clearable="false"
                                            placeholder="date.." autoApply>
                                        </VueDatePicker>
                                    </div>


                                    <div v-if="userData.tournamentTeams.length" class="col-md-12 mt-3">
                                        <button v-if="!form.isSaving" @click.prevent="save"
                                            class="btn btn-primary btn w-100">Save</button>
                                        <button v-else class="float-end theme-btn btn w-100" disabled>Saving...</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-lg-8">
                            <fieldset class="border rounded-3 p-3 bg-white h-100">
                                <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">LIST</legend>

                                <div class="col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-body p-1 m-1">
                                            <div v-if="userData.tournamentResults">
                                                <EasyDataTable class="border-0" :headers="tableHeaders"
                                                    :items="userData.tournamentResults" show-index>

                                                    <template #item-homeTeamAndScore="item">
                                                        {{ item.home_name }},
                                                        <span class="fw-bold">{{ item.home_score }}</span>
                                                    </template>

                                                    <template #item-awayTeamAndScore="item">
                                                        {{ item.away_name }},
                                                        <span class="fw-bold">{{ item.away_score }}</span>
                                                    </template>

                                                    <template #item-played="item">
                                                        <div class=" fw-bolder"> {{ (new
                                                            Date(item.date_played)).toDateString()
                                                        }} </div>
                                                    </template>

                                                    <template #item-delete="item">
                                                        <div class="operation-wrapper">

                                                            <span @click="undoResult(item)"
                                                                class="operation-icon cursor-pointer">
                                                                <button
                                                                    class="btn btn-sm btn-outline-danger m-0 py-0">undo</button>
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

const userData = useUserDataStore()
const selectedTournament = ref<any>({})

const $toast = useToast();
const today = new Date();
const format = (date: Date) => `${date.toDateString()}`;

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadResultsData();
    }
})

function loadResultsData() {
    userData.getTournamentTeams(selectedTournament.value.id)
    userData.getTournamentResults(selectedTournament.value.id)
}

// TABLE #####################################
const tableHeaders: Header[] = [
    { text: "", value: "match_stage" },
    { text: "", value: "homeTeamAndScore" },
    { text: "", value: "awayTeamAndScore" },
    { text: "", value: "played" },
    { text: "", value: "delete" },
];

async function undoResult(result: any) {
    if (confirm('Undo this a result? this will undo updates.')) {

        let obj = {
            homeTeam: result.home_team,
            awayTeam: result.away_team,
            homeTeam_score: result.home_score,
            awayTeam_score: result.away_score,
            result_id: result.result_id,
            match_stage: result.match_stage
        }

        try {
            let resp: any;
            if (selectedTournament.value.type == 'cup') resp = await api.undoSaveCupResult(obj)
            else resp = await api.undoSaveLeagueResult(obj)

            if (resp.status == 200) {
                userData.getTournamentResults(selectedTournament.value.id)
                $toast.default('Deleted successfuly', { position: 'top-right' });
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
    homeTeam_score: 0,
    awayTeam_score: 0,
    match_stage: '',
    group_in: '',
    date_played: new Date(),
})


const homeTeamDrop = computed(() => {
    return (form.match_stage == 'Group_Stage') ?
        userData.tournamentTeams.filter((x: { group_in: any; }) => x.group_in == form.group_in) :
        userData.tournamentTeams
})

const awayTeamDrop = computed(() => {
    return (form.match_stage == 'Group_Stage') ?
        userData.tournamentTeams.filter((x: { team_id: any; group_in: any; }) => x.team_id !== form.homeTeam && x.group_in === form.group_in) :
        userData.tournamentTeams.filter((x: { team_id: any; group_in: any; }) => x.team_id !== form.homeTeam)

})

async function save() {
    if (selectedTournament.value.type == 'cup') {
        if (!form.match_stage) {
            $toast.error('Please select match type', { position: 'top-right' });
            return;
        }

        if (form.match_stage == 'Group_Stage') {
            if (!form.group_in) {
                $toast.error('Please select Group', { position: 'top-right' });
                return;
            }
        }
    }

    if (!form.homeTeam) {
        $toast.error('Select Home Team', { position: 'top-right' });
        return;
    }

    if (!form.awayTeam) {
        $toast.error('Select Away Team', { position: 'top-right' });
        return;
    }

    if (isNaN(parseInt(form.homeTeam_score))) {
        $toast.error('Invalid home team score', { position: 'top-right' });
        return;
    }

    if (isNaN(parseInt(form.awayTeam_score))) {
        $toast.error('Invalid away team score', { position: 'top-right' });
        return;
    }

    if (selectedTournament.value.type == 'cup') {
        if (!form.match_stage) {
            $toast.error('Please select match stage', { position: 'top-right' });
            return;
        }
    }

    let obj: any = {};
    obj.awayTeam = form.awayTeam;
    obj.homeTeam = form.homeTeam;
    obj.awayTeam_score = form.awayTeam_score;
    obj.homeTeam_score = form.homeTeam_score;
    obj.date_played = (form.date_played).toISOString();
    obj.tour_id = selectedTournament.value.id;
    obj.match_stage = selectedTournament.value.type == 'cup' ? form.match_stage : null
    obj.group_in = selectedTournament.value.type == 'cup' ? form.group_in : null

    form.isSaving = true

    if (confirm('Are you sure you want to update this result?')) {
        try {
            let resp: any;
            if (selectedTournament.value.type == 'cup') resp = await api.saveCupResult(obj)
            else resp = await api.saveLeagueResult(obj)

            $toast.default('Result updated', { position: 'top-right' });
            userData.getTournamentResults(selectedTournament.value.id)

            form.isSaving = false
            form.awayTeam = "";
            form.homeTeam = "";
            form.awayTeam_score = 0;
            form.homeTeam_score = 0
            form.match_stage = "";
            form.group_in = "";

        } catch (error) {
            $toast.error('Network Error', { position: 'top-right' });
            form.isSaving = false
        }
    }
}

</script>

<style scoped></style>