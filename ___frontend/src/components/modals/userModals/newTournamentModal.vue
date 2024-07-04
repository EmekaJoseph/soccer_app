<template>
    <div class="modal fade bg-faint show d-block " id="newTournamentModal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">

                    </h5>
                    <button @click="emit('close')" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveNewTournament" class="row justify-content-center gy-3">
                        <div class="col-12">
                            <input v-model="form.tour_title" type="text" class="form-control" placeholder="title..">
                        </div>
                        <div class="col-12">
                            <select v-model="form.tour_type" class="form-select">
                                <option value="cup" selected>type: CUP</option>
                                <option value="league">type: LEAGUE</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button :disabled="form.isSaving" type="submit" class="btn btn-primary-theme w-100">
                                Create
                            </button>
                        </div>
                    </form>

                </div>
                <div class="modal-footer border-0">
                    <!-- <button @click="copy(linkToCopy)" v-if="!copied" type="button" class="btn btn-primary-theme w-100">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>

                    <button v-else type="button" class="btn btn-light bg-success-subtle w-100">
                        <i class="bi bi-check-lg"></i> Copied
                    </button> -->
                </div>
            </div>
        </div>
    </div>

</template>


<script setup lang="ts">
import { reactive } from 'vue';
import { useToast } from 'vue-toast-notification';
import api from '@/store/axiosManager'

const $toast = useToast();

const form = reactive({
    tour_title: '',
    tour_type: 'cup',
    isSaving: false
})

async function saveNewTournament() {
    if (!form.tour_title) {
        $toast.default('Enter Tournament Title', { position: 'top-right' });
        return;
    }

    if (form.tour_title.length == 1) {
        $toast.default('Name can not be  one Character', { position: 'top-right' });
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
        emit('done')
        emit('close')
        form.tour_title = ''

    } catch (error) {
        console.log(error);

        $toast.error('Network Error', { position: 'top-right' });
        form.isSaving = false;
    }

}

const emit = defineEmits(['close', 'done'])
</script>