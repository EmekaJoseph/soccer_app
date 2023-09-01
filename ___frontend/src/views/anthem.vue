<template>
    <div class="main min-vh-100 general-body">
        <div class="container pt-5">
            <div class="col-lg-10">
                <div class="alert alert-ligh border-0 small text-muted">
                    <h6><i class="bi bi-music-note-list"></i> Diocesan Anthem:</h6>
                    The Diocesan Anthem shall be used as the competion's anthem and it is expected to be sung at every
                    match.
                    <p>All teams MUST learn it and be able to sing it.</p>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="alert alert-warning border-0 py-4">
                    Our Lagos Mainland Diosese, a sacred fellowship. <br>
                    Firmly rooted in the charge to spread the gospel far and near. <br>
                    Bound in love, we strive to build a diocese we all can call <br>
                    An epitome of Christ.
                    <div class="fst-italic p-2">
                        <div class="fw-bold">Chorus:</div>
                        Forward ever Lagos Mainland <br>
                        Greater Blessed Lagos Mainland <br>
                        Onward Upward Mainland <br>
                        In unity and love.
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <audio class="form-control" src="/audio/diocesan_anthem.mp3" controls></audio>
            </div>
            <div class="col-lg-4 mt-3">
                <a href="/audio/diocesan_anthem.mp3" class="form-control btn btn-primary" download>
                    <i class="bi bi-download"></i> Download Anthem
                </a>
            </div>


        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
import { onMounted, ref, onUnmounted } from 'vue'



</script>

<style scoped></style>