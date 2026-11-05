<template>
    <TournamentPage v-model="selectedTournament" :loading="loading" @change="changed">
        <div class="row g-4">
            <div class="col-12">
                <AdminCard title="Find winners">
                    <form class="row g-3 align-items-end" @submit.prevent="searchWinners">
                        <div class="col-md-3">
                            <label class="w-100">1st place
                                <select v-model="filter.first" class="form-select" required @change="filter.second = filter.third = ''">
                                    <option value="" disabled>Select</option>
                                    <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <label class="w-100">2nd place
                                <select v-model="filter.second" class="form-select" @change="filter.third = ''">
                                    <option value="">Any</option>
                                    <option v-for="t in secondOptions" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <label class="w-100">3rd place
                                <select v-model="filter.third" class="form-select">
                                    <option value="">Any</option>
                                    <option v-for="t in thirdOptions" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" :disabled="!filter.first || searching" class="ui-btn ui-btn-primary find-btn w-100">
                                <i class="bi bi-search"></i> {{ searching ? 'Searching…' : 'Find winners' }}
                            </button>
                        </div>
                    </form>
                    <div v-if="winners" class="winners">
                        <div class="winners-head">{{ winners.length }} matching prediction(s), earliest first</div>
                        <ol v-if="winners.length" class="mb-0">
                            <li v-for="w in winners" :key="w.prediction_id">
                                {{ w.full_name }} · {{ w.phone_number }}
                                <span class="winner-date">{{ fx.dateDisplay(w.created_at, true) }}</span>
                            </li>
                        </ol>
                        <p v-else class="mb-0 small">Nobody predicted that combination. Try setting 2nd or 3rd place to Any.</p>
                    </div>
                </AdminCard>
            </div>

            <div class="col-12">
                <AdminCard title="Predictions" :count="userData.predictions.length"
                    hint="Fans predict from the Predict button on your tournament's public page.">
                    <template #actions>
                        <input v-model="searchValue" type="text" class="form-control search" placeholder="Search name…" aria-label="Search predictions by name">
                    </template>
                    <emptyDataComponent v-if="!userData.predictions.length" icon="bi bi-trophy">
                        No predictions yet. Share your public page so fans can join in.
                    </emptyDataComponent>
                    <EasyDataTable v-else :headers="tableHeaders" :items="userData.predictions" show-index
                        :search-field="['full_name']" :search-value="searchValue">
                        <template #item-full_name="item">{{ item.full_name }} ({{ item.phone_number }})</template>
                    </EasyDataTable>
                </AdminCard>
            </div>
        </div>
    </TournamentPage>
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

<style scoped>
.find-btn {
    padding-block: 0.7rem;
}

.search {
    width: 200px;
    padding-block: 0.35rem;
    font-size: 0.875rem;
}

.winners {
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid var(--line);
    color: var(--fg);
    font-size: 0.9375rem;
}

.winners-head {
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.winner-date {
    margin-left: 0.5rem;
    color: var(--fg-muted);
    font-size: 0.8125rem;
}
</style>
