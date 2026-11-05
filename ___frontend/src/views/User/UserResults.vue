<template>
    <TournamentPage v-model="selectedTournament" :loading="loading" @change="changed">
        <AdminCard title="Results" :count="userData.tournamentResults.length">
            <template #actions>
                <button class="ui-btn ui-btn-primary ui-btn-sm" @click="formOpen = true">
                    <i class="bi bi-plus-lg"></i> Record result
                </button>
            </template>

            <componentLoadingSpinner v-if="dataIsLoading" />
            <emptyDataComponent v-else-if="!userData.tournamentResults.length" icon="bi bi-check2-circle">
                No results recorded yet.
                <div class="mt-3">
                    <button class="ui-btn ui-btn-primary" @click="formOpen = true">Record the first result</button>
                </div>
            </emptyDataComponent>
            <EasyDataTable v-else class="text-nowrap sticky-actions" :headers="tableHeaders" :items="userData.tournamentResults">
                <template #item-results="item">
                    {{ item.home_name }} <b class="score">{{ item.home_score }}–{{ item.away_score }}</b> {{ item.away_name }}
                    <span v-if="item.home_score_pen !== null" class="pens">
                        ({{ item.home_score_pen }}–{{ item.away_score_pen }} pens)
                    </span>
                </template>
                <template #item-match_stage="item">{{ fx.stageLabel(item.match_stage, '—') }}</template>
                <template #item-played="item">
                    {{ item.date_played ? fx.dateDisplay(item.date_played) : '—' }}
                </template>
                <template #item-undo="item">
                    <button class="ui-btn ui-btn-danger ui-btn-sm" @click="undoResult(item)">Undo</button>
                </template>
            </EasyDataTable>
        </AdminCard>
    </TournamentPage>

    <ResultFormModal v-if="formOpen" :tournament="selectedTournament" @close="formOpen = false"
        @saved="loadData(selectedTournament.tour_id)" />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'
import ResultFormModal from '@/components/modals/userModals/ResultFormModal.vue'

const userData = useUserDataStore()
const dataIsLoading = ref(true)
const formOpen = ref(false)

const tableHeaders: Header[] = [
    { text: 'RESULT', value: 'results' },
    { text: 'STAGE', value: 'match_stage' },
    { text: 'DATE', value: 'played' },
    { text: '', value: 'undo' },
]

const { selectedTournament, changed, loading } = useTournamentPicker(loadData)

async function loadData(tourId: string) {
    dataIsLoading.value = true
    await Promise.all([userData.getTournamentResults(tourId), userData.getTournamentMatches(tourId)])
    dataIsLoading.value = false
}

async function undoResult(result: any) {
    const tap = await fx.confirmDelete('Undo this result? The standings will be recalculated.', 'Yes, undo')
    if (!tap.isConfirmed) return

    try {
        await api.undoResult(result.result_id)
        fx.toast.info('Result undone')
        loadData(selectedTournament.value.tour_id)
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>

<style scoped>
.score {
    margin-inline: 4px;
    font-family: var(--font-mono);
    font-weight: 500;
}

.pens {
    color: var(--fg-muted);
    font-size: 0.8125rem;
}
</style>
