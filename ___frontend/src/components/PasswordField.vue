<script setup lang="ts">
import { computed, ref, watch } from 'vue'

defineProps<{
    id: string
    label: string
    autocomplete: string
    invalid?: boolean
    required?: boolean
    minlength?: number
}>()

const value = defineModel<string>({ default: '' })
const shown = ref(false)
const eyeLabel = computed(() => (shown.value ? 'Hide password' : 'Show password'))

// Never leave the next password on show after a form has been cleared.
watch(value, (current) => {
    if (!current) shown.value = false
})
</script>

<template>
    <div class="form-floating password-field">
        <input :id="id" v-model="value" :type="shown ? 'text' : 'password'" class="form-control" :class="{ 'is-invalid': invalid }"
            placeholder=" " :autocomplete="autocomplete" :required="required" :minlength="minlength">
        <label :for="id">{{ label }}</label>
        <!-- mousedown.prevent keeps the cursor in the field while the eye is clicked. -->
        <button type="button" class="eye" :title="eyeLabel" :aria-label="eyeLabel" :aria-pressed="shown"
            @mousedown.prevent @click="shown = !shown">
            <i :class="shown ? 'bi bi-eye' : 'bi bi-eye-slash'" aria-hidden="true"></i>
        </button>
    </div>
</template>

<style scoped>
.password-field > .form-control,
.password-field > .form-control.is-invalid {
    padding-right: 3rem;
    background-image: none;
}

.password-field > label {
    max-width: calc(100% - 2.75rem);
}

.eye {
    position: absolute;
    top: 50%;
    right: 0.5rem;
    z-index: 5;
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--fg-muted);
    transform: translateY(-50%);
    transition: background-color 0.15s ease, color 0.15s ease;
}

.eye:hover {
    background: var(--hover-tint);
    color: var(--fg);
}
</style>
