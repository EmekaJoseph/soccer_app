<template>
    <div class="hero">
        <div class="hero-overlay"></div>
        <div class="row g-0">
            <section class="col-6 bg-primary-theme d-none d-lg-flex">
                <div class="container position-relative p-5 text-white">
                    <h1>Tournament Manager</h1>
                    <div>
                        Manage live scores, team standings, and match updates for all your favorite tournaments in one
                        place.
                    </div>
                </div>
            </section>
            <section class="col-lg-6  form-section">
                <UserLogin v-if="route.path != '/register'" />
                <UserRegister v-else />
            </section>
        </div>
    </div>
</template>

<script setup lang="ts">
import UserLogin from '../User/UserLogin.vue';
import UserRegister from '../User/UserRegister.vue';
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
    background: linear-gradient(to bottom, #060e128d 100%, rgba(0, 0, 0, 0) 100%);
}

section {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.form-section {
    background: url('@/assets/img/hero-bg.jpg') no-repeat center center;
    background-size: cover;
}

.hero h1 {
    margin: 0;
    font-size: 3rem;
    font-weight: 700;
}
</style>