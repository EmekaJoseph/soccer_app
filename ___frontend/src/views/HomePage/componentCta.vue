<template>
    <section class="cta-wrap">
        <div class="container">
            <div class="cta">
                <h2>Your next tournament starts here.</h2>
                <RouterLink :to="auth.isLoggedIn ? '/user/dashboard' : '/register'" class="ui-btn ui-btn-lg cta-btn">
                    {{ auth.isLoggedIn ? 'Open your dashboard' : 'Create your tournament' }}
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </RouterLink>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/store/authStore';

const auth = useAuthStore();
</script>

<style scoped>
.cta-wrap {
    padding-bottom: clamp(48px, 8vw, 96px);
}

.cta {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 32px;
    padding: clamp(32px, 6vw, 72px);
    border-radius: 24px;
    background: var(--volt);
}

.public-page .cta h2 {
    max-width: 640px;
    margin: 0;
    color: var(--on-volt);
    font-size: clamp(2.25rem, 5vw, 4rem);
    line-height: 1.02;
    letter-spacing: -0.045em;
}

.cta-btn {
    background: var(--on-volt);
    color: #fff;
}

.cta-btn:hover {
    background: #262b28;
    color: #fff;
}

.public-page .cta-btn:focus-visible {
    outline-color: var(--on-volt) !important;
}
</style>
