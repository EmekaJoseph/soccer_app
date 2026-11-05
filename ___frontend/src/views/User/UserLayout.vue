<template>
    <div class="admin-shell">
        <ComponentSideBar />
        <ComponentHeader />
        <main class="main-content">
            <RouterView v-slot="{ Component, route }">
                <Transition name="page" mode="out-in">
                    <!-- out-in needs one root element; pages with several would leave the view stuck blank. -->
                    <div :key="route.path">
                        <component :is="Component" />
                    </div>
                </Transition>
            </RouterView>
        </main>
    </div>
</template>

<script setup lang="ts">
import ComponentSideBar from './ComponentSideBar.vue'
import ComponentHeader from './ComponentHeader.vue'
</script>

<style scoped>
.page-enter-active,
.page-leave-active {
    transition: opacity 0.15s ease;
}

.page-enter-from,
.page-leave-to {
    opacity: 0;
}
</style>
