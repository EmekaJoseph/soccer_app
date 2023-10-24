<template>
    <div class="min-vh-100">
        <div class="row justify-content-center gy-3">
            <div v-for="(team, i) in stats.tourTeamsInfo" :key="i" class="col-lg-8">
                <div class="card p-2 ">
                    <div class="accordion accordion-flush" :id="'accordionFlush' + i">
                        <div class="accordion-item">
                            <h2 class="accordion-header" :id="'flush-headingOne' + i">
                                <button class="accordion-button  text-uppercase" :class="{ 'collapsed': i != 0 }"
                                    type="button" data-bs-toggle="collapse" :data-bs-target="'#flush' + i"
                                    aria-expanded="true" :aria-controls="'flush' + i">
                                    <i :style="{ color: `${!team.team_color ? '#eee' : team.team_color}` }"
                                        class="bi bi-shield-fill"></i>
                                    &nbsp;{{ team.team_name }}
                                </button>
                            </h2>
                            <div :id="'flush' + i" class="accordion-collapse collapse" :class="{ 'show': i == 0 }"
                                :aria-labelledby="'flush-headingOne' + i" :data-bs-parent="'#accordionFlush' + i">
                                <div class="accordion-body pt-1">
                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item ps-0">
                                            Played:
                                            <span class="float-end fw-bold">{{ team.played }}</span>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            Won:
                                            <span class="float-end fw-bold">{{ team.won }}</span>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            Lost:
                                            <span class="float-end fw-bold">{{ team.lost }}</span>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            Draw:
                                            <span class="float-end fw-bold">{{ team.draw }}</span>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            Goals Scored:
                                            <span class="float-end fw-bold">{{ team.scored }}</span>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            Goals Conceded:
                                            <span class="float-end fw-bold">{{ team.conceded }}</span>
                                        </li>
                                    </ul>

                                    <div v-if="team.rating !== undefined">
                                        <div class="text-muted" style="font-size: 11px;">Rating: {{ team.rating * 10 }}
                                        </div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success " :class="{ 'bg-danger': team.rating < 5 }"
                                                role="progressbar" :style="'width:' + (team.rating * 10) + '%;'"
                                                :aria-valuenow="team.rating" aria-valuemin="0" aria-valuemax="100">

                                            </div>
                                        </div>
                                    </div>
                                </div>
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

const stats = useStatsStore()

</script>

<style scoped>
.accordion-button {
    font-weight: bold;
    text-transform: capitalize;
    font-size: 13px;
    border-radius: 20px;
}

.accordion-button:not(.collapsed) {
    background-color: #2790C510;
    box-shadow: none
}
</style>