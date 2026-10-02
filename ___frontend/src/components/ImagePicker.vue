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

<template>
    <div>
        <div class="mb-1">{{ label }}:</div>
        <label class="image-circle cursor-pointer" :style="{ width: `${size}px`, height: `${size}px`, backgroundImage: src ? `url(${src})` : 'none' }">
            <i v-if="!src" class="bi bi-camera text-muted"></i>
            <input type="file" accept="image/*" class="d-none" @change="pick">
        </label>
        <div v-if="error" class="small text-danger mt-1">{{ error }}</div>
    </div>
</template>

<style scoped>
.image-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: var(--bs-light-bg-subtle);
    border: 1px solid #e8e5e5;
    background-size: cover;
    background-position: center;
}

.image-circle:hover {
    border-color: #41b883;
}
</style>
