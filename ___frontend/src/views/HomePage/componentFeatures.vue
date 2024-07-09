<template>


    <section id="services" class="services section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Features</h2>
            <p>Ditch the app overload! Manage all your favorite tournaments, live scores, team
                standings, and breaking news in a single, convenient platform.</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon flex-shrink-0"><i class="bi bi-app-indicator" style="color: #f57813;"></i></div>
                    <div>
                        <h4 class="title">Real-time score updates</h4>
                        <p class="description">Dynamically displayed scores for ongoing matches, including elapsed time
                            and
                            additional stats</p>
                        <!-- <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div>
                <!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon flex-shrink-0"><i class="bi bi-card-checklist" style="color: #15a04a;"></i>
                    </div>
                    <div>
                        <h4 class="title">Interactive leaderboards</h4>
                        <p class="description">Sortable tables showcasing team rankings, points, wins/losses, and other
                            relevant
                            stats</p>
                        <!-- <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon flex-shrink-0"><i class="bi bi-calendar4-week" style="color: #d90769;"></i></div>
                    <div>
                        <h4 class="title">Clear and concise schedule</h4>
                        <p class="description">List upcoming matches with dates, times, and participating teams</p>
                        <!-- <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon flex-shrink-0"><i class="bi bi-info-circle" style="color: #15bfbc;"></i></div>
                    <div>
                        <h4 class="title">In-depth team information</h4>
                        <p class="description">Detailed pages with team logos, player rosters, bios, and performance
                            statistics.</p>
                        <!-- <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon flex-shrink-0"><i class="bi bi-people" style="color: #f5cf13;"></i>
                    </div>
                    <div>
                        <h4 class="title">User accounts</h4>
                        <p class="description">Create a personalized experience by following specific tournaments,
                            teams, and players.</p>
                        <!-- <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="600">
                    <div class="icon flex-shrink-0"><i class="bi bi-link-45deg" style="color: #1335f5;"></i>
                    </div>
                    <div>
                        <h4 class="title">Auto Create link</h4>
                        <p class="description">Create tournament link automatically and send to the public for
                            information updates.</p>
                        <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

            </div>

        </div>

    </section>
</template>

<style lang="css" scoped>
.services .service-item {
    position: relative;
    height: 100%;
    margin-bottom: 30px;
}

.services .service-item .icon {
    margin-right: 20px;
}

.services .service-item .icon i {
    color: var(--accent-color);
    font-size: 40px;
    line-height: 0;
    transition: 0.3s;
}

.services .service-item:hover .icon i {
    color: var(--accent-color);
}

.services .service-item .title {
    color: var(--heading-color);
    font-weight: 700;
    margin-bottom: 10px;
    font-size: 20px;
    transition: 0.3s;
}

.services .service-item .description {
    font-size: 15px;
    color: color-mix(in srgb, var(--default-color), transparent 30%);
    margin-bottom: 10px;
}

.services .service-item .readmore {
    display: flex;
    align-items: center;
    color: color-mix(in srgb, var(--default-color), transparent 50%);
    transition: 0.3s;
    font-weight: 700;
    font-size: 14px;
}

.services .service-item .readmore i {
    margin-left: 8px;
}

.services .service-item:hover .title,
.services .service-item:hover .readmore,
.services .service-item:hover .icon i {
    color: var(--accent-color);
}
</style>
