<template>
    <emptyDataComponent v-if="!stats.tourMatches.length" icon="bi bi-calendar-event">
        No upcoming fixtures. Check back soon.
    </emptyDataComponent>

    <MatchDayList v-else :items="stats.tourMatches" :date-of="(m) => m.kick_off" :key-of="(m) => m.match_id">
        <template #default="{ item: match }">
            <MatchRow :status="kickoffTime(match.kick_off)" :meta="metaOf(match)"
                :home="{ name: match.home_team?.team_name, badge: match.home_team?.team_badge, color: match.home_team?.team_color }"
                :away="{ name: match.away_team?.team_name, badge: match.away_team?.team_badge, color: match.away_team?.team_color }" />
        </template>
    </MatchDayList>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore'
import fx from '@/store/useFunctions'
import MatchDayList from './MatchDayList.vue'
import MatchRow from './MatchRow.vue'
import { kickoffTime } from './matchDates'

const stats = useStatsStore()

const metaOf = (match: any) => [fx.stageLabel(match.match_stage, ''), match.venue].filter(Boolean).join(' · ')
</script>
