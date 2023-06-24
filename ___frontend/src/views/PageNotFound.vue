<template>
    <div class="wholePage">
        <div class="container">
            <div class="row justify-content-center">
                <div class="text-center imageS" style="font-size: 100px; color: #fff;  opacity: 0.2;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div class="text-center text-white fw-bold" style="font-size: 30px;">PAGE NOT FOUND</div>
                <div class="col-md-4">
                    <div class="text-center text-white">
                        Requested link does not exist in this
                        application, may be expired or broken.
                    </div>
                </div>
                <div class="text-center mt-3">
                    <router-link class="btn  text-decoration-none" to="/">
                        Home Page
                        <i class="bi bi-chevron-right"></i>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.wholePage {
    min-height: 100vh;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: var(--theme-color-2);
}
</style>