<template>
    <div class="container">
        <div v-if="userData.apiError">
            <internetErrorComponent />
        </div>
        <div v-else>

            <div class="row justify-content-center gy-4">
                <div class="col-lg-8">

                    <fieldset class="border rounded-3 p-3 bg-white h-100">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">TOURNAMENTS</legend>
                        <div class="d-flex justify-content-end col-12 mb-3  hover-tilt-Y">
                            <span @click="form.isOpen = !form.isOpen" v-if="!form.isOpen"
                                class="text-primary cursor-pointer"> <i class="bi bi-plus-lg"></i> create
                            </span>
                            <span @click="form.isOpen = !form.isOpen" v-else class="text-danger cursor-pointer"><i
                                    class="bi bi-x-lg"></i>
                                close </span>
                        </div>
                        <div class="content-panel">
                            <form @submit.prevent="saveNewTournament" v-if="form.isOpen"
                                class="row justify-content-center gy-3">
                                <div class="col-md-5">
                                    <input v-model="form.tour_title" type="text" class="form-control" placeholder="title..">
                                </div>
                                <div class="col-md-4">
                                    <select v-model="form.tour_type" class="form-select">
                                        <option value="cup" selected>type: CUP</option>
                                        <option value="league">type: LEAGUE</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button :disabled="form.isSaving" type="submit" class="btn btn-primary w-100">add
                                        new</button>
                                </div>
                            </form>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div v-if="!userData.tournaments.length" class="card-body text-center">
                                            You dont have any Tournaments, create One.
                                        </div>
                                        <div v-else class="card-body p-0">
                                            <EasyDataTable class="border-0" :headers="headers" :items="userData.tournaments"
                                                show-index>

                                                <template #item-edit="item">
                                                    <div class="operation-wrapper">
                                                        <span data-bs-toggle="modal" data-bs-target="#editMaterial"
                                                            @click="" class="me-4 operation-icon cursor-pointer">
                                                            <i class="bi bi-pencil"></i>
                                                        </span>
                                                    </div>
                                                </template>

                                                <template #item-delete="item">
                                                    <div class="operation-wrapper">
                                                        <span @click="" class="operation-icon cursor-pointer">
                                                            <i class="bi bi-trash3 text-danger"></i>
                                                        </span>
                                                    </div>
                                                </template>

                                            </EasyDataTable>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-4">
                    <fieldset class="border rounded-3 p-3 h-100 bg-white">
                        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">ACCOUNT</legend>
                        <div class="content-panel">

                        </div>
                    </fieldset>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';
import { useUserDataStore } from '@/store/userDataStore';
import type { Header } from "vue3-easy-data-table";
import api from '@/store/axiosManager'
import { useToast } from 'vue-toast-notification';

const userData = useUserDataStore()
const $toast = useToast();

const form = reactive({
    isOpen: false,
    tour_title: '',
    tour_type: 'cup',
    isSaving: false
})

onMounted(() => {
    userData.getTournaments()
})

async function saveNewTournament() {
    if (!form.tour_title) {
        $toast.default('Enter Tournament Title', { position: 'top-right' });
        return;
    }

    form.isSaving = true;

    try {
        let resp = await api.createTournament(form)
        if (resp.status == 203) {
            $toast.warning('Title already exists', { position: 'top-right' });
            form.isSaving = false;
            return;
        }

        $toast.success('Added Successfuly', { position: 'top-right' });
        form.isSaving = false;
        userData.getTournaments()
        form.isOpen = false;
        form.tour_title = ''

    } catch (error) {
        console.log(error);

        $toast.error('Network Error', { position: 'top-right' });
        form.isSaving = false;
    }

}

const headers: Header[] = [
    { text: "Name", value: "title" },
    { text: "TYPE", value: "type" },
    { text: "CREATED", value: "created" },
    // { text: "", value: "edit" },
    // { text: "", value: "delete" },

];
</script>

<style scoped></style>