<template>
    <div class="container px-3">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row gy-4">
                <div class="col-lg-5 mb-3">
                    <!-- <label>Tournament: </label> -->
                    <tourDropdownSelect @change="loadTournamentTeams" v-model="selectedTournament" />
                </div>
                <div class="col-lg-12">
                    <div class="row gy-3">
                        <div class="col-lg-5">
                            <div class="card shadow-sm">
                                <div class="card-header text-muted fw-bold bg-transparent border-0">
                                    ADD A TEAM
                                </div>
                                <div class="card-body">
                                    <!-- <fieldset class="border rounded-3 p-3  "> -->
                                    <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bold">
                                            CREATE A TEAM:
                                        </legend> -->
                                    <div class="row g-3">
                                        <div v-if="selectedTournament.type == 'cup'" class="col-md-12">
                                            <div class="form-floating">
                                                <select id="fo4444" v-model="form.group_in"
                                                    class="form-select  text-uppercase">
                                                    <option value="" selected disabled>--select--</option>
                                                    <option v-for="i in userData.valid_groups" :key="i" :value="i">
                                                        {{ i }}
                                                    </option>
                                                </select>
                                                <label for="fo4444">Group</label>
                                            </div>

                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input v-model="form.team_name" type="text" class="form-control"
                                                    id="formId1xaxa" placeholder="" />
                                                <label for="formId1xaxa">Team Name</label>
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <textarea id="team_brief" v-model="form.team_brief"
                                                    style="height: 100px;" class="form-control"
                                                    placeholder=""></textarea>
                                                <label for="team_brief">About Team (optional):</label>
                                            </div>
                                        </div>

                                        <div v-if="userData.tournaments.length" class="col-md-12 mt-3">
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
                            <div class="card border- shadow-sm card-fixed-height">
                                <div class="card-header text-muted fw-bold bg-transparent border-0">
                                    TEAMS LIST
                                </div>
                                <div class="card-body">
                                    <!-- <fieldset class="border rounded-3 p-3 "> -->
                                    <!-- <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bold">
                                            TEAMS LIST:
                                        </legend> -->
                                    <div class="row">
                                        <div class="d-none d-lg-block col-md-4 float-end">
                                            <div v-if="userData.tournamentTeams.length > 10">
                                                <input placeholder="search team.." type="text" class="form-control "
                                                    v-model="searchValue">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <div class="card border-0">
                                            <loadingSpinner v-if="dataIsLoading" />
                                            <div v-else class="card-body p-1 m-1">
                                                <div v-if="userData.tournamentTeams">
                                                    <EasyDataTable class="border-0"
                                                        :headers="selectedTournament.type == 'cup' ? headersCUP : headersLeague"
                                                        :items="userData.tournamentTeams" show-index :sort-by="sortBy"
                                                        :sort-type="sortType" :search-field="searchField"
                                                        :search-value="searchValue" buttons-pagination>

                                                        <template #header="header">
                                                            <div class="fw-bolder">
                                                                {{ header.text == '#' ? 'S/N' : header.text }}</div>
                                                        </template>

                                                        <template #item-edit="item">
                                                            <div class="operation-wrapper">
                                                                <span data-bs-toggle="modal"
                                                                    data-bs-target="#editMaterial" @click=""
                                                                    class=" operation-icon cursor-pointer">
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

const comRef = ref()


const $toast = useToast();

const userData = useUserDataStore()
const selectedTournament = ref<any>({})
const dataIsLoading = ref<boolean>(true)

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadTournamentTeams()

    }
})

async function loadTournamentTeams() {
    await userData.getTournamentTeams(selectedTournament.value.id)
    dataIsLoading.value = false
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


// ################################################ FORM
const form = reactive({
    team_name: '',
    team_brief: ' ',
    group_in: 'A',
    isSaving: false
})

async function save() {

    if (!form.team_name) {
        $toast.default('Enter Team Name', { position: 'top-right' });
        return;
    }

    if (selectedTournament.value.type == 'cup') {
        if (!form.group_in) {
            $toast.default('Select Group', { position: 'top-right' });
            return;
        }
    }

    let obj: any = {};
    obj.team_name = form.team_name;
    obj.team_brief = form.team_brief;
    obj.tour_id = selectedTournament.value.id;
    obj.group_in = selectedTournament.value.type == 'cup' ? form.group_in : null

    form.isSaving = true

    try {
        let resp = await api.createTeam(obj)
        if (resp.status == 203) {
            $toast.error('Name already exists', { position: 'top-right' });
            form.isSaving = false
            return;
        }
        $toast.default('New Team added', { position: 'top-right' });
        userData.getTournamentTeams(selectedTournament.value.id)
        // emit('done')
        form.isSaving = false
        // btnX.value.click()
        form.team_name = "";
        form.team_brief = "";
        form.group_in = "";
    } catch (error) {
        $toast.error('Network Error', { position: 'top-right' });
        form.isSaving = false
    }

}

</script>

<style scoped></style>