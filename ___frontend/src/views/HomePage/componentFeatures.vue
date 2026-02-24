<template>
    <section id="services" class="services section bg-primary-theme position-relative overflow-hidden">
        <!-- Section Title -->
        <div class="container section-title text-center mb-5" data-aos="fade-up">
            <div
                class="badge bg-info bg-opacity-10 text-info px-3 py-2 mb-3 rounded-pill border border-info border-opacity-25">
                Superior Features
            </div>
            <h2 class="display-5 fw-bold text-white mb-3">Everything You Need To <span class="text-gradient">Win</span>
            </h2>
            <p class="text-white-50 max-width-700 mx-auto">Manage all your favorite tournaments, live scores, team
                standings, and breaking news in a single, convenient platform.</p>
        </div>

        <div class="container position-relative z-1">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6" v-for="(feature, index) in features" :key="index" :data-aos="'fade-up'"
                    :data-aos-delay="index * 100">
                    <div class="service-item glass-card h-100 p-4 transition-all hover-tilt-Y">
                        <div class="icon-box mb-4 d-inline-flex align-items-center justify-content-center rounded-3"
                            :style="{ backgroundColor: feature.bgColor }">
                            <i :class="['bi', feature.icon]" :style="{ color: feature.iconColor }"></i>
                        </div>
                        <h4 class="title text-white mb-3">{{ feature.title }}</h4>
                        <p class="description text-white-50">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative backgrounds -->
        <div class="decoration top-50 start-50 translate-middle bg-info opacity-5 rounded-circle"
            style="width: 800px; height: 800px; filter: blur(120px);"></div>
    </section>
</template>

<script setup lang="ts">
const features = [
    {
        title: "Live Match Engine",
        description: "Follow every kick with real-time score tracking, substitutions, and detailed match event updates.",
        icon: "bi-lightning-fill",
        iconColor: "#f57813",
        bgColor: "rgba(245, 120, 19, 0.1)"
    },
    {
        title: "Automated Standings",
        description: "Watch your league tables and tournament brackets update instantly as match results are published.",
        icon: "bi-bar-chart-fill",
        iconColor: "#15a04a",
        bgColor: "rgba(21, 160, 74, 0.1)"
    },
    {
        title: "Professional Rosters",
        description: "Manage complete player profiles, including positions, jersey numbers, and career statistics history.",
        icon: "bi-people-fill",
        iconColor: "#d90769",
        bgColor: "rgba(217, 7, 105, 0.1)"
    },
    {
        title: "Team Management",
        description: "Comprehensive tools for organizing squads, uploading logos, and tracking all-time team performance.",
        icon: "bi-shield-shaded",
        iconColor: "#15bfbc",
        bgColor: "rgba(21, 191, 188, 0.1)"
    },
    {
        title: "Public Live Centers",
        description: "Share beautiful, responsive live links automatically for fans to follow matches from anywhere.",
        icon: "bi-share-fill",
        iconColor: "#f5cf13",
        bgColor: "rgba(245, 207, 19, 0.1)"
    },
    {
        title: "Instant Results",
        description: "Publish final scores and match reports immediately to keep your tournament history accurate and up-to-date.",
        icon: "bi-check-circle-fill",
        iconColor: "#4facfe",
        bgColor: "rgba(79, 172, 254, 0.1)"
    }
];
</script>

<style lang="css" scoped>
.services {
    padding: 100px 0;
}

.service-item {
    border: 1px solid rgba(255, 255, 255, 0.05);
    background: rgba(255, 255, 255, 0.03);
}

.service-item:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(0, 242, 254, 0.3);
}

.icon-box {
    width: 60px;
    height: 60px;
    font-size: 28px;
}

.max-width-700 {
    max-width: 700px;
}

.decoration {
    position: absolute;
    pointer-events: none;
    z-index: 0;
}

.z-1 {
    z-index: 1;
}

.transition-all {
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}
</style>
