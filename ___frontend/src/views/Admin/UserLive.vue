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
                        <select v-model="selectedTournament"
                            class="form-select text-uppercase rounded-0 border-end-0 border-start-0 border-top-0  border-bottom-3 cursor-pointer"
                            @change="loadLiveMatches">
                            <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="float-en">
                                <button data-bs-toggle="modal" data-bs-target="#addLiveMatchModal"
                                    class="btn btn-primary btn-sm">
                                    CLICK HERE TO ADD LIVE MATCH <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div v-if="!userData.tournamentLive.length">
                                <emptyDataComponent>
                                    No Live Matches Start a live match!
                                </emptyDataComponent>
                            </div>
                            <div class="row  gy-3">
                                <ComponentLive v-for="(liveData, i) in userData.tournamentLive" :key="i"
                                    :team-data="liveData" />
                            </div>
                        </div>

                    </div>
                </div>
                <div v-if="userData.tournamentLive.length" class="col-lg-4 mt-lg-4 mb-4  border-0  p-3 card">
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
                </div>


            </div>
        </div>
        <addLiveMatchModal :tour="selectedTournament" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import ComponentLive from './_ComponentLive.vue'
import addLiveMatchModal from '@/components/modals/addLiveMatchModal.vue';

const userData = useUserDataStore()
const selectedTournament = ref<any>({})

onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        // console.log(selectedTournament.value);
        loadLiveMatches()
    }
})

function loadLiveMatches() {
    userData.getTournamentTeams(selectedTournament.value.id)
    userData.getLiveMatches(selectedTournament.value.id)
}

</script>
