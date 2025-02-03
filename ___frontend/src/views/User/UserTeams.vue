<template>
    <div class="container px-3">
        <ComponentLoadingSpinner v-if="userData.apiLoading" />
        <div v-else>
            <div v-if="userData.apiError">
                <internetErrorComponent />
            </div>
            <div v-else>
                <div class="row gy-4">

                    <tourDropdownSelect @change="loadTournamentTeams" v-model="selectedTournament" />

                    <div v-if="selectedTournament?.id" class="col-lg-12">
                        <div class="row gy-3">
                            <div class="col-lg-6">
                                <div class="card border-0">
                                    <div class="card-header text-muted  bg-transparent border-0">
                                        TEAMS ({{ userData.tournamentTeams.length }})
                                        <span class="float-end">
                                            <button @click="openTeamForm" class="btn btn-primary-theme btn-sm">
                                                New Team <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12 mt-3">
                                            <div class="card min-vh-50 h-100 ">
                                                <componentLoadingSpinner v-if="teamsLoading" />
                                                <div v-else class="card-body">
                                                    <div v-if="userData.tournamentTeams">
                                                        <ul v-for="(team, index) in userData.tournamentTeams"
                                                            class="list-group list-group-flush">
                                                            <li @click="activeTeam = team"
                                                                class="list-group-item team-line-item my-1"
                                                                :class="{ 'active-team': activeTeam?.team_id == team.team_id }">
                                                                <span class="me-2">{{ index + 1 }}</span>
                                                                {{ team.team_name }}
                                                                <i class="bi bi-circle-fill me-1 "
                                                                    :style="{ color: team.team_color ?? '#ccc' }"></i>
                                                                <span v-if="team.group_in" class="me-1">
                                                                    (Group {{ team.group_in }})
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- </fieldset> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border-0 h-100">
                                    <div
                                        class="card-header text-muted  bg-transparent border-0 text-uppercase fw-bolder">
                                        {{ activeTeam?.team_name }}
                                        <span class="span float-end small cursor-pointer">
                                            <i class="bi bi-pencil-fill me-4"></i>
                                            <i @click="deleteTeam(activeTeam)"
                                                class="bi bi-trash3 text-danger me-3"></i>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <!-- Some borders are removed -->
                                        <ul class="list-group list-group-flush small">
                                            <li class="list-group-item">
                                                <div class="fw-bolder">ABOUT:</div>
                                                {{ activeTeam?.team_brief }}
                                            </li>
                                            <li class="list-group-item">
                                                <div class="fw-bolder"> MANAGER:</div>
                                                {{ activeTeam?.manager }}
                                            </li>
                                            <li class="list-group-item">
                                                <div class="fw-bolder"> MATCH PLAYED:</div>
                                                {{ activeTeam?.match_played }}
                                            </li>

                                            <li class="list-group-item">
                                                <div class="fw-bolder">PLAYERS:</div>
                                                {{ activeTeam?.players ?? 0 }}
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <teamFormModal :toggle-modal="formModal" :selected-tournament="selectedTournament" />
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header, Item, SortType } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';
import ComponentLoadingSpinner from '@/components/componentLoadingSpinner.vue';


import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import useFunctions from '@/store/useFunctions';
import teamFormModal from '@/components/modals/userModals/teamFormModal.vue';



const formModal = ref<boolean>(false)

// const pureColor = ref<ColorInputWithoutInstance>("red");
// const gradientColor = ref("linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 1) 100%)");

const $toast = useToast();

const userData = useUserDataStore()
const selectedTournament = ref<any>({})
const teamsLoading = ref<boolean>(true)

onMounted(async () => {
    userData.apiLoading = true
    await userData.getTournaments()
    userData.apiLoading = false
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        await loadTournamentTeams()
        updateActiveTeam()
    }
})

async function loadTournamentTeams() {
    teamsLoading.value = true
    await userData.getTournamentTeams(selectedTournament.value.id)
    teamsLoading.value = false
}




function openTeamForm() {
    formModal.value = !formModal.value
}

function updateActiveTeam() {
    activeTeam.value = userData.tournamentTeams.length ? userData.tournamentTeams[0] : null
}



// TABLE #####################################
const activeTeam = ref<any>(null)
async function deleteTeam(team: any) {
    useFunctions.confirmDelete(`Delete ${team.team_name} ?`, 'Yes, Delete').then(async (tap) => {
        if (tap.value) {
            try {
                let resp = await api.deleteTeam(team.team_id)
                if (resp.status == 200) {
                    loadTournamentTeams()
                    updateActiveTeam()
                    $toast.default(team.team_name + ' deleted', { position: 'top-right' });
                }
            } catch (error) {
                console.log(error);
                $toast.error('Could not delete, Internet Error', { position: 'top-right' });
            }
        }
    })
}

</script>

<style scoped>
.team-line-item {
    cursor: pointer;
    font-size: 14px;
    border-radius: 10px;
}

.team-line-item:hover {
    background-color: #cccccc47;
}


.active-team {
    background-color: #ccccccb5;
}
</style>
