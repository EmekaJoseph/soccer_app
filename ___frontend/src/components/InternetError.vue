<template>
    <div class="empty-state text-center py-5">
        <div class="error-icon mx-auto mb-3"><i class="bi bi-wifi-off"></i></div>
        <div class="fw-semibold mb-1">We couldn't load this page</div>
        <div class="small opacity-75 mb-3">Check your connection and try again.</div>
        <button class="btn btn-sm btn-primary-theme px-3" @click="reload">
            <i class="bi bi-arrow-clockwise me-1"></i> Retry
        </button>
    </div>
</template>

<script setup lang="ts">
const reload = () => window.location.reload()
</script>

<style scoped>
.error-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    background: rgba(229, 72, 77, 0.12);
    color: #e5484d;
}
</style>
