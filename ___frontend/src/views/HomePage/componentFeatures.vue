<template>


    <section id="services" class="services section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Features</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon flex-shrink-0"><i class="bi bi-briefcase" style="color: #f57813;"></i></div>
                    <div>
                        <h4 class="title">Lorem Ipsum</h4>
                        <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias
                            excepturi sint occaecati cupiditate non provident</p>
                        <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon flex-shrink-0"><i class="bi bi-card-checklist" style="color: #15a04a;"></i>
                    </div>
                    <div>
                        <h4 class="title">Dolor Sitema</h4>
                        <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex ea commodo consequat tarad limino ata</p>
                        <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon flex-shrink-0"><i class="bi bi-bar-chart" style="color: #d90769;"></i></div>
                    <div>
                        <h4 class="title">Sed ut perspiciatis</h4>
                        <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                            dolore eu fugiat nulla pariatur</p>
                        <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon flex-shrink-0"><i class="bi bi-binoculars" style="color: #15bfbc;"></i></div>
                    <div>
                        <h4 class="title">Magni Dolores</h4>
                        <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
                            officia deserunt mollit anim id est laborum</p>
                        <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon flex-shrink-0"><i class="bi bi-brightness-high" style="color: #f5cf13;"></i>
                    </div>
                    <div>
                        <h4 class="title">Nemo Enim</h4>
                        <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui
                            blanditiis praesentium voluptatum deleniti atque</p>
                        <a href="#" class="readmore stretched-link"><span>Learn More</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="600">
                    <div class="icon flex-shrink-0"><i class="bi bi-calendar4-week" style="color: #1335f5;"></i>
                    </div>
                    <div>
                        <h4 class="title">Eiusmod Tempor</h4>
                        <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero
                            tempore, cum soluta nobis est eligendi</p>
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
