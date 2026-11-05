<template>
    <div class="overlay-loader">
        <div class="overlay__inner">
            <div class="overlay__content">
                <span class="spinner"></span>
            </div>
        </div>
    </div>
</template>



<style scoped>
.overlay-loader {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: var(--scrim);
    z-index: 9999;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 44px;
    height: 44px;
    display: inline-block;
    border-width: 3px;
    border-color: var(--line-strong);
    border-top-color: var(--accent);
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
</style>