<template>
    <div class="d-flex flex-column min-vh-100">
        <div class="flex-grow-1">
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
</style>
