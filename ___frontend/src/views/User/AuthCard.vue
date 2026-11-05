<script setup lang="ts">
defineProps<{ title: string; subtitle: string }>()
</script>

<template>
    <div class="auth-card animate__animated animate__fadeIn">
        <div class="mb-4">
            <h2 class="auth-title">{{ title }}</h2>
            <p class="auth-subtitle">{{ subtitle }}</p>
        </div>
        <slot />
    </div>
</template>

<style>
/* Not scoped: shared by the login, register and password forms rendered inside. */
.auth-card {
    padding: clamp(24px, 4vw, 40px);
    border: 1px solid var(--line);
    border-radius: 16px;
    background: var(--ink-900);
    box-shadow: 0 24px 60px -24px rgba(0, 0, 0, 0.8);
}

.auth-card .auth-title {
    margin-bottom: 6px;
    font-size: 1.75rem;
}

.auth-card .auth-subtitle {
    margin: 0;
    font-size: 0.9375rem;
}

.auth-card .form-floating .form-control {
    background: var(--ink-950);
    border: 1px solid var(--line-strong);
    border-radius: 10px;
    color: var(--fg);
}

.auth-card .form-floating .form-control:focus {
    background: var(--ink-950);
    border-color: var(--volt) !important;
}

.auth-card .form-floating .form-control:-webkit-autofill {
    -webkit-text-fill-color: var(--fg);
    box-shadow: inset 0 0 0 100px var(--ink-950);
}

.auth-card .form-floating > label {
    color: var(--fg-muted);
}

/* Bootstrap paints a solid chip behind the floated label. */
.auth-card .form-floating > label::after {
    background: transparent !important;
}

.auth-card .input-group-text {
    background: var(--ink-950);
    border: 1px solid var(--line-strong);
    border-radius: 0 10px 10px 0;
    color: var(--fg-muted);
}

.auth-card .form-floating .form-control.border-end-0 {
    border-radius: 10px 0 0 10px;
}

.auth-card .form-control.is-invalid {
    border-color: #ff8a80 !important;
}

.auth-card .text-danger {
    color: #ff8a80 !important;
}

.auth-card .auth-link {
    color: var(--volt);
    font-weight: 500;
    text-decoration: none;
}

.auth-card .auth-link:hover {
    text-decoration: underline;
}

.auth-card .auth-alert {
    border: 0;
    background: rgba(255, 138, 128, 0.12);
    color: #ff8a80;
}

.auth-card .auth-success {
    border: 0;
    background: var(--volt-soft);
    color: var(--volt);
}
</style>
