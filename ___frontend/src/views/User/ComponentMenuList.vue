<template>
    <nav class="menu" aria-label="Main">
        <div class="menu-label">Menu</div>
        <RouterLink v-for="menu in visibleMenu" :key="menu.link" :to="menu.link" class="menu-item"
            :class="{ current: route.path.startsWith(menu.link) }" @click="emit('navigate')">
            <i :class="menu.icon"></i>
            <span>{{ menu.name }}</span>
        </RouterLink>
    </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/store/authStore'
import { menuItems } from '@/router/user_routes'

const emit = defineEmits<{ navigate: [] }>()
const authStore = useAuthStore()
const route = useRoute()

const visibleMenu = computed(() => menuItems.filter((m) => authStore.user && m.roles.includes(authStore.user.role)))
</script>

<style scoped>
.menu {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.menu-label {
    font-size: 0.68rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.35);
    padding: 0 0.75rem 0.5rem;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.75rem;
    border-radius: 12px;
    color: rgba(255, 255, 255, 0.68);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.93rem;
    transition: background-color 0.15s, color 0.15s;
}

.menu-item i {
    font-size: 1.05rem;
    width: 1.2rem;
    text-align: center;
}

.menu-item:hover {
    background: rgba(255, 255, 255, 0.06);
    color: #fff;
}

.menu-item.current {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.22), rgba(0, 242, 254, 0.12));
    color: #fff;
    box-shadow: inset 0 0 0 1px rgba(0, 242, 254, 0.25);
}

.menu-item.current i {
    color: var(--brand-cyan);
}

/* Light variant used inside the mobile offcanvas */
:global(.offcanvas) .menu-label {
    color: var(--text-muted);
}

:global(.offcanvas) .menu-item {
    color: var(--text-body);
}

:global(.offcanvas) .menu-item:hover,
:global(.offcanvas) .menu-item.current {
    background: #eef6fb;
    color: var(--text-strong);
    box-shadow: none;
}

:global(.offcanvas) .menu-item.current i {
    color: #1f8fd1;
}
</style>
