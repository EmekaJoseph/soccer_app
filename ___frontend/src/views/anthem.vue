<template>
    <div class="public-page min-vh-100">
        <div class="container pt-5">
            <div class="col-lg-10">
                <div class="small mb-4">
                    <h6><i class="bi bi-music-note-list"></i> Diocesan Anthem:</h6>
                    The Diocesan Anthem shall be used as the competion's anthem and it is expected to be sung at every
                    match.
                    <p>All teams MUST learn it and be able to sing it.</p>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="lyrics mb-4">
                    Our Lagos Mainland Diocese, a sacred fellowship. <br>
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
                <a href="/audio/diocesan_anthem.mp3" class="ui-btn ui-btn-ghost w-100" download>
                    Download Tune
                </a>
            </div>


        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
import { onMounted, ref, onUnmounted } from 'vue'



</script>

<style scoped>
h6 {
    color: var(--fg);
}

.lyrics {
    padding: 2rem;
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    background: var(--surface);
    color: var(--fg);
    line-height: 1.7;
}
</style>