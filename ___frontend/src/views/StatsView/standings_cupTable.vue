<template>
    <div v-for="({ group, teams }, i) in prop.data" :key="i" class="card rounded-0 mb-4">
        <div class="card-header fw-bolder">
            GROUP {{ group }}
        </div>
        <div v-if="teams" class="card-body">
            <EasyDataTable class="border-0" :headers="headers" :items="teams" show-index>
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

<style>
.vue3-easy-data-table__footer {
    display: none !important;
    margin: 0px !important;
}

.vue3-easy-data-table__main {
    min-height: auto !important;
}

.vue3-easy-data-table__body td {
    color: #000 !important;
    font-size: 14px !important;
    /* font-weight: bolder; */
}

.vue3-easy-data-table__header th {

    color: #a5a0a0 !important;
}
</style>