<template>
    <header class="nav-bar">
        <div class="container nav-inner">
            <RouterLink to="/" class="d-inline-flex text-decoration-none" aria-label="LiveFixtura home">
                <BrandMark />
            </RouterLink>

            <!-- Plain anchors would go through the router, whose scrollBehavior jumps back to the top. -->
            <nav class="nav-links d-none d-md-flex" aria-label="Page sections">
                <a href="#how" @click.prevent="scrollToSection('how')">How it works</a>
                <a href="#features" @click.prevent="scrollToSection('features')">Features</a>
            </nav>

            <div class="d-flex align-items-center gap-2">
                <ThemeToggle />
                <RouterLink v-if="auth.isLoggedIn" to="/user/dashboard" class="ui-btn ui-btn-primary">Dashboard</RouterLink>
                <template v-else>
                    <RouterLink to="/login" class="ui-btn ui-btn-ghost">Log in</RouterLink>
                    <RouterLink to="/register" class="ui-btn ui-btn-primary nav-start">Get started</RouterLink>
                </template>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import BrandMark from '@/components/BrandMark.vue';
import { useAuthStore } from '@/store/authStore';

const auth = useAuthStore();

function scrollToSection(id: string) {
    document.getElementById(id)?.scrollIntoView();
}
</script>

<style scoped>
.nav-bar {
    position: sticky;
    top: 0;
    z-index: 1020;
    border-bottom: 1px solid var(--line);
    background: var(--bar-bg);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
}

.nav-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
}

.nav-links {
    gap: 32px;
}

.nav-links a {
    color: var(--fg-muted);
    font-size: 0.875rem;
    text-decoration: none;
    transition: color 0.15s ease;
}

.nav-links a:hover {
    color: var(--fg);
}

/* The hero's main button covers this on the narrowest phones. */
@media (max-width: 399.98px) {
    .nav-start {
        display: none;
    }
}
</style>
