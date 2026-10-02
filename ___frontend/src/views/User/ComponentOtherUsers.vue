<template>
    <div class="card shadow-sm h-100 border-0">
        <div class="card-header text-muted fw-bold bg-transparent border-0">
            SCORERS
            <div class="small fw-normal">Helpers who can schedule matches, record results and run live scores.</div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submitUser" class="row g-3">
                <div class="col-lg-12">
                    <input v-model="usersForm.email" type="email" class="form-control" placeholder="E-mail" required>
                </div>
                <div class="col-lg-12">
                    <input v-model="usersForm.password" type="text" class="form-control" placeholder="Password (min. 8 characters)" minlength="8" required>
                </div>
                <div v-if="usersForm.error" class="col-12 small text-danger">{{ usersForm.error }}</div>
                <div class="col-lg-12">
                    <button type="submit" :disabled="usersForm.isSaving" class="btn btn-primary-theme w-100">
                        {{ usersForm.isSaving ? 'Adding…' : 'Add scorer' }}
                    </button>
                </div>

                <div class="col-12 mt-4">
                    <div class="card" style="height: 200px; overflow-y: auto;">
                        <div class="card-body">
                            <table v-if="userData.subUsers.length" class="table table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, i) in userData.subUsers" :key="user.subuser_id">
                                        <th>{{ i + 1 }}</th>
                                        <td>{{ user.email }}</td>
                                        <td>
                                            <button type="button" class="btn-icon danger" title="Remove"
                                                @click="deleteUser(user)">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else class="text-center small">No scorers yet</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'

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
        userData.getSubUsers()
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>
