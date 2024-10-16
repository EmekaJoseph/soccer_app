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
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 250px !important;
    /* border-right: 1px solid v-bind('templateStore.borderInDark') !important; */
    background-color: var(--theme-color-7) !important;
    overflow-y: auto;
    padding-right: 0px;
    padding-top: 100px;
}

@media (max-width: 767px) {
    .sidebar {
        display: none;
    }
}
</style>