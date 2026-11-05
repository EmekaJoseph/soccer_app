<template>
    <aside class="sidebar">
        <RouterLink to="/user/dashboard" class="brand" aria-label="Dashboard">
            <BrandMark />
        </RouterLink>
        <ComponentMenuList />
        <div class="sidebar-foot">
            <RouterLink to="/"><i class="bi bi-arrow-up-right"></i> Public site</RouterLink>
        </div>
    </aside>
</template>

<script setup lang="ts">
import BrandMark from '@/components/BrandMark.vue'
import ComponentMenuList from './ComponentMenuList.vue'
</script>

<style scoped>
.sidebar {
    position: fixed;
    inset: 0 auto 0 0;
    z-index: 1030;
    display: flex;
    flex-direction: column;
    width: var(--sidebar-width);
    padding: 0 0.75rem 1rem;
    overflow-y: auto;
    border-right: 1px solid var(--line);
    background: var(--bg);
}

.brand {
    display: flex;
    align-items: center;
    flex: none;
    height: 64px;
    padding-inline: 0.5rem;
    text-decoration: none;
}

.sidebar-foot {
    margin-top: auto;
    padding: 0.75rem 0.75rem 0;
    border-top: 1px solid var(--line);
}

.sidebar-foot a {
    color: var(--fg-muted);
    font-size: 0.8125rem;
    text-decoration: none;
}

.sidebar-foot a:hover {
    color: var(--fg);
}

@media (max-width: 767.98px) {
    .sidebar {
        display: none;
    }
}
</style>
