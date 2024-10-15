<template>
    <main class="main general-body">
        <ComponentHero />
        <ComponentInfo />
        <ComponentFeatures />
    </main>
</template>

<script setup lang="ts">
import ComponentFeatures from './componentFeatures.vue';
import ComponentHero from './componentHero.vue';
import ComponentInfo from './componentInfo.vue';
</script>

<style lang="css" scoped>
section,
.section {
    /* color: var(--default-color); */
    /* background-color: var(--background-color); */
    padding: 60px 0;
    scroll-margin-top: 100px;
    overflow: clip;
}

@media (max-width: 1199px) {

    section,
    .section {
        scroll-margin-top: 66px;
    }
}
</style>
