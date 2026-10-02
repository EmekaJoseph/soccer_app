<script setup lang="ts">
import fx from '@/store/useFunctions'

withDefaults(defineProps<{
    badge?: string | null
    color?: string | null
    size?: number
}>(), { size: 28 })
</script>

<template>
    <img v-if="badge" :src="fx.resolvePhotoSrc(badge)" :width="size" :height="size" class="team-badge" alt="">
    <i v-else class="bi bi-shield-fill" :style="{ color: color || '#ccc', fontSize: `${size * 0.85}px` }"></i>
</template>

<style scoped>
.team-badge {
    object-fit: contain;
    border-radius: 6px;
}
</style>
