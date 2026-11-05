<template>
    <section class="hero">
        <div class="hero-glow"></div>
        <svg class="hero-pitch" viewBox="0 0 1000 680" fill="none" aria-hidden="true">
            <rect x="1" y="1" width="998" height="678" />
            <path d="M500 1V679" />
            <circle cx="500" cy="340" r="91" />
            <circle cx="500" cy="340" r="3" fill="currentColor" />
            <path d="M1 138H165V542H1M1 248H55V432H1M165 267A91 91 0 0 1 165 413" />
            <path d="M999 138H835V542H999M999 248H945V432H999M835 267A91 91 0 0 0 835 413" />
        </svg>

        <div class="container position-relative">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7" :class="{ 'order-last order-lg-first': showAuth }">
                    <span class="hero-tag">
                        <span class="ui-live-dot"></span>
                        Football tournament management
                    </span>
                    <h1>Your tournament,<br><span class="hero-accent">live</span> on every phone.</h1>
                    <p class="hero-lead">
                        Schedule fixtures, update scores from the touchline, and give fans one link for live
                        matches, results and standings.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <RouterLink :to="startLink" class="ui-btn ui-btn-primary ui-btn-lg">
                            {{ auth.isLoggedIn ? 'Open your dashboard' : 'Create your tournament' }}
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </RouterLink>
                        <a href="#how" class="ui-btn ui-btn-ghost ui-btn-lg" @click.prevent="scrollToHow">See how it works</a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <RegisterComponent v-if="route.path == '/register'" />
                    <PasswordResetComponent v-else-if="route.path == '/forgot-password' || route.path == '/reset-password'" />
                    <LoginComponent v-else-if="route.path == '/login'" />
                    <ComponentPreview v-else />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/store/authStore';
import LoginComponent from '../User/LoginComponent.vue';
import PasswordResetComponent from '../User/PasswordResetComponent.vue';
import RegisterComponent from '../User/RegisterComponent.vue';
import ComponentPreview from './componentPreview.vue';

const route = useRoute();
const auth = useAuthStore();

const showAuth = computed(() => route.path !== '/');
const startLink = computed(() => (auth.isLoggedIn ? '/user/dashboard' : '/register'));

function scrollToHow() {
    document.getElementById('how')?.scrollIntoView();
}
</script>

<style scoped>
.hero {
    position: relative;
    padding-block: clamp(56px, 8vw, 104px) clamp(72px, 10vw, 136px);
    overflow: hidden;
}

.hero-glow {
    position: absolute;
    top: -20%;
    right: -10%;
    width: 60%;
    aspect-ratio: 1;
    border-radius: 50%;
    background: radial-gradient(closest-side, rgba(200, 243, 29, 0.11), transparent);
    pointer-events: none;
}

/* A pitch lying flat, receding behind the content. */
.hero-pitch {
    position: absolute;
    left: 50%;
    bottom: -38%;
    width: max(1500px, 118%);
    color: rgba(255, 255, 255, 0.1);
    stroke: currentColor;
    stroke-width: 1;
    transform: translateX(-50%) perspective(1400px) rotateX(62deg);
    mask-image: linear-gradient(to top, #000 15%, transparent 70%);
    -webkit-mask-image: linear-gradient(to top, #000 15%, transparent 70%);
    pointer-events: none;
}

.hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    color: var(--fg-muted);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.hero h1 {
    margin-bottom: 24px;
    font-size: clamp(2.75rem, 5.6vw, 4.75rem);
    line-height: 1.02;
    letter-spacing: -0.045em;
}

.hero-accent {
    color: var(--volt);
}

.hero-lead {
    max-width: 480px;
    margin-bottom: 36px;
    font-size: 1.125rem;
    line-height: 1.6;
}
</style>
