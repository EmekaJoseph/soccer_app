<template>
    <div class="container">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row justify-content-center gy-4">
                <div class="col-lg-12">
                    <fieldset class="border rounded-3 p-3 bg-white">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">Teams</legend>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <label>Show Teams Under: </label>
                                <select v-model="selectedTournament" class="form-select bg-light-subtle text-uppercase"
                                    @change="loadTournamentTeams">
                                    <option v-for="i in userData.tournaments" :key="i" :value="i.id">{{ i.title }}</option>
                                </select>
                            </div>
                            <div class="d-none d-lg-block col-md-4">
                                <div v-if="userData.tournamentTeams.length > 10">
                                    <label>&nbsp;</label>
                                    <input placeholder="search team.." type="text" class="form-control "
                                        v-model="searchValue">
                                </div>
                            </div>
                            <div class="col-lg-2 d-none d-lg-block"></div>
                            <div class="col-md-6 col-lg-2">
                                <label>&nbsp;</label>
                                <input data-bs-toggle="modal" data-bs-target="#newTeamModal" type="button"
                                    value="ADD A TEAM" class="form-control btn btn-primary text-white float-lg-end">
                            </div>
                        </div>

                        <div class="content-panel">
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-body p-1 m-1">
                                        <div v-if="userData.tournamentTeams">
                                            <EasyDataTable class="border-0"
                                                :headers="current_type == 'cup' ? headersCUP : headersLeague"
                                                :items="userData.tournamentTeams" show-index :sort-by="sortBy"
                                                :sort-type="sortType" :search-field="searchField"
                                                :search-value="searchValue" buttons-pagination>

                                                <template #item-edit="item">
                                                    <div class="operation-wrapper">
                                                        <span data-bs-toggle="modal" data-bs-target="#editMaterial"
                                                            @click="" class=" operation-icon cursor-pointer">
                                                            <i class="bi bi-pencil"></i>
                                                        </span>

                                                    </div>
                                                </template>
                                                <template #item-delete="item">
                                                    <div class="operation-wrapper">

                                                        <span @click="deleteTeam(item)"
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
                    </fieldset>
                </div>
            </div>
            <newTeamModal @done="reloadTournamentTeams" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header, Item, SortType } from "vue3-easy-data-table";
import newTeamModal from '@/components/modals/newTeamModal.vue'
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';


const $toast = useToast();

const userData = useUserDataStore()
const selectedTournament = ref('')

const current_type = computed(() => {
    let tour = userData.tournaments.find((x: { id: string; }) => x.id == selectedTournament.value);
    return tour ? tour.type : '';
})

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0].id
        loadTournamentTeams()
    }
})

function loadTournamentTeams() {
    userData.getTournamentTeams(selectedTournament.value)
}

function reloadTournamentTeams() {
    $toast.default('New Team added', { position: 'top-right' });
    userData.getTournamentTeams(selectedTournament.value)
}


// TABLE #####################################
const sortBy: string = "group_in";
const sortType: SortType = "asc";
const searchField = ["team_name"];
const searchValue = ref('');

const headersCUP: Header[] = [
    { text: "NAME", value: "team_name" },
    { text: "GROUP", value: "group_in", sortable: true },
    { text: "", value: "edit" },
    { text: "", value: "delete" },
];

const headersLeague: Header[] = [
    { text: "NAME", value: "team_name" },
    { text: "", value: "edit" },
    { text: "", value: "delete" },
];

async function deleteTeam(team: any) {


    if (confirm('Delete ' + team.team_name + ' ?')) {
        try {
            let resp = await api.deleteTeam(team.team_id)
            if (resp.status == 200) {
                loadTournamentTeams()
                $toast.default(team.team_name + ' deleted', { position: 'top-right' });
            }
        } catch (error) {
            console.log(error);
            $toast.error('Could not delete, Internet Error', { position: 'top-right' });
        }
    }
}

</script>

<style scoped></style>