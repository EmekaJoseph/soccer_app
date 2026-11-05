<template>
    <span class="brand">
        <svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="12" fill="#c8f31d" />
            <g fill="#0a0c0b" stroke="#0a0c0b" stroke-width="1.3" stroke-linecap="round">
                <path d="M12 7.8 16 10.7 14.5 15.4H9.5L8 10.7Z" />
                <path d="M12 7.8V2M16 10.7 21.5 8.9M14.5 15.4 17.9 20M9.5 15.4 6.1 20M8 10.7 2.5 8.9" fill="none" />
            </g>
        </svg>
        LiveFixtura
    </span>
</template>

<style scoped>
.brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--fg);
    font-family: var(--font-sans);
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: -0.02em;
}
</style>
