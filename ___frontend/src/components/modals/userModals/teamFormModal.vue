<template>

    <button type="button" ref="openModalBtn" class="d-none" data-bs-toggle="modal" data-bs-target="#teamFormModal">
    </button>

    <div class="modal fade" id="teamFormModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollabl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title " id="modalTitleId">
                        Team Modal
                    </h5>
                    <button ref="closeModalBtn" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div v-if="selectedTournament.type == 'cup'" class="col-md-12 col-lg-5">
                            <div class="form-floating">
                                <select id="fo4444" v-model="form.group_in" class="form-select  text-uppercase">
                                    <option value="" selected disabled>--select--</option>
                                    <option v-for="i in userData.valid_groups" :key="i" :value="i">
                                        {{ i }}
                                    </option>
                                </select>
                                <label for="fo4444">Group</label>
                            </div>

                        </div>

                        <div class="col-md-12 col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <span class="form-label me-3">Team's color: </span>
                                    <span class="float-end">
                                        <ColorPicker v-model:pureColor="form.team_color" format="hex" shape="square"
                                            blur-close disable-alpha />
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input v-model="form.team_name" type="text" class="form-control" id="formId1xaxa"
                                    placeholder="" />
                                <label for="formId1xaxa">Team Name:</label>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input v-model="form.manager" type="text" class="form-control" id="formId1xaxaasasa"
                                    placeholder="" />
                                <label for="formId1xaxaasasa">Team Manager's Name:</label>
                            </div>

                        </div>


                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea id="team_brief" v-model="form.team_brief" style="height: 150px;"
                                    class="form-control" placeholder=""></textarea>
                                <label for="team_brief">About Team (optional):</label>
                            </div>
                        </div>

                        <div v-if="userData.tournaments.length" class="col-md-12 mt-3">
                            <button v-if="!form.isSaving" @click.prevent="save"
                                class="btn btn-primary-theme btn w-100">Save</button>
                            <button v-else class="btn btn-primary-theme w-100" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Saving...
                            </button>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div> -->
            </div>
        </div>
    </div>

</template>
<script setup lang="ts">
import { reactive, ref, watch } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import { useToast } from 'vue-toast-notification';

import { useUserDataStore } from '@/store/userDataStore';
import api from '@/store/axiosManager'

const props = defineProps<{
    toggleModal: boolean;
    selectedTournament: any
}>();

const emit = defineEmits(['done'])

const userData = useUserDataStore()
const openModalBtn = ref<any>(null)
const closeModalBtn = ref<any>(null)
const $toast = useToast();



watch(() => props.toggleModal, () => {
    openModalBtn.value?.click()
})

onBeforeRouteLeave(() => {
    closeModalBtn.value?.click()
})





// form
const form = reactive({
    team_name: '',
    manager: '',
    team_brief: ' ',
    team_color: '#ff0000',
    group_in: 'A',
    isSaving: false
})

function resetForm() {
    form.team_name = '';
    form.manager = '';
    form.team_brief = ' ';
    form.team_color = '#ff0000';
    // form.group_in= 'A';
}





async function save() {

    if (!form.team_name) {
        $toast.default('Enter Team Name', { position: 'top-right' });
        return;
    }

    if (!form.manager) {
        $toast.default('Enter Manager', { position: 'top-right' });
        return;
    }

    if (props.selectedTournament.type == 'cup') {
        if (!form.group_in) {
            $toast.default('Select Group', { position: 'top-right' });
            return;
        }
    }

    let obj: any = {};
    obj.team_name = form.team_name;
    obj.manager = form.manager;
    obj.team_color = form.team_color;
    obj.team_brief = form.team_brief;
    obj.tour_id = props.selectedTournament.id;
    obj.group_in = props.selectedTournament.type == 'cup' ? form.group_in : null

    form.isSaving = true

    try {
        let resp = await api.createTeam(obj)
        if (resp.status == 203) {
            $toast.error('Name already exists', { position: 'top-right' });
            form.isSaving = false
            return;
        }
        $toast.default('New Team added', { position: 'top-right' });
        userData.getTournamentTeams(props.selectedTournament.id)
        form.isSaving = false
        closeModalBtn.value?.click()
        resetForm()
    } catch (error) {
        $toast.error('Network Error', { position: 'top-right' });
        form.isSaving = false
    }

}



</script>