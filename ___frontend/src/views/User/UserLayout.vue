<template>
    <div class="admin-shell">
        <ComponentSideBar />
        <ComponentHeader />
        <main class="main-content">
            <RouterView v-slot="{ Component }">
                <Transition name="page" mode="out-in">
                    <component :is="Component" />
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
    transition: opacity 0.18s ease, transform 0.18s ease;
}

.page-enter-from {
    opacity: 0;
    transform: translateY(6px);
}

.page-leave-to {
    opacity: 0;
}
</style>
