<template>
    <div>
        <div class="modal fade" id="otherLiveMatchesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-sm">
                <div class="modal-content">
                    <div class="modal-header border-0 bg-light">
                        <span class="fw-bold">Other Live Matches:</span>
                        <span class="float-end">
                            <button ref="btnX" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </span>
                    </div>
                    <div class="modal-body">

                        <div v-if="isLoading" class="d-flex justify-content-center align-items-center my-5">
                            <div style="width: 3rem; height: 3rem;" class="text-secondary spinner-border spinner-border-sm">
                            </div>
                        </div>
                        <div v-else>
                            <ul v-if="liveMatchesList.length" class="list-group list-group-flush">
                                <li v-for="live in liveMatchesList" class="list-group-item">
                                    <div class="text-center">{{ live.home_team }} <span class="fw-bold">VS</span> {{
                                        live.away_team }}</div>
                                    <div class="text-center">{{ live.home_team_score }} : {{ live.away_team_score }}
                                    </div>
                                    <div class="text-center">
                                        <small class="text-muted text-center">created by: </small>
                                        <span>
                                            {{ live.isMe ? live.isMe : live.creator.email }}
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            <div v-else class="d-flex justify-content-center align-items-center my-5">
                                NO MATCHES
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'

watch(() => prop.clicker, () => {
    tour_id.value = prop.tour.id
    isLoading.value = true
    liveMatchesList.value = []
    getLiveMatchesForAdmin()
    console.log(prop.clicker);

})

const prop = defineProps({
    tour: {
        type: Object,
        required: true
    },
    clicker: {
        type: Boolean,
        required: true
    }


})

const tour_id = ref<any>(null)
const liveMatchesList = ref<any[]>([])
const isLoading = ref(false)



async function getLiveMatchesForAdmin() {
    try {
        let resp = await api.getLiveMatchesForAdmin(tour_id.value)
        liveMatchesList.value = resp.data
        console.log(resp.data);
    } catch (error) {

    }
    finally {
        isLoading.value = false
    }
}


const btnX: any = ref(null)
onBeforeRouteLeave(() => {
    btnX.value.click()
})
</script>