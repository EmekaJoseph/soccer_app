<script setup lang="ts">
import { computed } from 'vue'
import { useTemplateStore } from '@/store/templateStore'

const template = useTemplateStore()
const label = computed(() => (template.appTheme == 'dark' ? 'Switch to light mode' : 'Switch to dark mode'))
</script>

<template>
    <button type="button" class="ui-btn ui-btn-ghost theme-toggle" :title="label" :aria-label="label" @click="template.toggleTheme()">
        <i :class="template.appTheme == 'dark' ? 'bi bi-sun' : 'bi bi-moon'" aria-hidden="true"></i>
    </button>
</template>

<style scoped>
.theme-toggle {
    flex: none;
    width: 38px;
    padding-inline: 0;
}
</style>
