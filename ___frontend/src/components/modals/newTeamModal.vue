<template>
    <div>
        <div class="modal fade" id="newTeamModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-sm">
                <div class="modal-content">
                    <div class="modal-header border-0 bg-light">
                        <span class="fw-bold">New Team:</span>
                        <span class="float-end">
                            <button ref="btnX" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </span>
                    </div>
                    <div class="modal-body p-sm-4">
                        <div class="row justify-content-center gy-3">
                            <div v-if="!userData.tournaments.length">
                                Add a tournament first
                            </div>
                            <div v-else class="col-md-12 ">
                                <form class="row g-3">
                                    <div class="col-md-12">
                                        <label>Tournament: </label>
                                        <select v-model="form.selectedTournament"
                                            class="form-select bg-light-subtle text-uppercase">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in userData.tournaments" :key="i" :value="i.id">{{ i.title }}
                                            </option>
                                        </select>
                                    </div>

                                    <div v-if="current_type() == 'cup'" class="col-md-12">
                                        <label>Group: </label>
                                        <select v-model="form.group_in" class="form-select bg-light-subtle text-uppercase">
                                            <option value="" selected disabled></option>
                                            <option v-for="i in valid_groups" :key="i" :value="i">{{ i }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Team Name:</label>
                                        <input v-model="form.team_name" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label>About Team (optional):</label>
                                        <textarea v-model="form.team_brief" rows="4" class="form-control"></textarea>
                                    </div>


                                    <div class="col-md-12 mt-3">
                                        <button v-if="!isSaving" @click.prevent="save"
                                            class="btn btn-primary btn w-100">Save</button>
                                        <button v-else class="float-end theme-btn btn w-100" disabled>Saving...</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'


const userData = useUserDataStore()
const isSaving = ref(false)
const emit = defineEmits(['done'])

const form = reactive({
    selectedTournament: '',
    team_name: '',
    team_brief: ' ',
    group_in: 'A'
})

const current_type = () => {
    let tour = userData.tournaments.find((x: { id: string; }) => x.id == form.selectedTournament);
    return tour ? tour.type : '';
}

const valid_groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']

async function save() {
    if (!form.selectedTournament) {
        alert('select tournament')
        return;
    }

    if (!form.team_name) {
        alert('empty team name')
        return;
    }

    if (current_type() == 'cup') {
        if (!form.group_in) {
            alert('select group')
            return;
        }
    }

    let obj = {};
    obj.team_name = form.team_name;
    obj.team_brief = form.team_brief;
    obj.tour_id = form.selectedTournament;
    obj.group_in = current_type() == 'cup' ? form.group_in : null

    isSaving.value = true

    try {
        let resp = await api.createTeam(obj)
        if (resp.status == 203) {
            alert('Name already exists')
            isSaving.value = false
            return;
        }

        emit('done')
        isSaving.value = false
        btnX.value.click()
        form.team_name = "";
        form.team_brief = "";
        form.selectedTournament = "";
        form.group_in = "";
    } catch (error) {
        alert('internet error')
        isSaving.value = false
    }

}


const btnX: any = ref(null)
onBeforeRouteLeave(() => {
    btnX.value.click()
})
</script>

<style scoped></style>

