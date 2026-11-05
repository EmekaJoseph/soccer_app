<template>
    <main class="public-page not-found">
        <span class="ui-eyebrow">404</span>
        <h1>This page doesn't exist.</h1>
        <p>The link may have expired or been typed incorrectly.</p>
        <RouterLink class="ui-btn ui-btn-primary ui-btn-lg" to="/">
            Back to home <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </RouterLink>
    </main>
</template>

<style scoped>
.not-found {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 24px 16px;
    text-align: center;
}

.not-found h1 {
    margin-bottom: 12px;
    font-size: clamp(2rem, 6vw, 3.25rem);
}

.not-found p {
    margin-bottom: 28px;
}
</style>
