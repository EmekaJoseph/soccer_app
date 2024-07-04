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
                        @change="loadPredictionsData">
                        <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}</option>
                    </select>
                </div>

                <div class="col-lg-12">
                    <div class="col-md-4">
                        <input placeholder="search name.." type="text" class="form-control " v-model="searchValue">
                    </div>
                </div>


                <div class="col-lg-12">
                    <fieldset class="border rounded-3 p-3 bg-light-subtle h-100 shadow-sm">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">PREDICTIONS:
                        </legend>
                        <div class="col-md-12 mt-3">
                            <div class="card border-0 p-0">
                                <div class="card-body p-1 m-1">
                                    <div v-if="userData.predictions">
                                        <EasyDataTable class="border-0" :headers="tableHeaders"
                                            :items="userData.predictions" show-index :search-field="searchField"
                                            :search-value="searchValue">


                                            <template #item-full_name="item">
                                                {{ item.full_name }},
                                                ({{ item.phone_number }})
                                            </template>
                                        </EasyDataTable>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-lg-12">
                    <fieldset class="border rounded-3 p-3 bg-light-subtle h-100 shadow-sm">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">FIND WINNERS:
                        </legend>

                        <div class="col-lg-12">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="text-muted">1ST:</label>
                                    <select class="form-select  cursor-pointer" v-model="formSearchObj.first"
                                        @change="formSearchObj.second = formSearchObj.third = '0'">
                                        <option value="0" selected disabled>Select...</option>
                                        <option v-for="i in userData.tournamentTeams" :key="i" :value="i.team_id">
                                            {{ i.team_name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted">2ND:</label>
                                    <select class="form-select  cursor-pointer" v-model="formSearchObj.second"
                                        @change="formSearchObj.third = '0'">
                                        <option value="0" selected>*Any*</option>
                                        <option v-for="i in secondPostitionDrop" :key="i" :value="i.team_id">
                                            {{ i.team_name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted">3RD:</label>
                                    <select class="form-select  cursor-pointer" v-model="formSearchObj.third">
                                        <option value="0" selected>*Any*</option>
                                        <option v-for="i in thirdPositionDrop" :key="i" :value="i.team_id">
                                            {{ i.team_name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <button :disabled="(formSearchObj.first == '0')" @click="searchWinners()"
                                        v-if="!formSearchObj.isLoading" class="btn btn-primary-theme mt-3 w-100">
                                        <i class="bi bi-search"></i> Search by filter
                                    </button>
                                    <button v-else disabled class="btn btn-primary-theme mt-3 w-100">
                                        Searching ..
                                    </button>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>
            </div>
        </div>







        <!-- Button trigger modal -->
        <button ref="winnersModalTrigger" type="button" class="d-none" data-bs-toggle="modal"
            data-bs-target="#winnersModal">
        </button>

        <!-- Modal -->
        <div class="modal fade" id="winnersModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light-subtle">
                        <h6 class="modal-title fs-6 fw-bolder">WINNERS BY PREDICTIONS ({{ winnersResultData.length }})
                        </h6>
                        <button ref="btnX" type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4  min-vh-50">
                        <div class="table-responsive" v-if="winnersResultData.length">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>S/N</td>
                                        <td>Name</td>
                                        <td>Date</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(winner, index) in winnersResultData" :key="index">
                                        <th>{{ (index + 1) }}</th>
                                        <td>{{ winner.full_name }}</td>
                                        <td>{{ (new Date(winner.created_at)).toLocaleString() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>
                            <div class="text-center py-5">No winners</div>
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
import type { Header } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import { onBeforeRouteLeave } from 'vue-router';

const userData = useUserDataStore()
const selectedTournament = ref<any>({})


onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        userData.getTournamentTeams(selectedTournament.value.id)
        loadPredictionsData();
    }
})

function loadPredictionsData() {
    userData.getPredictions(selectedTournament.value.id)
}

// TABLE #####################################
const searchField = ["full_name"];
const searchValue = ref('');
const tableHeaders: Header[] = [
    { text: "Name", value: "full_name" },
    { text: "1ST", value: "first_place" },
    { text: "2ND", value: "second_place" },
    { text: "3RD", value: "third_place" },
    { text: "Date", value: "predicted" },
    // { text: "", value: "delete" },
];



// searching winners ###########################################################
const winnersResultData = ref<any>([])
const winnersModalTrigger = ref<any>(null)
const btnX: any = ref(null)
onBeforeRouteLeave(() => {
    btnX.value.click()
})
const formSearchObj = reactive({
    first: '0',
    second: '0',
    third: '0',
    tour_id: '',
    isLoading: false
})
const secondPostitionDrop = computed(() => {
    return userData.tournamentTeams.filter((x: { team_id: any; }) => x.team_id !== formSearchObj.first)
})

const thirdPositionDrop = computed(() => {
    return userData.tournamentTeams.filter((x: { team_id: any; }) => ((x.team_id !== formSearchObj.first) && (x.team_id !== formSearchObj.second)))
})


async function searchWinners() {
    // console.log(formSearchObj);
    formSearchObj.isLoading = true

    try {
        formSearchObj.tour_id = selectedTournament.value.id
        let resp = await api.getWinnersByPrediction(formSearchObj)
        if (resp.status == 200) {
            winnersResultData.value = resp.data
            winnersModalTrigger.value.click()
        }
    } catch (error) {
        console.log(error);
    }
    finally {
        formSearchObj.isLoading = false
    }

}
// searching winners ###########################################################

</script>