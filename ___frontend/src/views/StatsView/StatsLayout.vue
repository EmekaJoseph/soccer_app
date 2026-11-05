<template>
    <div v-if="stats.apiLoading" class="loading" role="status">
        <div class="spinner"></div>
        Loading tournament…
    </div>
    <emptyDataComponent v-else-if="stats.notFound">
        This tournament doesn't exist anymore. Check the link you were given.
    </emptyDataComponent>
    <internetErrorComponent v-else-if="stats.apiError" />
    <slot v-else />
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore'

const stats = useStatsStore()
</script>

<style scoped>
.loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    padding-block: 72px;
    font-size: 0.875rem;
}

.spinner {
    width: 32px;
    height: 32px;
    border: 2px solid var(--line-strong);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
