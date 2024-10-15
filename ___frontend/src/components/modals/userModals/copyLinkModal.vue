<template>
    <div class="modal fade bg-faint show d-block " id="copyLinkModal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered animate__animated animate__slideInDown animate__faster"
            role="document">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalTitleId">

                    </h5>
                    <button @click="emit('close')" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <div class="card bg-warning-subtle fw-bold p-3 border-0 text-muted">{{ linkToCopy }}</div>

                </div>
                <div class="modal-footer border-0">
                    <button @click="copy(linkToCopy)" v-if="!copied" type="button" class="btn btn-primary-theme w-100">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>

                    <button v-else type="button" class="btn btn-light bg-success-subtle w-100">
                        <i class="bi bi-check-lg"></i> Copied
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>


<script setup lang="ts">
import { useClipboard } from '@vueuse/core'

const { copy, isSupported, copied } = useClipboard()

const emit = defineEmits(['close'])
defineProps(['linkToCopy'])
</script>