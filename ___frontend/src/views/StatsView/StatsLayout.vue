<template>
    <div v-if="stats.apiLoading"
        class="loading-state d-flex flex-column justify-content-center align-items-center mt-5">
        <div class="spinner-modern mb-3"></div>
        <div class="text-white-50 small caps-ls-1">Syncing Live Stats...</div>
    </div>
    <div v-else>
        <div v-if="stats.apiError" class="mt-5">
            <internetErrorComponent />
        </div>
        <div v-else>
            <slot></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useStatsStore } from '@/store/statsStore';
const stats = useStatsStore();
</script>

<style scoped>
.loading-state {
    min-height: 200px;
}

.spinner-modern {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    border-top-color: var(--accent-color);
    animation: spin 1s ease-in-out infinite;
    filter: drop-shadow(0 0 10px rgba(0, 242, 254, 0.4));
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.caps-ls-1 {
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 10px;
}
</style>