<template>
    <div class="container px-3">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row gy-4">

                <tourDropdownSelect @change="loadMatchData" v-model="selectedTournament" />

                <div class="col-lg-12">
                    <div class="row gy-3">
                        <div class="col-lg-5">
                            <div class="card border-0 h-100">
                                <div class="card-header text-muted  bg-transparent border-0">
                                    ADD MATCH:
                                </div>
                                <div class="card-body">
                                    <!-- <fieldset class="border rounded-3 p-3 bg-white shadow"> -->
                                    <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">NEW
                                            SCHEDULE:
                                        </legend> -->
                                    <div class="row g-3">
                                        <!-- <div v-if="current_type == 'cup'" class="col-md-12"> -->

                                        <div class="col-md-">
                                            <label>Home Team: </label>
                                            <select v-model="form.homeTeam" class="form-select  ">
                                                <option value="" selected disabled>--select--</option>
                                                <option class="text-uppercase" v-for="i in userData.tournamentTeams"
                                                    :key="i.team_id" :value="i.team_id">{{
                                                        i.team_name }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- <div class=" text-center fw-bold mt-3 text-secondary">VS
                                        </div> -->

                                        <div class="col-md-">
                                            <label>Away Team: </label>
                                            <select v-model="form.awayTeam" class="form-select  ">
                                                <option value="" selected disabled>--select--</option>
                                                <option class="text-uppercase" v-for="i in awayTeamDrop"
                                                    :key="i.team_id" :value="i.team_id">{{
                                                        i.team_name }}
                                                </option>
                                            </select>
                                        </div>



                                        <div class="col-md-12">
                                            <label>Venue:</label>
                                            <textarea v-model="form.venue" rows="2" class="form-control"></textarea>
                                        </div>



                                        <div class="col-md-12">
                                            <label>Kick-Off:</label>
                                            <VueDatePicker :preview-format="format" :minDate="today"
                                                v-model="form.kick_off" hideInputIcon :enableTimePicker="true"
                                                :is-24="false" :clearable="false" placeholder="date.." autoApply>
                                            </VueDatePicker>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Match Type:</label>
                                            <select :disabled="selectedTournament.type != 'cup'"
                                                v-model="form.match_stage" class="form-select  text-">
                                                <option value="" selected disabled></option>
                                                <option v-for="i in userData.match_stages" :key="i" :value="i">{{ i
                                                    }}
                                                </option>
                                            </select>
                                        </div>


                                        <div v-if="userData.tournamentTeams.length" class="col-md-12 mt-4">
                                            <button v-if="!form.isSaving" @click.prevent="save"
                                                class="btn btn-primary-theme btn w-100">Save</button>
                                            <button v-else class="btn btn-primary-theme w-100" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Saving...
                                            </button>
                                        </div>
                                    </div>
                                    <!-- </fieldset> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <!-- <fieldset class="border rounded-3 p-3 bg-light-subtle h-100"> -->
                            <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">LIST
                                </legend> -->

                            <div class="card border-0 card-fixed-height h-100">
                                <div class="card-header text-muted  bg-transparent border-0">
                                    MATCHES LIST:
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 mt-3">
                                        <div class="card border-0 p-0">
                                            <componentLoadingSpinner v-if="dataIsLoading" />
                                            <div v-else class="card-body p-1 m-1 text-nowrap">
                                                <div v-if="userData.tournamentMatches">
                                                    <EasyDataTable class="border-0" :headers="tableHeaders"
                                                        :items="userData.tournamentMatches">

                                                        <template #item-homeVsAway="item">
                                                            <div>
                                                                {{ item?.home_team?.team_name ?? '-' }}
                                                                <span class=" fw-bolder"> VS </span>
                                                                {{ item?.away_team?.team_name ?? '-' }}
                                                            </div>
                                                        </template>

                                                        <template #item-kick_off="item">
                                                            <div class=" fw-bolder"> {{ (new
                                                                Date(item.kick_off)).toDateString()
                                                                }} </div>
                                                        </template>



                                                        <template #item-delete="item">
                                                            <div class="operation-wrapper">
                                                                <span @click="deleteMatch(item)"
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
                                </div>
                            </div>
                            <!-- </fieldset> -->
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
const dataIsLoading = ref<any>(true)

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadMatchData()
    }

})

async function loadMatchData() {
    userData.getTournamentTeams(selectedTournament.value.id)
    loadTournamentMatches()

}


async function loadTournamentMatches() {
    await userData.getTournamentMatches(selectedTournament.value.id)
    dataIsLoading.value = false
}

// TABLE #####################################
const tableHeaders: Header[] = [
    { text: "MATCH", value: "homeVsAway" },
    { text: "TYPE", value: "match_stage" },
    { text: "KICK OFF", value: "kick_off" },
    { text: "", value: "delete" },
];

async function deleteMatch(match: any) {
    if (confirm('Delete this scheduled match ?')) {
        try {
            let resp = await api.deleteMatch(match.match_id)
            if (resp.status == 200) {
                loadTournamentMatches()
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
        let resp = await api.createMatch(obj)
        if (resp.status == 203) {
            $toast.error('Duplicate entry', { position: 'top-right' });
            form.isSaving = false
            return;
        }
        $toast.default('New match added', { position: 'top-right' });
        userData.getTournamentMatches(selectedTournament.value.id)
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
