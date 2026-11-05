<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue'

/** Dialog frame. Content goes in `.modal-body` and, optionally, `.modal-footer` children. */
defineProps<{ title: string }>()
const emit = defineEmits<{ close: [] }>()

const root = ref<HTMLElement | null>(null)
const previouslyFocused = document.activeElement as HTMLElement | null
const previousOverflow = document.body.style.overflow

const FOCUSABLE = 'a[href], button:not(:disabled), input:not(:disabled), select:not(:disabled), textarea:not(:disabled)'

onMounted(() => {
    document.body.style.overflow = 'hidden'
    // Skip the first field on touch screens, where focusing it would throw up the keyboard.
    const field = window.matchMedia('(pointer: fine)').matches
        ? root.value?.querySelector<HTMLElement>('.modal-body input:not(:disabled), .modal-body select:not(:disabled), .modal-body textarea:not(:disabled)')
        : null
    ;(field ?? root.value)?.focus()
})

onBeforeUnmount(() => {
    document.body.style.overflow = previousOverflow
    previouslyFocused?.focus?.()
})

/** Keeps Tab inside the dialog. */
function trapTab(event: KeyboardEvent) {
    const items = [...(root.value?.querySelectorAll<HTMLElement>(FOCUSABLE) ?? [])]
    const first = items[0]
    const last = items[items.length - 1]
    if (!first || !last) return

    if (event.shiftKey && (document.activeElement === first || document.activeElement === root.value)) {
        event.preventDefault()
        last.focus()
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault()
        first.focus()
    }
}
</script>

<template>
    <div ref="root" class="modal fade bg-faint show d-block" tabindex="-1" role="dialog" aria-modal="true" :aria-label="title"
        @keydown.esc="emit('close')" @keydown.tab="trapTab">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animate__animated animate__fadeIn animate__faster">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ title }}</h2>
                    <button type="button" class="btn-close" aria-label="Close" @click="emit('close')"></button>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
