<template>
    <section id="why-us" class="why-us section">

        <div class="container">

            <div class="row g-0">

                <div class="col-xl-5 img-bg" data-aos="fade-up" data-aos-delay="100">
                    <img src="@/assets/img/why-us-bg.jpg" alt="">
                </div>

                <div class="col-xl-7 slides position-relative" data-aos="fade-up" data-aos-delay="200">

                    <div class="swiper init-swiper">

                        <div class="swiper-wrapper">

                            <div class="swiper-slide">
                                <div class="item">
                                    <h3>Unmissable Tournaments. Live Scores. One App.</h3>

                                    <p>Ditch the app overload! Follow all your favorite tournaments, live scores, team
                                        standings, and breaking news in a single, convenient platform. Stay ahead of the
                                        curve, analyze team performance, and never miss a close call with real-time
                                        notifications.</p>
                                </div>
                            </div><!-- End slide item -->

                            <div class="swiper-slide">
                                <div class="item">
                                    <h3>Real-time score updates</h3>

                                    <p>Dynamically displayed scores for ongoing matches, including elapsed time and
                                        additional stats </p>
                                </div>
                            </div><!-- End slide item -->

                            <div class="swiper-slide">
                                <div class="item">
                                    <h3>Interactive leaderboards</h3>

                                    <p>Sortable tables showcasing team rankings, points, wins/losses, and other relevant
                                        stats</p>
                                </div>
                            </div><!-- End slide item -->

                            <div class="swiper-slide">
                                <div class="item">
                                    <h3>Clear and concise schedule</h3>

                                    <p>List upcoming matches with dates, times, and participating teams</p>
                                </div>
                            </div><!-- End slide item -->

                            <div class="swiper-slide">
                                <div class="item">
                                    <h3>In-depth team information</h3>

                                    <p>Detailed pages with team logos, player rosters, bios, and performance statistics
                                    </p>
                                </div>
                            </div><!-- End slide item -->

                        </div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

            </div>

        </div>

    </section>
</template>

<style lang="css" scoped>
.why-us .img-bg {
    min-height: 500px;
    position: relative;
}

.why-us .img-bg img {
    position: absolute;
    inset: 0;
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
}

.why-us .slides {
    background-color: color-mix(in srgb, var(--default-color), transparent 97%);
}

.why-us h3 {
    font-size: 24px;
    font-weight: 700;
}

.why-us h4 {
    font-size: 18px;
    font-weight: 300;
    color: color-mix(in srgb, var(--default-color), transparent 30%);
}

.why-us .swiper {
    margin: 140px 140px 120px 140px;
    overflow: hidden;
}

.why-us .swiper-wrapper {
    height: auto;
}

.why-us .swiper-button-prev:after,
.why-us .swiper-button-next:after {
    font-size: 24px;
    color: color-mix(in srgb, var(--default-color), transparent 50%);
}

.why-us .swiper-button-prev {
    left: 80px;
}

.why-us .swiper-button-next {
    right: 80px;
}

.why-us .swiper-pagination {
    margin-top: 30px;
    position: relative;
}

.why-us .swiper-pagination .swiper-pagination-bullet {
    background-color: color-mix(in srgb, var(--default-color), transparent 50%);
}

.why-us .swiper-pagination .swiper-pagination-bullet-active {
    background-color: var(--accent-color);
}

@media (max-width: 1200px) {
    .why-us .swiper {
        margin: 60px 60px 40px 60px;
    }

    .why-us .swiper-button-prev,
    .why-us .swiper-button-next {
        display: none;
    }
}

@media (max-width: 575px) {
    .why-us .swiper {
        margin: 40px 40px 20px 40px;
    }
}
</style>
