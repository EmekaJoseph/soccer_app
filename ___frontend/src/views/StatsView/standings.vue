<template>
    <emptyDataComponent v-if="!stats.tourStandings.length" icon="bi bi-list-ol">
        Standings will appear once teams are added.
    </emptyDataComponent>

    <template v-else-if="stats.tour_type == 'cup'">
        <StandingsTable v-for="{ group, teams } in stats.tourStandings" :key="group"
            :title="group ? `Group ${group}` : 'Teams'" :rows="teams" />
    </template>

    <StandingsTable v-else :rows="stats.tourStandings" />
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore'
import StandingsTable from './StandingsTable.vue'

const stats = useStatsStore()
</script>
