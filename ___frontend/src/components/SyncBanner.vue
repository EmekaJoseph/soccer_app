<script setup lang="ts">
/** Says the data on screen may be out of date, without taking that data away. */
defineProps<{ busy?: boolean }>()
const emit = defineEmits<{ retry: [] }>()
</script>

<template>
    <div class="sync-banner" role="status">
        <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
        <span class="sync-text"><slot /></span>
        <button type="button" class="ui-btn ui-btn-ghost ui-btn-sm" :disabled="busy" @click="emit('retry')">
            {{ busy ? 'Retrying…' : 'Retry now' }}
        </button>
    </div>
</template>

<style scoped>
.sync-banner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem 0.75rem;
    margin-bottom: 1.25rem;
    padding: 0.65rem 0.9rem;
    border: 1px solid var(--danger-border);
    border-radius: var(--radius-sm);
    background: var(--danger-soft);
    color: var(--fg);
    font-size: 0.875rem;
}

.sync-banner > i {
    color: var(--danger);
}

.sync-text {
    flex: 1 1 200px;
}
</style>
