<script setup lang="ts">
import { useUserDataStore } from '@/store/userDataStore'

/** Frame of every page that works on one tournament: loading, errors, the picker and "no tournament yet". */
defineProps<{ loading: boolean }>()
const selected = defineModel<any>()
const emit = defineEmits<{ change: [] }>()

const userData = useUserDataStore()
</script>

<template>
    <componentLoadingSpinner v-if="loading" />
    <internetErrorComponent v-else-if="userData.pageError" />
    <div v-else class="row g-4">
        <tourDropdownSelect v-model="selected" @change="emit('change')">
            <slot v-if="selected" name="actions" />
        </tourDropdownSelect>

        <div class="col-12">
            <!-- A failed refresh keeps what is on screen rather than replacing the page. -->
            <SyncBanner v-if="userData.apiError" @retry="emit('change')">
                Couldn't refresh this page, so it may be out of date.
            </SyncBanner>

            <emptyDataComponent v-if="!selected" icon="bi bi-trophy">
                You need a tournament before you can use this page.
                <div class="mt-3">
                    <RouterLink to="/user/dashboard" class="ui-btn ui-btn-primary">Go to Dashboard</RouterLink>
                </div>
            </emptyDataComponent>
            <slot v-else />
        </div>
    </div>
</template>
