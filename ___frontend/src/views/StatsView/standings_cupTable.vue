<template>
    <div v-for="({ group, teams }, i) in prop.data" :key="i" class="card mb-4">
        <div class="card-header fw-bolder bg-white shadow-sm border-0">
            GROUP {{ group }}
        </div>
        <div v-if="teams" class="card-body p-1 py-3">
            <EasyDataTable table-class-name="easy-table-cup-league" class="border-0" :headers="headers" :items="teams"
                show-index>

                <template #header="header">
                    <div class="fw-bolde">
                        {{ header.text == '#' ? '' : header.text }}</div>
                </template>

                <template #item-team_badge="item">
                    <div v-if="item.badge"> {{ item.badge }} </div>
                    <div v-else>
                        <i :style="{ color: `${!item.team_color ? '#eee' : item.team_color}` }"
                            class="bi bi-shield-fill"></i>
                    </div>
                </template>

                <template #item-team_name="item">
                    <div class=" fw-bolder"> {{ item.team_name }} </div>
                </template>

                <template #item-points="item">
                    <div class=" fw-bolder"> {{ item.points }} </div>
                </template>

            </EasyDataTable>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Header } from "vue3-easy-data-table";
const prop = defineProps({
    data: {
        type: Object,
        required: true
    }
})

const headers: Header[] = [
    { text: "", value: "team_badge" },
    { text: "", value: "team_name" },
    { text: "P", value: "played" },
    { text: "W", value: "won" },
    { text: "D", value: "draw" },
    { text: "L", value: "lose" },
    { text: "GD", value: "goal_diff" },
    { text: "Pts", value: "points" },
];

</script>

<style scoped>
@media screen and (max-width: 992px) {
    .card {
        font-size: 12px;
    }
}
</style>

<style></style>