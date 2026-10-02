<template>
    <header class="topbar">
        <div class="d-flex align-items-center gap-3 min-w-0">
            <button class="btn-icon d-md-none" data-bs-toggle="offcanvas" data-bs-target="#userOffcanvas"
                aria-controls="userOffcanvas" aria-label="Open menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="min-w-0">
                <div class="crumb">{{ authStore.isAdmin ? 'Owner workspace' : 'Scorer workspace' }}</div>
                <h1 class="page-title text-truncate">{{ String(route.name ?? '') }}</h1>
            </div>
        </div>

        <div class="dropdown">
            <button class="user-chip" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">{{ initials }}</span>
                <span class="d-none d-sm-flex flex-column text-start lh-sm">
                    <span class="fw-semibold text-strong">{{ authStore.displayName }}</span>
                    <span class="small text-muted">{{ authStore.isAdmin ? 'Owner' : 'Scorer' }}</span>
                </span>
                <i class="bi bi-chevron-down small text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><RouterLink class="dropdown-item" to="/user/account"><i class="bi bi-person-gear me-2"></i>Account settings</RouterLink></li>
                <li><hr class="dropdown-divider"></li>
                <li><button class="dropdown-item text-danger" @click="logOut"><i class="bi bi-box-arrow-right me-2"></i>Log out</button></li>
            </ul>
        </div>
    </header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="userOffcanvas" aria-labelledby="userOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="userOffcanvasLabel">SOCCER<span class="text-gradient">APP</span></h5>
            <button ref="btnClose" type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ComponentMenuList @navigate="btnClose?.click()" />
            <button class="btn btn-soft mt-auto" @click="logOut"><i class="bi bi-box-arrow-right me-2"></i>Log out</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore'
import ComponentMenuList from './ComponentMenuList.vue'

const authStore = useAuthStore()
const route = useRoute()
const router = useRouter()
const btnClose = ref<HTMLButtonElement | null>(null)

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
        router.replace({ path: '/' })
    }
}
</script>

<style scoped>
.topbar {
    position: sticky;
    top: 0;
    z-index: 1020;
    margin-left: var(--sidebar-width);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.9rem 1.75rem;
    background: rgba(243, 246, 250, 0.82);
    backdrop-filter: saturate(160%) blur(12px);
    -webkit-backdrop-filter: saturate(160%) blur(12px);
    border-bottom: 1px solid var(--surface-border);
}

.crumb {
    font-size: 0.72rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--text-muted);
}

.page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--text-strong);
    margin: 0;
}

.min-w-0 {
    min-width: 0;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.3rem 0.75rem 0.3rem 0.3rem;
    border-radius: 999px;
    border: 1px solid var(--surface-border);
    background: var(--surface-card);
    box-shadow: var(--shadow-sm);
}

.avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    color: #04212c;
    background: linear-gradient(135deg, var(--brand-blue), var(--brand-cyan));
}

.text-strong {
    color: var(--text-strong);
}

.dropdown-menu {
    border-radius: var(--radius-md);
    padding: 0.4rem;
    min-width: 210px;
}

.dropdown-item {
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
}

.offcanvas {
    width: 280px !important;
}

@media (max-width: 767.98px) {
    .topbar {
        margin-left: 0;
        padding: 0.75rem 1rem;
    }

    .page-title {
        font-size: 1.1rem;
    }
}
</style>
