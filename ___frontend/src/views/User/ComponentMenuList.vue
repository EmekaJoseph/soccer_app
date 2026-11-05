<template>
    <nav class="menu" aria-label="Main">
        <template v-for="group in visibleGroups" :key="group.section">
            <div v-if="group.section" class="menu-section">{{ group.section }}</div>
            <RouterLink v-for="menu in group.items" :key="menu.link" :to="menu.link" class="menu-item"
                :class="{ current: route.path.startsWith(menu.link) }"
                :aria-current="route.path.startsWith(menu.link) ? 'page' : undefined" @click="emit('navigate')">
                <i :class="menu.icon" aria-hidden="true"></i>
                <span>{{ menu.name }}</span>
            </RouterLink>
        </template>
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

/** Menu items for this role, grouped in display order. Empty sections disappear. */
const visibleGroups = computed(() => {
    const groups: { section: string; items: typeof menuItems }[] = []
    for (const item of menuItems) {
        if (!authStore.user || !item.roles.includes(authStore.user.role)) continue
        const section = item.section ?? ''
        const group = groups.find((g) => g.section === section)
        if (group) group.items.push(item)
        else groups.push({ section, items: [item] })
    }
    return groups
})
</script>

<style scoped>
.menu {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin-top: 0.75rem;
}

.menu-section {
    margin-top: 1rem;
    padding: 0 0.75rem 0.35rem;
    font-family: var(--font-mono);
    font-size: 0.6875rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.55rem 0.75rem;
    border-radius: 8px;
    color: var(--fg-muted);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.15s ease, color 0.15s ease;
}

.menu-item i {
    width: 1.1rem;
    font-size: 1rem;
    text-align: center;
}

.menu-item:hover {
    background: var(--surface);
    color: var(--fg);
}

.menu-item.current {
    background: var(--surface-raised);
    color: var(--fg);
}

.menu-item.current i {
    color: var(--accent);
}
</style>
