<template>
    <div v-if="!stats.tourSchedules.length" class="min-vh-100">
        <emptyDataComponent>
            No shedules yet, <br> Come back later.
        </emptyDataComponent>

    </div>


    <!-- <div v-else class="animate__animated  animate__bounceInUp"> -->
    <div v-else class="min-vh-100">
        <div v-for="({ match_stage, kick_off, home_team, away_team, venue }, i) in stats.tourSchedules" :key="i"
            class="card bg-white rounded-0 mb-4">
            <div class="card-header fw-bolder bg-white shadow-sm border-0 text-center">
                {{ match_stage ? match_stage.replaceAll('_', ' ') : '' }}. <br>
                {{ dateFormat(kick_off) }},
                {{ new Date(kick_off).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
            </div>
            <div class="card-body px-2">
                <div class="row justify-content-center">
                    <div class="col-4 fw-bolder text-uppercase text-center team_name">{{ home_team.team_name }}</div>
                    <div class="col-1">
                        <span v-if="home_team.team_badge"> {{ home_team.team_badge }} </span>
                        <span v-else>
                            <i :style="{ color: `${!home_team.team_color ? '#eee' : home_team.team_color}` }"
                                class="bi bi-shield-fill"></i>
                        </span>
                    </div>
                    <div class="col-2">
                        <div class="text-center small">-</div>
                    </div>
                    <div class="col-1">
                        <span v-if="away_team.team_badge"> {{ away_team.team_badge }} </span>
                        <span v-else>
                            <i :style="{ color: `${!away_team.team_color ? '#eee' : away_team.team_color}` }"
                                class="bi bi-shield-fill"></i>
                        </span>
                    </div>
                    <div class="col-4  fw-bolder text-uppercase text-center team_name">{{ away_team.team_name }}</div>
                </div>
            </div>
            <div class="card-footer border-0 bg-white text-muted py-0 pb-2">
                <div class="text-center small">@{{ venue }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore';
import { useDateFormat } from '@vueuse/core';

const stats = useStatsStore();

const dateFormat = (date: any) => useDateFormat(new Date(date), 'ddd, DD MMMM YYYY').value

</script>

<style scoped>
.versus {
    color: var(--theme-color-2);
}



@media screen and (max-width: 992px) {
    .card {
        font-size: 12px;
    }

    /* .team_name {
        font-size: 11px;
    } */
}
</style>