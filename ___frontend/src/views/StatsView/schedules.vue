<template>
    <div v-if="stats.apiLoading" class="d-flex justify-content-center align-items-center mt-5">
        <img src="@/assets/images/loader.gif" width="40" alt="">
    </div>
    <div v-else>
        <div v-if="stats.apiError" class="d-flex justify-content-center align-items-center mt-5">
            Error fetching data, Try reloading the page or come back later.
        </div>
        <div v-else>
            <div v-if="!stats.tourSchedules.length" class="d-flex justify-content-center align-items-center mt-5">
                No matches to show
            </div>
            <div v-else class="animate__animated  animate__bounceInUp">
                <div v-for="({ match_stage, kick_off, home_team, away_team, venue }, i) in stats.tourSchedules" :key="i"
                    class="card bg-white rounded-0 mb-4">
                    <div class="card-header fw-bolder text-center">
                        {{ match_stage ? match_stage : 'Match' }} -
                        {{ dateFormat(kick_off) }},
                        {{ new Date(kick_off).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 fw-bolder text-uppercase text-center">{{ home_team }}</div>
                            <div class="col-2">
                                <span class="fw-bold versus text-center">VS</span>
                            </div>
                            <div class="col-5 fw-bolder text-uppercase text-center">{{ away_team }}</div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-white text-muted py-0 pb-2">
                        <div class="text-center small">@{{ venue }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from './statsStore';
import { useDateFormat } from '@vueuse/core';

const stats = useStatsStore();

const dateFormat = (date: any) => useDateFormat(new Date(date), 'DD/MMMM/YYYY').value

</script>

<style scoped>
.versus {
    color: var(--theme-color-2);
}

@media screen and (max-width: 992px) {
    .card {
        font-size: 12px;
    }
}
</style>