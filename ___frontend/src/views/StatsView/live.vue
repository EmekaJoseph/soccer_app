<template>
    <div v-if="!stats.tourLives.length">
        <emptyDataComponent>
            No Live Matches
        </emptyDataComponent>
    </div>
    <div v-else>
        <div class="row justify-content-center">
            <div v-for="({ match_stage, home_team, away_team, home_team_score, away_team_score, curr_time }, i) in stats.tourLives"
                :key="i" class="col-sm-6">
                <div class="card bg-success-subtle  rounded-0 mb-4">
                    <div class="card-header fw-bold bg-transparent shadow border-0 rounded-0 small">
                        <span class="text-success  fw-bold fs-5">{{ curr_time }}'
                        </span>
                        <div class="float-end">{{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}</div>

                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 fw-bolder text-uppercase">
                                {{ home_team }}
                                <span class="float-end">{{ home_team_score }}</span>
                            </div>

                            <div class="col-12 fw-bolder text-uppercase">
                                {{ away_team }}
                                <span class="float-end ">{{ away_team_score }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore';
const stats = useStatsStore();

// @ts-ignore
window.Echo.channel('liveMatch').listen('liveScore', (e) => {
    //console.log(e); // the data from the server
    let liveMatch = stats.tourLives.find((x) => x.live_id == e.live_id)
    liveMatch.home_team_score = e.results.home_team_score
    liveMatch.away_team_score = e.results.away_team_score
    liveMatch.curr_time = e.results.curr_time
})

// @ts-ignore
window.Echo.channel('endMatch').listen('endMatch', (e) => {
    //console.log(e); // the data from the server
    stats.tourLives = stats.tourLives.filter((x) => x.live_id != e.live_id)
})

</script>

<style scoped>
@media screen and (max-width: 992px) {
    .card {
        font-size: 14px;
    }
}

.winner {
    border-right: 2px solid green;
}
</style>