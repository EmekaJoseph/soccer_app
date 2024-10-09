<template>
    <div class="modal fade bg-faint show d-block animate__animated animate__fadeIn" id="TournamentFormModal"
        tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0">
                <div class="modal-header border-0 ">
                    <h5 class="modal-title ">
                        NEW TOURNAMENT
                    </h5>
                    <button @click="emit('close')" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body ">

                    <!-- <paystack buttonClass="'button-class btn btn-primary'" buttonText="Pay Online"
                        :publicKey="publicKey" :email="email" :amount="amount" :reference="''"
                        :onSuccess="onSuccessfulPayment" :onCanel="onCancelledPayment"></paystack> -->


                    <form @submit.prevent="saveTournament" class="row  g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" v-model="form.tour_title" id="titleT"
                                    placeholder="" />
                                <label for="titleT">Name:</label>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="!props.isEditing">
                            <div class="form-floating">
                                <select id="Ttype" v-model="form.tour_type" class="form-select">
                                    <option value="cup" selected>CUP</option>
                                    <option value="league">LEAGUE</option>
                                </select>
                                <label for="Ttype">Type:</label>
                            </div>

                        </div>
                        <div class="col-12">
                            <span v-html="tourTypeInfomation" class="small text-muted"> </span>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea v-model="form.tour_desc" id="Tdesc" placeholder="" class="form-control"
                                    style="height: 85px"></textarea>
                                <label for="Tdesc">Description:</label>
                            </div>
                        </div>

                        <div class="col-12" v-if="!props.isEditing">
                            <div class="">

                                <div class="row g-1">
                                    <!-- <div class="col-md-8">
                                        <div class="dropzone" v-bind="getRootProps()">
                                            <div class="text-center small">
                                                <div><i class="bi bi-image color-theme"></i></div>
                                                <div><span class="color-theme">Click to replace</span> or drag and
                                                    drop
                                                </div>
                                                <div class="fw-light">SVG, PNG, JPG or GIF (max. 400 x 400px)</div>
                                            </div>
                                            <input v-bind="getInputProps()" />
                                        </div>
                                    </div> -->


                                </div>
                            </div>
                        </div>

                        <div class="col-md-3  cursor-pointer">
                            <div class="mb-1"> Logo:</div>
                            <div class="image-circle" v-bind="getRootProps()"
                                :style="{ backgroundImage: `url(${form.photo_path})` }">
                                <input v-bind="getInputProps()" />
                            </div>
                            <!-- <div class="image-circle"></div> -->

                        </div>

                        <div class="col-md-12">
                            <button v-if="!form.isSaving" type="submit" class="btn btn-primary-theme float-end btn-lg"
                                style="width: 200px;">
                                {{ !props.isEditing ? 'Create ' : 'Update ' }}
                            </button>
                            <button v-else class="btn btn-primary-theme float-end btn-lg " style="width: 200px;"
                                type="button" disabled>
                                <span class="spinner-border spinner-border-s" role="status" aria-hidden="true"></span>

                            </button>

                        </div>
                    </form>

                </div>
                <!-- <div class="modal-footer border-0">

                    <button v-if="!form.isSaving" type="submit" class="btn btn-primary-theme btn-lg">
                        {{ !props.isEditing ? 'Create ' : 'Update ' }}
                    </button>
                    <button v-else class="btn btn-primary-theme " type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Saving...
                    </button>
                </div> -->
            </div>
        </div>
    </div>

</template>


<script setup lang="ts">
import { computed, reactive, watchEffect } from 'vue';
import { useToast } from 'vue-toast-notification';
import api from '@/store/axiosManager'
import { useDropzone } from "vue3-dropzone";
import useFxn from '@/store/useFunctions';
// @ts-ignore
// import paystack from "vue3-paystack";

const props = defineProps({
    isEditing: {
        required: true,
        type: Boolean
    },
    editingData: {
        required: false,
        type: Object
    }
})




const { getRootProps, getInputProps, ...rest } = useDropzone({
    onDrop: (acceptedFiles) => {
        const requiredFormats = ['png', 'jpg', 'jpeg']
        if (!useFxn.isExtension(acceptedFiles[0].name, requiredFormats)) {
            useFxn.toast('Please upload an image', 'warning');
            return;
        }

        const file = acceptedFiles[0];
        form.photo_path = file;

        const reader = new FileReader();
        reader.onload = (e: any) => {
            form.photo_path = e.target.result; // Set preview to DataURL
        };
        reader.readAsDataURL(file);

        form.tour_logo = acceptedFiles[0]
    }
});

const $toast = useToast();

const form = reactive({
    tour_title: '',
    tour_id: '',
    photo_path: '',
    tour_desc: '',
    tour_logo: '',
    tour_type: 'cup',
    isSaving: false
})

watchEffect(() => {
    if (props.isEditing) {
        form.tour_title = props.editingData?.tour_title
        form.tour_type = props.editingData?.tour_type
        form.tour_desc = props.editingData?.tour_desc
    }
})

async function saveTournament() {
    if (!form.tour_title) {
        $toast.default('Enter Tournament Title', { position: 'top-right' });
        return;
    }

    if (form.tour_title.length == 1) {
        $toast.default('Name can not be  one Character', { position: 'top-right' });
        return;
    }

    form.isSaving = true;

    const newForm = new FormData();
    newForm.append('tour_title', form.tour_title)
    newForm.append('tour_logo', form.tour_logo)
    newForm.append('tour_desc', form.tour_desc ?? '')
    newForm.append('tour_type', form.tour_type ?? '')
    newForm.append('tour_id', props.editingData?.tour_id ?? '')

    try {
        const resp = props.isEditing ? await api.updateTournament(newForm) : await api.createTournament(newForm)
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


// const publicKey = 'pk_test_bc8eb248cabce660866f611519d07ce8f27a513c';
// const amount = 1000;
// const email = 'somteacodes@gmail.com';
// const firstname = 'Somtea';
// const lastname = 'Codes'

// function onCancelledPayment() {
//     console.log("Payment cancelled by user");
// }


// const onSuccessfulPayment = (response: any) => {
//     console.log(response);
// }



const tourTypeInfomation = computed(() => {
    return form.tour_type == 'cup' ?
        ` A <b>Cup</b> competition is typically a knockout tournament. Teams play a single
         match against each other. The losing team is eliminated, and the winning team advances to
         the next round.` :
        `A <b>League</b> is a series of games played over a predetermined period, typically a
          season. Each team plays a set number of matches against other teams in the league.`
})

</script>

<style lang="css" scoped>
.image-circle {
    height: 100px;
    width: 100px;
    border-radius: 50%;
    background-color: var(--bs-light-bg-subtle);
    border: 1px solid #e8e5e5;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}

.image-circle:hover {
    border-color: #41b883;
}


.dropzone {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    row-gap: 5px;
    border: 2px dashed var(--bs-dark-bg-subtle);
    background-color: var(--bs-light);
    transition: 0.3s ease all;
    color: rgb(170, 164, 164);
    cursor: pointer;
    border-radius: 10px;
}

/* .dropzone input {
    display: none;
} */

.active-dropzone {
    color: #fff;
    border-color: #fff;
    background-color: #41b883;
}
</style>