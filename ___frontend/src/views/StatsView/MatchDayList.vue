<script setup lang="ts" generic="T">
import { computed } from 'vue'
import { groupByMatchDay } from './matchDates'

const props = defineProps<{ items: T[]; dateOf: (item: T) => string | null | undefined; keyOf: (item: T) => string | number }>()

const groups = computed(() => groupByMatchDay(props.items, props.dateOf))
</script>

<template>
    <section v-for="group in groups" :key="group.label" class="day">
        <h2 class="day-label">{{ group.label }}</h2>
        <div class="day-card">
            <div v-for="item in group.items" :key="keyOf(item)" class="day-row">
                <slot :item="item" />
            </div>
        </div>
    </section>
</template>

<style scoped>
.day + .day {
    margin-top: 28px;
}

.public-page .day-label {
    margin-bottom: 10px;
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.day-card {
    border: 1px solid var(--line);
    border-radius: 14px;
    background: var(--ink-900);
}

.day-row + .day-row {
    border-top: 1px solid var(--line);
}
</style>
