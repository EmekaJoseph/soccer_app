<template>
    <footer class="site-footer">
        <div class="container footer-inner">
            <RouterLink to="/" class="d-inline-flex text-decoration-none" aria-label="LiveFixtura home">
                <BrandMark />
            </RouterLink>
            <p class="mb-0">&copy; {{ (new Date()).getFullYear() }} LiveFixtura. All rights reserved.</p>
            <p class="mb-0">Designed by <span class="footer-credit">ProfficTech</span></p>
        </div>
    </footer>
</template>

<script setup lang="ts">
import BrandMark from './BrandMark.vue';
</script>

<style scoped>
.site-footer {
    width: 100%;
    padding-block: 28px;
    border-top: 1px solid var(--line);
    background: var(--bg);
    color: var(--fg-muted);
    font-family: var(--font-sans);
    font-size: 0.8125rem;
}

.footer-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px 32px;
}

.footer-credit {
    color: var(--fg);
    font-weight: 500;
}
</style>
