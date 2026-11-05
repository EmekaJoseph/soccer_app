<template>
    <div class="col-12">
        <div class="tour-picker">
            <label class="picker">
                <span class="picker-label">Tournament</span>
                <select v-model="selectedTournament" class="form-select" :disabled="!userData.tournaments.length"
                    @change="emit('change')">
                    <option v-if="!userData.tournaments.length" :value="undefined">No tournaments yet</option>
                    <option v-for="t in userData.tournaments" :key="t.tour_id" :value="t">{{ t.title }}</option>
                </select>
            </label>
            <span v-if="selectedTournament" class="tag type-tag">{{ selectedTournament.type }}</span>
            <a v-if="selectedTournament" class="public-link" :href="`/stats/${selectedTournament.tour_id}`" target="_blank" rel="noopener">
                View public page <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
            </a>
            <div v-if="$slots.default" class="picker-actions">
                <slot />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useUserDataStore } from '@/store/userDataStore'

const userData = useUserDataStore()
const selectedTournament = defineModel<any>()
const emit = defineEmits<{ change: [] }>()
</script>

<style scoped>
.tour-picker {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 0.75rem;
}

.picker {
    flex: 0 1 340px;
    min-width: 0;
}

.picker-label {
    display: block;
    margin-bottom: 0.35rem;
    font-size: 0.8125rem;
}

.picker .form-select {
    margin: 0;
    font-weight: 500;
}

.type-tag {
    margin-bottom: 0.65rem;
    text-transform: capitalize;
}

.public-link {
    margin-bottom: 0.65rem;
    font-size: 0.8125rem;
    text-decoration: none;
    white-space: nowrap;
}

.public-link:hover {
    text-decoration: underline;
}

.picker-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-left: auto;
}
</style>
