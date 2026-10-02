<script setup lang="ts">
defineProps<{ title: string; subtitle: string }>()
</script>

<template>
    <div class="auth-card animate__animated animate__fadeIn">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="/icons/soccer.svg" width="50" alt="" class="mb-3 animate-float">
                <h2 class="text-white fw-bold mb-1">{{ title }}</h2>
                <p class="text-white-50 small">{{ subtitle }}</p>
            </div>
            <slot />
        </div>
    </div>
</template>

<style>
/* Not scoped: shared by the login, register and password forms rendered inside. */
.auth-card .form-floating .form-control {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    border-radius: 12px;
}

.auth-card .form-floating .form-control:focus {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: var(--accent-color) !important;
    box-shadow: 0 0 0 4px rgba(0, 242, 254, 0.1) !important;
}

.auth-card .form-floating > label {
    color: rgba(255, 255, 255, 0.5);
}

.auth-card .input-group-text {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 0 12px 12px 0;
}

.auth-card .form-floating .form-control.border-end-0 {
    border-radius: 12px 0 0 12px;
}

.auth-card .is-invalid {
    border-color: #ff4d4d !important;
}

.auth-card .btn-primary-theme {
    background: var(--accent-gradient) !important;
    border: none !important;
    color: #000 !important;
    letter-spacing: 0.5px;
}

.auth-card .text-gradient {
    background: var(--accent-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.auth-card .auth-alert {
    background: rgba(220, 53, 69, 0.1);
    color: #ff6b6b;
    border: 0;
}

.auth-card .auth-success {
    background: rgba(25, 135, 84, 0.15);
    color: #75e0a7;
    border: 0;
}
</style>
