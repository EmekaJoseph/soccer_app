<script lang="ts" setup>
import { useUserDataStore } from '@/store/userDataStore'

const userData = useUserDataStore()
const selectedTournament = defineModel<any>()
const emit = defineEmits<{ change: [] }>()
</script>

<template>
    <div class="col-12">
        <div class="tour-picker">
            <span class="picker-icon"><i class="bi bi-trophy"></i></span>
            <label class="flex-grow-1 mb-0">
                <span class="picker-label">Tournament</span>
                <select v-model="selectedTournament" class="form-select" :disabled="!userData.tournaments.length"
                    @change="emit('change')">
                    <option v-if="!userData.tournaments.length" :value="undefined">No tournaments yet</option>
                    <option v-for="t in userData.tournaments" :key="t.tour_id" :value="t">{{ t.title }}</option>
                </select>
            </label>
            <span v-if="selectedTournament" class="badge type-badge" :class="selectedTournament.type">
                {{ selectedTournament.type }}
            </span>
            <slot />
        </div>
    </div>
</template>

<style scoped>
.tour-picker {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    flex-wrap: wrap;
    padding: 0.75rem 1rem;
    background: var(--surface-card);
    border: 1px solid var(--surface-border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-md);
}

.picker-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e6f4fd;
    color: #1f8fd1;
    font-size: 1.1rem;
}

.picker-label {
    display: block;
    font-size: 0.68rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--text-muted);
}

.tour-picker .form-select {
    border: 0 !important;
    background-color: transparent !important;
    padding: 0.1rem 2rem 0.1rem 0 !important;
    margin: 0 !important;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.05rem;
    color: var(--text-strong);
    box-shadow: none !important;
    max-width: 480px;
}

.type-badge {
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-size: 0.7rem;
}

.type-badge.cup {
    background: #fff4e0;
    color: #b86e00;
}

.type-badge.league {
    background: #efeafe;
    color: #6d4ae0;
}
</style>
