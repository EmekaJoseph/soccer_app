<template>
    <div class="fixed-bottom-btn">
        <div @click="startShare" class="justify-content-end floatPanel">
            <div class="card animate__heartBeat animate__infinite animate__slower">
                <div class="card-body p-1 px-3">
                    <div><i class="bi bi-share"></i> share site</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useShare } from '@vueuse/core'

const { share } = useShare()

function startShare() {
    share({
        title: 'DLAM FA',
        text: 'Diocese of Lagos Mainland Football Academy',
        url: location.href,
    })
}
</script>

<style scoped>
.fixed-bottom-btn {
    position: fixed;
    bottom: 0;
    right: 0;
    z-index: 999;
}


.floatPanel {
    margin-bottom: 20px;
    padding-right: 15px;
    display: flex;
    z-index: 999;
    position: relative;
    transition: all ease-in-out 0.4s;
    font-size: 11px;
}
</style>