<template>
    <div class="hero">
        <div class="hero-overlay"></div>
        <div class="row g-0">
            <section class="col-6 bg-primary-theme d-none d-lg-flex">
                <div class="container position-relative p-5 text-white">
                    <img src="/icons/soccer.svg" width="100" alt="">
                    <h1>Tournament Manager</h1>
                    <div class="px-5">
                        Manage live scores, team standings, and match updates for all your favorite tournaments in one
                        place. Stay ahead of the
                        curve, analyze team performance, and never miss a close call with real-time
                        notifications.
                    </div>
                </div>
            </section>
            <section class="col-lg-6  bg-imag bg-primary-theme px-md-0 px-3">
                <LoginComponent v-if="route.path != '/register'" />
                <RegisterComponent v-else />
            </section>
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
    overflow: hidden;
}

.hero-overlay {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, var(--theme-color-7) 100%, #00000000 100%);
}

section {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.bg-image {
    background: url('@/assets/img/hero-bg.jpg') no-repeat center center;
    background-size: cover;
}

.hero h1 {
    margin: 0;
    font-size: 3rem;
    font-weight: 700;
}
</style>