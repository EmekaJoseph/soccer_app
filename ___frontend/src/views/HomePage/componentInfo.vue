<template>
    <section id="why-us" class="why-us section position-relative overflow-hidden">
        <div class="container position-relative z-1">
            <div class="row align-items-center gy-5">
                <div class="col-xl-5" data-aos="fade-right">
                    <div class="image-wrapper position-relative">
                        <div class="glass-decoration animate-float"></div>
                        <img src="@/assets/img/why-us-bg.jpg" alt="Soccer Experience"
                            class="img-fluid rounded-4 shadow-lg border border-white border-opacity-10">
                    </div>
                </div>

                <div class="col-xl-7" data-aos="fade-left">
                    <div class="ps-xl-5">
                        <!-- <div class="badge bg-primary-theme px-3 py-2 mb-3 rounded-pill">
                            <i class="bi bi-info-circle-fill me-2 text-gradient"></i>
                            Why Choose Us
                        </div> -->
                        <h2 class="display-5 fw-bold text-white mb-4">Unmatched Soccer <span
                                class="text-gradient">Experience</span></h2>

                        <div class="swiper init-swiper glass-card p-4 p-md-5">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="item">
                                        <h3 class="text-white mb-3">Complete Tournament Ecosystem</h3>
                                        <p class="text-white-50">From grassroots leagues to professional cups, manage
                                            every league, result, and team in one premium dashboard.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="item">
                                        <h3 class="text-white mb-3">Player-Centric Tracking</h3>
                                        <p class="text-white-50">Monitor individual player performance, goals, cards,
                                            and substitutions across all match events.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="item">
                                        <h3 class="text-white mb-3">Fan Engagement Ready</h3>
                                        <p class="text-white-50">Give your fans a professional experience with
                                            live-updating results, sleek standings, and mobile-ready match centers.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="item">
                                        <h3 class="text-white mb-3">Strategic Team Planning</h3>
                                        <p class="text-white-50">Organize squads, track historical performance, and
                                            manage team logos and rosters with professional precision.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination mt-4"></div>
                            <div class="d-none d-md-flex swiper-navigation position-absolute bottom-0 end-0 p-4">
                                <button class="btn btn-outline-light btn-sm swiper-button-prev me-2"><i
                                        class="bi bi-chevron-left"></i></button>
                                <button class="btn btn-outline-light btn-sm swiper-button-next"><i
                                        class="bi bi-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="decoration top-0 start-0 translate-middle bg-primary-theme opacity-10 rounded-circle"
            style="width: 400px; height: 400px; filter: blur(80px);"></div>
        <div class="decoration bottom-0 end-0 translate-middle-y bg-info opacity-10 rounded-circle"
            style="width: 300px; height: 300px; filter: blur(60px);"></div>
    </section>
</template>

<style lang="css" scoped>
.why-us {
    background: #0f2027;
    /* Dark matching hero */
    padding: 100px 0;
}

.image-wrapper {
    position: relative;
    padding: 20px;
}

.glass-decoration {
    position: absolute;
    top: -20px;
    right: -20px;
    width: 60%;
    height: 60%;
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    z-index: -1;
}

.why-us .swiper {
    position: relative;
    overflow: hidden;
}

.why-us .item h3 {
    font-size: 1.5rem;
    font-weight: 700;
}

.swiper-navigation .btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.swiper-navigation .btn:hover {
    background: var(--accent-gradient);
    border-color: transparent;
    color: #000;
}

.decoration {
    position: absolute;
    pointer-events: none;
    z-index: 0;
}

.z-1 {
    z-index: 1;
}

:deep(.swiper-pagination-bullet) {
    background: rgba(255, 255, 255, 0.3);
    opacity: 1;
}

:deep(.swiper-pagination-bullet-active) {
    background: var(--accent-color);
}
</style>
