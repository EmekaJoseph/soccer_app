<script setup lang="ts">
defineProps<{ title?: string; count?: number; hint?: string }>()
</script>

<template>
    <section class="card">
        <header class="card-header">
            <slot name="title">{{ title }}</slot>
            <span v-if="count != null" class="count">{{ count }}</span>
            <div v-if="$slots.actions" class="ms-auto d-flex flex-wrap align-items-center gap-2">
                <slot name="actions" />
            </div>
            <p v-if="hint" class="card-hint">{{ hint }}</p>
        </header>
        <div class="card-body">
            <slot />
        </div>
    </section>
</template>
