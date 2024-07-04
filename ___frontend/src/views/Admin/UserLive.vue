<template>
    <div class="container px-3">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>
            <div class="row g-4">
                <div class="col-12 mb-4">
                    <div class="col-lg-4">
                        <!-- <label>Tournament: </label> -->
                        <select v-model="selectedTournament" class="form-select text-uppercase  cursor-pointer"
                            @change="loadLiveMatches">
                            <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row g-5">
                        <!-- <div class="col-12 mb-5">
                            <div class="float-lg-en">
                                <button data-bs-toggle="modal" data-bs-target="#addLiveMatchModal"
                                    class="btn btn-primary-theme btn-sm">
                                    ADD LIVE MATCH <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div> -->
                        <div class="col-lg-12">
                            <div v-if="!userData.tournamentLive.length">
                                <emptyDataComponent>
                                    No Live Matches Start a live match!
                                </emptyDataComponent>
                            </div>
                            <div class="row  gy-5">
                                <div v-if="userData.tournamentLive.length"
                                    class="col-12 alert border-0 mb-0 text-danger alert-warning">
                                    <b>Live Match is ON!, Do not logout or refresh page.</b>
                                </div>
                                <ComponentLive v-for="(liveData, i) in userData.tournamentLive" :key="i"
                                    :team-data="liveData" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div v-if="userData.tournamentLive.length" class="col-lg-4 mt-lg-4 mb-4  border-0  p-3 card">
                    <div class="card mt-lg-5 border-0 bg-transparent">
                        <div class="card-header fw-bold border-0 bg-transparent ">LIVE COMMENTRY</div>
                        <div class="card-body ">
                            <div class="row gy-3">
                                <div class="col-12">
                                    <textarea placeholder="type here.." class="form-control" rows="4"></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="float-en btn btn-outline-dark w-100">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->


            </div>
        </div>
        <addLiveMatchModal :tour="selectedTournament" />
        <otherLiveMatchesModal :tour="selectedTournament" :clicker="clicker" />

        <div v-if="authStore.getUserData().role == 'admin'" class="fixed-bottom-btn" @click="clicker = !clicker">
            <div class="justify-content-end floatPanel floatPanel-2 hover-tilt-Y">
                <div class="card newLiveCard otherLiveCard shadow" data-bs-toggle="modal"
                    data-bs-target="#otherLiveMatchesModal">
                    <div><i class="bi bi-info"></i></div>
                </div>
            </div>
        </div>

        <div v-if="userData.tournamentLive.length == 0" class="fixed-bottom-btn">
            <div class="justify-content-end floatPanel hover-tilt-Y">
                <div class="card newLiveCard shadow" data-bs-toggle="modal" data-bs-target="#addLiveMatchModal">
                    <div><i class="bi bi-plus-lg"></i></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import ComponentLive from './_ComponentLive.vue'
import addLiveMatchModal from '@/components/modals/addLiveMatchModal.vue';
import otherLiveMatchesModal from '@/components/modals/otherLiveMatchesModal.vue';
import { useAuthStore } from '@/store/authStore';

const userData = useUserDataStore()
const selectedTournament = ref<any>('')
const clicker = ref<boolean>(false)

const authStore = useAuthStore()

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadLiveMatches()
    }
})

function loadLiveMatches() {
    userData.getTournamentTeams(selectedTournament.value.id)
    userData.getLiveMatchesByUser(selectedTournament.value.id)
}

</script>

<style scoped>
.fixed-bottom-btn {
    position: fixed;
    bottom: 0;
    right: 0;
    z-index: 999;
}


.floatPanel {
    margin-bottom: 60px;
    padding-right: 24px;
    display: flex;
    z-index: 999;
    position: relative;
    transition: all ease-in-out 0.4s;
    font-size: 11px;
}

.floatPanel-2 {
    margin-bottom: 130px !important;
}

.newLiveCard {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.43rem;
    color: #fff;
    background-color: #27566d;
    cursor: pointer;
}

.otherLiveCard {
    color: #fff;
    background-color: #6c6d27 !important;
}
</style>
