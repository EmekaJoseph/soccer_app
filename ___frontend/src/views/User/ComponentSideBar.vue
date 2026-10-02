<template>
    <aside class="sidebar">
        <RouterLink to="/user/dashboard" class="brand">
            <span class="brand-mark"><img src="/icons/soccer.svg" alt="" width="22"></span>
            <span class="brand-name">SOCCER<span class="text-gradient">APP</span></span>
        </RouterLink>
        <ComponentMenuList />
        <div class="sidebar-foot">
            <RouterLink to="/" class="small">
                <i class="bi bi-house"></i> Public site
            </RouterLink>
        </div>
    </aside>
</template>

<script setup lang="ts">
import ComponentMenuList from './ComponentMenuList.vue'
</script>

<style scoped>
.sidebar {
    position: fixed;
    inset: 0 auto 0 0;
    width: var(--sidebar-width);
    display: flex;
    flex-direction: column;
    background:
        radial-gradient(120% 60% at 0% 0%, rgba(0, 242, 254, 0.08), transparent 60%),
        linear-gradient(180deg, var(--brand-navy-800), var(--brand-navy-900));
    border-right: 1px solid rgba(255, 255, 255, 0.06);
    padding: 1.25rem 0.9rem;
    overflow-y: auto;
    z-index: 1030;
}

.brand {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.4rem 0.6rem 1.4rem;
    text-decoration: none;
}

.brand-mark {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.brand-name {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.15rem;
    letter-spacing: 0.04em;
    color: #fff;
}

.sidebar-foot {
    margin-top: auto;
    padding: 1rem 0.6rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-foot a {
    color: rgba(255, 255, 255, 0.55);
    text-decoration: none;
}

.sidebar-foot a:hover {
    color: #fff;
}

@media (max-width: 767.98px) {
    .sidebar {
        display: none;
    }
}
</style>
