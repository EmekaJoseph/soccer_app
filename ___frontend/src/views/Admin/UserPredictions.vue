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
                    <fieldset class="border rounded-3 p-3 bg-light-subtle h-100">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">PREDICTIONS:
                        </legend>
                        <div class="col-md-12 mt-3">
                            <div class="card border-0 p-0">
                                <div class="card-body p-1 m-1">
                                    <div v-if="userData.predictions">
                                        <EasyDataTable class="border-0" :headers="tableHeaders"
                                            :items="userData.predictions" show-index>


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

            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header } from "vue3-easy-data-table";

const userData = useUserDataStore()
const selectedTournament = ref<any>({})


onMounted(async () => {
    await userData.getTournaments()
    if (userData.tournaments.length) {
        selectedTournament.value = userData.tournaments[0]
        loadPredictionsData();
    }
})

function loadPredictionsData() {
    userData.getPredictions(selectedTournament.value.id)
}

// TABLE #####################################
const tableHeaders: Header[] = [
    { text: "Name", value: "full_name" },
    { text: "1ST", value: "first_place" },
    { text: "2ND", value: "second_place" },
    { text: "3RD", value: "third_place" },
    { text: "Date", value: "predicted" },
    // { text: "", value: "delete" },
];

</script>