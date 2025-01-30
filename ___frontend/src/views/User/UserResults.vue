<template>
    <div class="container">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row gy-4">

                <tourDropdownSelect @change="loadResultsData" v-model="selectedTournament" />

                <div class="col-lg-12">
                    <div class="row gy-3">
                        <div class="col-lg-5">
                            <div class="card shadow-s border-0">
                                <div class="card-header text-muted  bg-transparent border-0">
                                    NEW RESULT:
                                </div>

                                <div class="card-body">
                                    <!-- <fieldset class="border rounded-3 p-3 bg-light-subtle  h-100"> -->
                                    <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">NEW
                                            RESULT:
                                        </legend> -->
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label>Match:</label>
                                            <select @change="updateFormWithSelectedMatch" v-model="selectedMatch"
                                                class="form-select  text-uppercase">
                                                <option value="" selected disabled></option>
                                                <option v-for="i in userData.tournamentMatches" :key="i" :value="i">
                                                    {{ i.home_team.team_name + ' VS ' + i.away_team.team_name }} ({{
                                                        i.match_stage }})
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <!-- <div class="card shadow-sm border-0"> -->
                                            <!-- <div class="card-body"> -->
                                            <div class="row">
                                                <div class="col-9">
                                                    <label>Home Team: </label>
                                                    <input type="text" :value="form.homeTeam" class="form-control"
                                                        disabled>
                                                </div>

                                                <div class="col-3">
                                                    <label class="small">score:</label>
                                                    <input type="text" class="form-control" v-maska data-maska="##"
                                                        v-model="form.homeTeam_score">
                                                </div>
                                                <!-- </div> -->
                                                <!-- </div> -->
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <!-- <div class="card shadow-sm border-0"> -->
                                            <!-- <div class="card-body "> -->
                                            <div class="row">
                                                <div class="col-9">
                                                    <label>Away Team: </label>
                                                    <input type="text" :value="form.awayTeam" class="form-control"
                                                        disabled>
                                                </div>

                                                <div class="col-3">
                                                    <label class="small">score:</label>
                                                    <input class="form-control" type="text" v-maska data-maska="##"
                                                        v-model="form.awayTeam_score">
                                                </div>

                                                <!-- </div> -->
                                                <!-- </div> -->
                                            </div>
                                        </div>

                                        <div class="col-md-12"
                                            v-show="selectedMatch.match_stage && selectedMatch.match_stage != 'Group_Stage'">
                                            <div @click="form.isPenalties = !form.isPenalties" class="cursor-pointer">
                                                <i v-if="!form.isPenalties" class="bi bi-square"></i>
                                                <i v-else class="bi bi-check-square-fill text-secondary"></i>
                                                Penalty Shoot-Out?
                                            </div>
                                            <div v-show="form.isPenalties" class="card">
                                                <div class="card-body small">
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <label class="small">{{ form.homeTeam }}:</label>
                                                            <input class="form-control" type="text" v-maska
                                                                data-maska="##" v-model="form.home_score_pen">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="small">{{ form.awayTeam }}:</label>
                                                            <input class="form-control" type="text" v-maska
                                                                data-maska="##" v-model="form.away_score_pen">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 mt-3">
                                            <button :disabled="!selectedMatch" v-if="!form.isSaving"
                                                @click.prevent="save"
                                                class="btn btn-primary-theme btn w-100">Save</button>
                                            <button v-else class="float-end theme-btn btn w-100"
                                                disabled>Saving...</button>
                                        </div>
                                    </div>
                                    <!-- </fieldset> -->
                                </div>
                            </div>


                        </div>

                        <div class="col-lg-7">
                            <div class="card card-fixed-height border-0 h-100">
                                <div class="card-header text-muted  bg-transparent border-0">
                                    RESULTS LIST:
                                </div>

                                <div class="card-body">
                                    <!-- <fieldset class="border rounded-3 p-3 bg-light-subtle h-100"> -->
                                    <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">
                                            LIST
                                        </legend> -->

                                    <div class="col-md-12 mt-3">
                                        <div class="card border-0">
                                            <componentLoadingSpinner v-if="dataIsLoading" />
                                            <div v-else class="card-body p-1 m-1">
                                                <div v-if="userData.tournamentResults">
                                                    <EasyDataTable class="border-0 text-nowrap" :headers="tableHeaders"
                                                        :items="userData.tournamentResults">

                                                        <template #item-results="item">
                                                            {{ item.home_name }}
                                                            <span class="fw-bold">
                                                                {{ item.home_score }}
                                                            </span>,
                                                            &nbsp;
                                                            {{ item.away_name }}
                                                            <span class="fw-bold">
                                                                {{ item.away_score }}
                                                            </span>
                                                        </template>

                                                        <template #item-played="item">
                                                            <div class=" fw-bolder">
                                                                {{ (new Date(item.date_played)).toDateString() }}
                                                            </div>
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
                                    <!-- </fieldset> -->
                                </div>
                            </div>


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
import { vMaska } from "maska"

const userData = useUserDataStore()
const selectedTournament = ref<any>({})
const selectedMatch = ref<any>('')
const dataIsLoading = ref<any>(true)

const $toast = useToast();

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadResultsData();
        userData.getTournamentMatches(selectedTournament.value.id)
    }
})

async function loadResultsData() {
    await userData.getTournamentResults(selectedTournament.value.id)
    dataIsLoading.value = false
}

function loadTournamentMatches() {
    userData.getTournamentMatches(selectedTournament.value.id)

}

// TABLE #####################################
const tableHeaders: Header[] = [
    { text: "Results", value: "results" },
    { text: "Stage", value: "match_stage" },
    { text: "Date", value: "played" },
    { text: "", value: "delete" },
];

async function undoResult(result: any) {
    if (confirm('Undo this a result? this will undo updates.')) {

        let obj = {
            result_id: result.result_id,
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
const form = reactive({
    isSaving: false,
    awayTeam: '',
    homeTeam: '',
    homeTeam_score: 0,
    awayTeam_score: 0,
    isPenalties: false,
    home_score_pen: 0,
    away_score_pen: 0,
})

function updateFormWithSelectedMatch() {
    if (selectedMatch.value) {
        form.homeTeam = selectedMatch.value.home_team.team_name
        form.awayTeam = selectedMatch.value.away_team.team_name
    }
}


async function save() {

    let obj: any = {};
    obj.awayTeam_score = form.awayTeam_score;
    obj.homeTeam_score = form.homeTeam_score;
    obj.home_score_pen = form.isPenalties ? form.home_score_pen : null;
    obj.away_score_pen = form.isPenalties ? form.away_score_pen : null;
    obj.match_id = selectedMatch.value.match_id;
    obj.match_stage = selectedMatch.value.match_stage;


    form.isSaving = true

    if (confirm('Are you sure you want to update this result?')) {
        try {
            let resp: any;
            if (selectedTournament.value.type == 'cup') resp = await api.saveCupResult(obj)
            else resp = await api.saveLeagueResult(obj)

            $toast.default('Result updated', { position: 'top-right' });
            userData.getTournamentResults(selectedTournament.value.id)

            form.isSaving = false
            form.awayTeam_score = 0;
            form.homeTeam_score = 0
            form.isPenalties = false
            form.away_score_pen = 0
            form.home_score_pen = 0
            form.homeTeam = ''
            form.awayTeam = ''
            selectedMatch.value = ''

        } catch (error) {
            $toast.error('Network Error', { position: 'top-right' });
            form.isSaving = false
        }
    }
}

</script>

<style scoped></style>