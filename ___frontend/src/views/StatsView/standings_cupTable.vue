<template>
    <div v-for="({ group, teams }, i) in prop.data" :key="i" class="card rounded-0 mb-4">
        <div class="card-header fw-bolder bg-white shadow-sm border-0">
            GROUP {{ group }}
        </div>
        <div v-if="teams" class="card-body p-1 py-3">
            <EasyDataTable table-class-name="easy-table-cup-league" class="border-0" :headers="headers" :items="teams"
                show-index>
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