<template>
    <div class="sidebar">
        <ComponentMenuList />
    </div>
</template>

<script setup lang="ts">
import ComponentMenuList from './ComponentMenuList.vue';

</script>

<style scoped>
.sidebar {
    background-color: var(--theme-color-7) !important;
}
</style>