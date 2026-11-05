<template>
    <header class="topbar">
        <div class="d-flex align-items-center gap-3 min-w-0">
            <button class="btn-icon d-md-none" data-bs-toggle="offcanvas" data-bs-target="#userOffcanvas"
                aria-controls="userOffcanvas" aria-label="Open menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="min-w-0">
                <h1 class="page-title text-truncate">{{ String(route.name ?? '') }}</h1>
                <p v-if="pageHint" class="page-hint text-truncate">{{ pageHint }}</p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <ThemeToggle />
            <div class="dropdown">
            <button class="user-chip" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">{{ initials }}</span>
                <span class="d-none d-sm-flex flex-column text-start lh-sm">
                    <span class="user-name">{{ authStore.displayName }}</span>
                    <span class="user-role">{{ authStore.isAdmin ? 'Owner' : 'Scorer' }}</span>
                </span>
                <i class="bi bi-chevron-down user-role"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><RouterLink class="dropdown-item" to="/user/account"><i class="bi bi-person-gear me-2"></i>Account settings</RouterLink></li>
                <li><hr class="dropdown-divider"></li>
                <li><button class="dropdown-item text-danger" @click="logOut"><i class="bi bi-box-arrow-right me-2"></i>Log out</button></li>
            </ul>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="userOffcanvas" aria-label="Menu">
        <div class="offcanvas-header">
            <BrandMark />
            <button ref="btnClose" type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ComponentMenuList @navigate="btnClose?.click()" />
            <button class="ui-btn ui-btn-ghost mt-auto" @click="logOut"><i class="bi bi-box-arrow-right"></i>Log out</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import { useUserDataStore } from '@/store/userDataStore'
import BrandMark from '@/components/BrandMark.vue'
import { menuItems } from '@/router/user_routes'
import ComponentMenuList from './ComponentMenuList.vue'

const authStore = useAuthStore()
const userData = useUserDataStore()
const route = useRoute()
const router = useRouter()
const btnClose = ref<HTMLButtonElement | null>(null)

const pageHint = computed(() => menuItems.find((m) => route.path.startsWith(m.link))?.hint)

const initials = computed(() =>
    authStore.displayName.split(/[\s@.]+/).filter(Boolean).slice(0, 2).map((w) => w[0]!.toUpperCase()).join(''),
)

async function logOut() {
    btnClose.value?.click()
    try {
        await api.logout()
    } catch {
        // token may already be invalid; signing out locally is enough
    } finally {
        authStore.logout()
        userData.reset()
        router.replace({ path: '/' })
    }
}
</script>

<style scoped>
.topbar {
    position: sticky;
    top: 0;
    z-index: 1020;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    height: 64px;
    margin-left: var(--sidebar-width);
    padding-inline: 2rem;
    border-bottom: 1px solid var(--line);
    background: var(--bar-bg);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
}

.page-title {
    margin: 0;
    color: var(--fg);
    font-size: 1rem;
    font-weight: 600;
}

.page-hint {
    margin: 0;
    font-size: 0.75rem;
}

.min-w-0 {
    min-width: 0;
}

@media (max-width: 575.98px) {
    .page-hint {
        display: none;
    }
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.25rem 0.6rem 0.25rem 0.25rem;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    background: transparent;
}

.user-chip:hover {
    background: var(--surface-raised);
}

.avatar {
    display: grid;
    place-items: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: var(--volt);
    color: var(--on-volt);
    font-size: 0.75rem;
    font-weight: 600;
}

.user-name {
    color: var(--fg);
    font-size: 0.8125rem;
    font-weight: 500;
}

.user-role {
    color: var(--fg-muted);
    font-size: 0.75rem;
}

.dropdown-menu {
    min-width: 210px;
    padding: 0.35rem;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius-sm);
    background: var(--surface-raised);
    box-shadow: var(--shadow-lg);
}

.dropdown-item {
    padding: 0.5rem 0.75rem;
    border-radius: 7px;
    color: var(--fg);
    font-size: 0.875rem;
}

.dropdown-item:hover,
.dropdown-item:focus {
    background: var(--hover-tint);
}

.dropdown-divider {
    border-color: var(--line);
}

.offcanvas {
    width: 280px !important;
    border-right: 1px solid var(--line) !important;
    background: var(--bg);
}

@media (max-width: 767.98px) {
    .topbar {
        margin-left: 0;
        padding-inline: 1rem;
    }
}
</style>
