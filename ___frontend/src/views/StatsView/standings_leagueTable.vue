<template>
    <div class="card rounded-0 mb-4">
        <div class="card-header fw-bolder">
            STANDINGS
        </div>
        <div v-if="data" class="card-body">
            <EasyDataTable class="border-0" :headers="headers" :items="data" show-index>
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

<style scoped></style>