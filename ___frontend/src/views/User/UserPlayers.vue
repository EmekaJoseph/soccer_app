<template>
    <div class="container px-3">
        <componentLoadingSpinner v-if="loading" />
        <internetErrorComponent v-else-if="userData.apiError" />
        <div v-else class="row gy-4">
            <tourDropdownSelect v-model="selectedTournament" @change="changed" />

            <div v-if="!selectedTournament" class="col-12">
                <emptyDataComponent>Create a tournament on the dashboard first.</emptyDataComponent>
            </div>
            <div v-else-if="!userData.tournamentTeams.length" class="col-12">
                <emptyDataComponent>Add teams to this tournament before adding players.</emptyDataComponent>
            </div>

            <template v-else>
                <div class="col-lg-4">
                    <div class="card border-0">
                        <div class="card-header text-muted bg-transparent border-0">
                            {{ form.player_id ? 'EDIT PLAYER' : 'ADD PLAYER' }}
                            <button v-if="form.player_id" class="btn btn-sm btn-link float-end p-0" @click="resetForm">Cancel</button>
                        </div>
                        <div class="card-body">
                            <form :key="formKey" class="row g-3" @submit.prevent="save">
                                <div class="col-6">
                                    <label class="w-100">First name
                                        <input v-model.trim="form.first_name" class="form-control" required>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="w-100">Last name
                                        <input v-model.trim="form.last_name" class="form-control" required>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <label class="w-100">Team
                                        <select v-model="form.team_id" class="form-select" required>
                                            <option value="" disabled>-- select --</option>
                                            <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="w-100">Date of birth
                                        <input v-model="form.dob" type="date" class="form-control" :max="today">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="w-100">Position / note
                                        <input v-model="form.info" class="form-control" maxlength="50" placeholder="e.g. Goalkeeper">
                                    </label>
                                </div>
                                <div class="col-12">
                                    <ImagePicker v-model="form.image" :current="form.currentImage" label="Photo" :size="60" />
                                </div>
                                <div v-if="formError" class="col-12 small text-danger">{{ formError }}</div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary-theme w-100" :disabled="isSaving">
                                        {{ isSaving ? 'Saving…' : form.player_id ? 'Update player' : 'Add player' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 h-100">
                        <div class="card-header text-muted bg-transparent border-0 d-flex flex-wrap gap-2 align-items-center">
                            <span class="flex-grow-1">PLAYERS ({{ filteredPlayers.length }})</span>
                            <select v-model="teamFilter" class="form-select form-select-sm w-auto">
                                <option value="">All teams</option>
                                <option v-for="t in userData.tournamentTeams" :key="t.team_id" :value="t.team_id">{{ t.team_name }}</option>
                            </select>
                            <input v-model="search" class="form-control form-control-sm w-auto" placeholder="Search name…">
                        </div>
                        <div class="card-body">
                            <EasyDataTable :headers="headers" :items="filteredPlayers" :loading="playersLoading" class="border-0">
                                <template #item-name="item">
                                    <div class="d-flex align-items-center gap-2 py-1">
                                        <img v-if="item.image" :src="fx.resolvePhotoSrc(item.image)" class="rounded-circle" width="32" height="32" alt="">
                                        <i v-else class="bi bi-person-circle fs-4 text-muted"></i>
                                        {{ item.first_name }} {{ item.last_name }}
                                    </div>
                                </template>
                                <template #item-team="item">{{ item.team?.team_name }}</template>
                                <template #item-age="item">{{ age(item.dob) }}</template>
                                <template #item-actions="item">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn-icon" title="Edit" @click="edit(item)"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-icon danger" title="Delete" @click="remove(item)"><i class="bi bi-trash3"></i></button>
                                    </div>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import type { Header } from 'vue3-easy-data-table'
import { useUserDataStore } from '@/store/userDataStore'
import api, { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useTournamentPicker } from '@/composables/useTournamentPicker'
import ImagePicker from '@/components/ImagePicker.vue'

const userData = useUserDataStore()
const playersLoading = ref(true)
const isSaving = ref(false)
const formError = ref('')
const teamFilter = ref('')
const search = ref('')
const today = new Date().toISOString().slice(0, 10)

const headers: Header[] = [
    { text: 'NAME', value: 'name' },
    { text: 'TEAM', value: 'team' },
    { text: 'POSITION', value: 'info' },
    { text: 'AGE', value: 'age' },
    { text: '', value: 'actions' },
]

const emptyForm = () => ({
    player_id: '',
    first_name: '',
    last_name: '',
    team_id: '',
    dob: '',
    info: '',
    image: null as File | null,
    currentImage: null as string | null,
})
const form = reactive(emptyForm())
const formKey = ref(0) // remounts the form (and image preview) on reset/edit

const { selectedTournament, changed, loading } = useTournamentPicker(async (tourId) => {
    playersLoading.value = true
    resetForm()
    await Promise.all([userData.getTournamentTeams(tourId), userData.getPlayers(tourId)])
    playersLoading.value = false
})

const filteredPlayers = computed(() => {
    const term = search.value.trim().toLowerCase()
    return userData.tournamentPlayers.filter((p) =>
        (!teamFilter.value || p.team_id === teamFilter.value)
        && (!term || `${p.first_name} ${p.last_name}`.toLowerCase().includes(term)),
    )
})

function age(dob?: string | null) {
    if (!dob) return '—'
    const years = Math.floor((Date.now() - new Date(dob).getTime()) / (365.25 * 24 * 3600 * 1000))
    return Number.isFinite(years) ? years : '—'
}

function resetForm() {
    Object.assign(form, emptyForm())
    formError.value = ''
    formKey.value++
}

function edit(player: any) {
    formKey.value++
    Object.assign(form, {
        ...emptyForm(),
        player_id: player.player_id,
        first_name: player.first_name,
        last_name: player.last_name,
        team_id: player.team_id,
        dob: player.dob ?? '',
        info: player.info ?? '',
        currentImage: player.image,
    })
}

async function save() {
    formError.value = ''
    isSaving.value = true
    const { player_id, currentImage, ...fields } = form

    try {
        if (player_id) await api.updatePlayer(player_id, fields)
        else await api.createPlayer(fields)
        fx.toast.success(player_id ? 'Player updated' : 'Player added')
        resetForm()
        await userData.getPlayers(selectedTournament.value.tour_id)
    } catch (error) {
        formError.value = apiErrorMessage(error)
    } finally {
        isSaving.value = false
    }
}

async function remove(player: any) {
    const tap = await fx.confirmDelete(`Remove ${player.first_name} ${player.last_name}?`, 'Remove')
    if (!tap.isConfirmed) return

    try {
        await api.deletePlayer(player.player_id)
        if (form.player_id === player.player_id) resetForm()
        await userData.getPlayers(selectedTournament.value.tour_id)
    } catch (error) {
        fx.toast.error(apiErrorMessage(error))
    }
}
</script>
