<template>
    <div class="d-flex justify-content-center align-items-center py-5" role="status" aria-label="Loading">
        <span class="loader"></span>
    </div>
</template>

<style scoped>
.loader {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 3px solid rgba(125, 140, 155, 0.2);
    border-top-color: #1fb6e8;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
