<template>
    <BaseModal title="Share your public page" @close="emit('close')">
        <div class="modal-body">
            <p class="small mb-2">Anyone with this link can follow your tournament.</p>
            <div class="link-box">{{ linkToCopy }}</div>
        </div>
        <div class="modal-footer">
            <button v-if="!copied" type="button" class="ui-btn ui-btn-primary ui-btn-lg w-100" @click="copy(linkToCopy)">
                <i class="bi bi-clipboard"></i> Copy link
            </button>
            <button v-else type="button" class="ui-btn ui-btn-ghost ui-btn-lg w-100">
                <i class="bi bi-check-lg"></i> Copied
            </button>
        </div>
    </BaseModal>
</template>

<script setup lang="ts">
import { useClipboard } from '@vueuse/core'

const { copy, copied } = useClipboard({ legacy: true })

const emit = defineEmits(['close'])
defineProps(['linkToCopy'])
</script>

<style scoped>
.link-box {
    padding: 0.75rem 0.85rem;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius-sm);
    background: var(--bg);
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 0.8125rem;
    overflow-wrap: anywhere;
}
</style>
