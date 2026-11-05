<template>
    <div class="d-flex flex-column min-vh-100">
        <div class="flex-grow-1 layout-body">
            <RouterView />
        </div>
        <footerVue />
    </div>
</template>

<script setup lang="ts">
import footerVue from '@/components/Footer.vue';
</script>

<style scoped>
.min-vh-100 {
    min-height: 100vh;
}

/* Keeps short pages from showing a white band above the footer. */
.layout-body {
    background: var(--bg);
}
</style>
