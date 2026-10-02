<template>
    <footer class="footer-section">
        <div class="container">
            <div class="glass-card p-4 border-0 mb-4">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-4 text-center text-lg-start">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                            <img src="/icons/soccer.svg" width="30" alt="" class="me-2">
                            <h5 class="text-white fw-bold mb-0">SOCCER<span class="text-gradient">APP</span></h5>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <p class="small mb-0">
                            &copy; {{ (new Date()).getFullYear() }} Tournament Manager. All rights reserved.
                        </p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-end gap-3">
                            <span class="small">Designed by <span class="text-white fw-bold">ProfficTech</span></span>
                            <router-link to="/login"
                                class="btn btn-sm btn-outline-info rounded-circle p-2 d-inline-flex align-items-center justify-content-center"
                                style="width: 35px; height: 35px;">
                                <i class="bi bi-person-fill"></i>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.footer-section {
    padding: 24px 0 40px;
    width: 100%;
    background: var(--brand-navy-900);
    color: rgba(255, 255, 255, 0.6);
}

.footer-section .glass-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: none;
    backdrop-filter: none;
}

.text-gradient {
    background: var(--accent-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

i {
    font-size: 1.1rem;
}
</style>