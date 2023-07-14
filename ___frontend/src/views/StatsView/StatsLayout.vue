<template>
    <div v-if="stats.apiLoading" class="d-flex justify-content-center align-items-center mt-5">
        <div style="width: 3rem; height: 3rem;" class="text-secondary spinner-border spinner-border-sm"> </div>
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

<style scoped></style>