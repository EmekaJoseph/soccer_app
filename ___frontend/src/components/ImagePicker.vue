<template>
    <div>
        <div class="mb-2 small">{{ label }}</div>
        <label class="image-circle cursor-pointer" :style="{ width: `${size}px`, height: `${size}px`, backgroundImage: src ? `url(${src})` : 'none' }">
            <i v-if="!src" class="bi bi-camera"></i>
            <input type="file" accept="image/*" class="visually-hidden" :aria-label="label" @change="pick">
        </label>
        <div v-if="error" class="small text-danger mt-1">{{ error }}</div>
    </div>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, ref } from 'vue'
import fx from '@/store/useFunctions'

const props = withDefaults(defineProps<{
    /** Path of the image already saved on the server, if any. */
    current?: string | null
    label?: string
    size?: number
}>(), { label: 'Image', size: 70 })

const file = defineModel<File | null>({ default: null })
const error = ref('')
const previewUrl = ref('')

const src = computed(() => previewUrl.value || fx.resolvePhotoSrc(props.current))

function pick(event: Event) {
    const chosen = (event.target as HTMLInputElement).files?.[0]
    error.value = ''
    if (!chosen) return

    if (!chosen.type.startsWith('image/')) {
        error.value = 'Please choose an image file.'
        return
    }
    if (chosen.size > 4 * 1024 * 1024) {
        error.value = 'Image must be smaller than 4 MB.'
        return
    }

    URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = URL.createObjectURL(chosen)
    file.value = chosen
}

onBeforeUnmount(() => URL.revokeObjectURL(previewUrl.value))
</script>

<style scoped>
.image-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px dashed var(--line-strong);
    border-radius: 50%;
    background-color: var(--bg);
    background-position: center;
    background-size: cover;
    color: var(--fg-muted);
    transition: border-color 0.15s ease;
}

.image-circle:hover,
.image-circle:focus-within {
    border-color: var(--accent);
}
</style>
