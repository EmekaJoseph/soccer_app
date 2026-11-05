<template>
    <emptyDataComponent v-if="!stats.tourResults.length" icon="bi bi-check2-circle">
        No results yet.
    </emptyDataComponent>

    <MatchDayList v-else :items="stats.tourResults" :date-of="(r) => r.date_played" :key-of="(r) => r.result_id">
        <template #default="{ item: result }">
            <MatchRow status="FT" :meta="fx.stageLabel(result.match_stage, '')"
                :home="side(result, 'home')" :away="side(result, 'away')" />
        </template>
    </MatchDayList>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore'
import fx from '@/store/useFunctions'
import MatchDayList from './MatchDayList.vue'
import MatchRow, { type MatchSide } from './MatchRow.vue'

const stats = useStatsStore()

function side(result: any, team: 'home' | 'away'): MatchSide {
    return {
        name: result[`${team}_name`],
        badge: result[`${team}_badge`],
        color: result[`${team}_color`],
        score: result[`${team}_score`],
        penalties: result[`${team}_score_pen`],
        won: result.winner ? result.winner === result[`${team}_team`] : undefined,
    }
}
</script>
