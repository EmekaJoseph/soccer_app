<template>
    <main class="public-page" data-bs-theme="dark">
        <ComponentNav />
        <ComponentHero />
        <ComponentSteps />
        <ComponentFeatures />
        <ComponentCta />
    </main>
</template>

<script setup lang="ts">
import ComponentCta from './componentCta.vue';
import ComponentFeatures from './componentFeatures.vue';
import ComponentHero from './componentHero.vue';
import ComponentNav from './componentNav.vue';
import ComponentSteps from './componentSteps.vue';
</script>
