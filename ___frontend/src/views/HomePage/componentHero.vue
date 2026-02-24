<template>
    <div class="hero bg-image d-flex align-items-center">
        <div class="hero-overlay"></div>
        <div class="container position-relative z-1">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <div class="badge bg-primary-theme px-3 py-2 mb-3 rounded-pill animate-float">
                        <i class="bi bi-trophy-fill me-2 text-warning"></i>
                        Professional Soccer Management
                    </div>
                    <h1 class="display-3 fw-bold text-white mb-4">
                        Elevate Every <span class="text-gradient">Soccer Goal</span>
                    </h1>
                    <p class="lead text-white-50 mb-5 px-lg-0 px-4">
                        The all-in-one platform for tournament organizers and fans. Track every team, manage player
                        rosters, and deliver real-time results directly to your audience.
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-lightning-charge-fill text-gradient fs-3 me-2"></i>
                            <div class="text-white">
                                <div class="fw-bold">Live Scores</div>
                                <div class="small text-white-50">Instant Tracking</div>
                            </div>
                        </div>
                        <div class="vr bg-white mx-3 opacity-25"></div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people-fill text-gradient fs-3 me-2"></i>
                            <div class="text-white">
                                <div class="fw-bold">Player Stats</div>
                                <div class="small text-white-50">Detailed Rosters</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- <div class="glass-card p-4 p-md-5"> -->
                    <LoginComponent v-if="route.path != '/register'" />
                    <RegisterComponent v-else />
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import LoginComponent from '../User/LoginComponent.vue';
import RegisterComponent from '../User/RegisterComponent.vue';
import { useRoute } from 'vue-router';

const route = useRoute()
</script>

<style scoped>
.hero {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    padding: 100px 0;
}

.hero-overlay {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(15, 32, 39, 0.9) 0%, rgba(32, 58, 67, 0.8) 100%);
    z-index: 0;
}

.bg-image {
    background: url('@/assets/img/hero-bg.jpg') no-repeat center center;
    background-size: cover;
}

.hero h1 {
    font-size: clamp(2.5rem, 5vw, 4rem);
    line-height: 1.1;
}

.z-1 {
    z-index: 1;
}

@media (max-width: 991.98px) {
    .hero {
        padding: 80px 0;
    }
}
</style>