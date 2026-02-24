<template>
    <div v-for="({ group, teams }, i) in prop.data" :key="i" class="glass-card mb-5 overflow-hidden border-0">
        <div class="card-header-modern p-3 p-md-4 border-bottom border-white border-opacity-10">
            <h4 class="text-white fw-black mb-0 text-uppercase d-flex align-items-center">
                <i class="bi bi-grid-3x3-gap-fill me-2 text-gradient"></i>
                GROUP {{ group }}
            </h4>
        </div>
        <div v-if="teams" class="p-0">
            <EasyDataTable table-class-name="easy-table-cup-league" :headers="headers" :items="teams" show-index>

                <template #header-index="header">
                    <span>S/N</span>
                </template>

                <template #item-team_badge="item">
                    <div class="badge-mini">
                        <span v-if="item.badge" class="badge-text">{{ item.badge }}</span>
                        <i v-else :style="{ color: item.team_color || '#eee' }" class="bi bi-shield-fill"></i>
                    </div>
                </template>

                <template #item-team_name="item">
                    <div class="text-white fw-bold name-truncate"> {{ item.team_name }} </div>
                </template>

                <template #item-points="item">
                    <div class="text-gradient fw-black fs-5"> {{ item.points }} </div>
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
    { text: "", value: "team_badge", width: 40 },
    { text: "TEAM", value: "team_name" },
    { text: "P", value: "played", width: 40 },
    { text: "W", value: "won", width: 40 },
    { text: "D", value: "draw", width: 40 },
    { text: "L", value: "lose", width: 40 },
    { text: "GD", value: "goal_diff", width: 50 },
    { text: "PTS", value: "points", width: 60 },
];
</script>

<style scoped>
.badge-mini {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fw-black {
    font-weight: 900;
}

.name-truncate {
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 576px) {
    .name-truncate {
        max-width: 80px;
    }
}
</style>
