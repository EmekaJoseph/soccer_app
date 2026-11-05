<template>
    <AdminCard title="Scorers" :count="userData.subUsers.length" class="h-100"
        hint="Helpers who can schedule matches, record results and run live scores.">
        <form class="row g-3" @submit.prevent="submitUser">
            <div class="col-12">
                <div class="form-floating">
                    <input id="scorerEmail" v-model="usersForm.email" type="email" class="form-control" placeholder=" " autocomplete="off" required>
                    <label for="scorerEmail">Scorer's e-mail</label>
                </div>
            </div>
            <div class="col-12">
                <PasswordField id="scorerPassword" v-model="usersForm.password" label="Password (min. 8 characters)" autocomplete="new-password" :minlength="8" required />
            </div>
            <div v-if="usersForm.error" class="col-12 small text-danger">{{ usersForm.error }}</div>
            <div class="col-12">
                <button type="submit" :disabled="usersForm.isSaving" class="ui-btn ui-btn-primary w-100">
                    {{ usersForm.isSaving ? 'Adding…' : 'Add scorer' }}
                </button>
            </div>
        </form>

        <ul v-if="userData.subUsers.length" class="scorers">
            <li v-for="user in userData.subUsers" :key="user.subuser_id">
                <span class="scorer-email">{{ user.email }}</span>
                <button type="button" class="btn-icon danger" title="Remove" :aria-label="`Remove ${user.email}`" @click="deleteUser(user)">
                    <i class="bi bi-x-lg"></i>
                </button>
            </li>
        </ul>
        <p v-else class="no-scorers">No scorers yet</p>
    </AdminCard>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import PasswordField from '@/components/PasswordField.vue'

const userData = useUserDataStore()

onMounted(() => userData.getSubUsers())

const usersForm = reactive({ email: '', password: '', isSaving: false, error: '' })

async function submitUser() {
    usersForm.error = ''
    usersForm.isSaving = true
    try {
        await api.createSubUser({ email: usersForm.email, password: usersForm.password })
        fx.toast.success('Scorer added')
        usersForm.email = ''
        usersForm.password = ''
        userData.getSubUsers()
    } catch (error) {
        usersForm.error = apiErrorMessage(error)
    } finally {
        usersForm.isSaving = false
    }
}

async function deleteUser(user: any) {
    const tap = await fx.confirmDelete(`Remove ${user.email}?`, 'Remove')
    if (!tap.isConfirmed) return

    try {
        await api.deleteSubUser(user.subuser_id)
        fx.toast.info('Scorer removed')
        userData.getSubUsers()
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>

<style scoped>
.scorers {
    max-height: 200px;
    margin: 1.25rem 0 0;
    padding: 0;
    overflow-y: auto;
    list-style: none;
}

.scorers li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.5rem 0;
    border-top: 1px solid var(--line);
}

.scorer-email {
    min-width: 0;
    color: var(--fg);
    font-size: 0.875rem;
    overflow-wrap: anywhere;
}

.no-scorers {
    margin: 1.25rem 0 0;
    font-size: 0.875rem;
    text-align: center;
}
</style>
