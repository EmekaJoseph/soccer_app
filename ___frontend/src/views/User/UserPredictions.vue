<template>
    <div class="container px-3">
        <componentLoadingSpinner v-if="loading" />
        <internetErrorComponent v-else-if="userData.apiError" />
        <div v-else class="row gy-4">
            <tourDropdownSelect v-model="selectedTournament" @change="changed" />

            <div v-if="!selectedTournament" class="col-12">
                <emptyDataComponent>Create a tournament on the dashboard first.</emptyDataComponent>
            </div>

            <template v-else>
                <div class="col-lg-12">
                    <fieldset class="border rounded-3 p-3 bg-light-subtle shadow-sm">
                        <legend class="text-muted float-none p-0 px-2 w-auto small fw-bolder">FIND WINNERS</legend>
                        <form class="row g-3" @submit.prevent="searchWinners">
                            <div class="col-md-3">
                                <label class="text-muted w-100">1ST:
                                    <select class="form-select" v-model="filter.first" required
                                        @change="filter.second = filter.third = ''">
                                        <option value="" disabled>Select…</option>
                                        <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted w-100">2ND:
                                    <select class="form-select" v-model="filter.second" @change="filter.third = ''">
                                        <option value="">*Any*</option>
                                        <option v-for="t in secondOptions" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted w-100">3RD:
                                    <select class="form-select" v-model="filter.third">
                                        <option value="">*Any*</option>
                                        <option v-for="t in thirdOptions" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" :disabled="!filter.first || searching" class="btn btn-primary-theme w-100">
                                    <i class="bi bi-search"></i> {{ searching ? 'Searching…' : 'Find winners' }}
                                </button>
                            </div>
                        </form>
                        <div v-if="winners" class="mt-4">
                            <div class="fw-bold mb-2">{{ winners.length }} matching prediction(s), earliest first</div>
                            <ol v-if="winners.length" class="mb-0">
                                <li v-for="w in winners" :key="w.prediction_id">
                                    {{ w.full_name }} — {{ w.phone_number }}
                                    <span class="text-muted small">({{ fx.dateDisplay(w.created_at, true) }})</span>
                                </li>
                            </ol>
                        </div>
                    </fieldset>
                </div>

                <div class="col-lg-12">
                    <fieldset class="border rounded-3 p-3 bg-light-subtle shadow-sm">
                        <legend class="text-muted float-none p-0 px-2 w-auto small fw-bolder">
                            PREDICTIONS ({{ userData.predictions.length }})
                        </legend>
                        <div class="col-md-4 mb-3">
                            <input placeholder="search name…" type="text" class="form-control" v-model="searchValue">
                        </div>
                        <EasyDataTable class="border-0" :headers="tableHeaders" :items="userData.predictions" show-index
                            :search-field="['full_name']" :search-value="searchValue">
                            <template #item-full_name="item">{{ item.full_name }} ({{ item.phone_number }})</template>
                        </EasyDataTable>
                        <div class="small text-muted mt-2">
                            Fans predict from the "Predict" button on your tournament's public page.
                        </div>
                    </fieldset>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'

const userData = useUserDataStore()
const searchValue = ref('')
const searching = ref(false)
const winners = ref<any[] | null>(null)
const filter = reactive({ first: '', second: '', third: '' })

const tableHeaders: Header[] = [
    { text: 'Name', value: 'full_name' },
    { text: '1ST', value: 'first_place' },
    { text: '2ND', value: 'second_place' },
    { text: '3RD', value: 'third_place' },
    { text: 'When', value: 'predicted' },
]

const { selectedTournament, changed, loading } = useTournamentPicker(async (tourId) => {
    winners.value = null
    Object.assign(filter, { first: '', second: '', third: '' })
    await Promise.all([userData.getTournamentTeams(tourId), userData.getPredictions(tourId)])
})

const secondOptions = computed(() => userData.tournamentTeams.filter((t) => t.team_id !== filter.first))
const thirdOptions = computed(() => userData.tournamentTeams.filter((t) => t.team_id !== filter.first && t.team_id !== filter.second))

async function searchWinners() {
    searching.value = true
    try {
        winners.value = (await api.getWinnersByPrediction(selectedTournament.value.tour_id, { ...filter })).data
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    } finally {
        searching.value = false
    }
}
</script>
