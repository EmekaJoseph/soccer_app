<script setup lang="ts">
withDefaults(defineProps<{ icon?: string }>(), { icon: 'bi bi-inbox' })
</script>

<template>
    <div class="empty-state">
        <div class="empty-icon"><i :class="icon"></i></div>
        <div class="empty-text"><slot /></div>
    </div>
</template>

<style scoped>
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 0.75rem;
    padding: 2.5rem 1rem;
    color: inherit;
}

.empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    background: rgba(125, 140, 155, 0.12);
    opacity: 0.85;
}

.empty-text {
    max-width: 360px;
    font-size: 0.95rem;
    opacity: 0.85;
}
</style>
