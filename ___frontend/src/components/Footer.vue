
<template>
    <div class="footer container-fluid">
        <!-- <footer class="d-flex flex-wrap justify-content-center align-items-center"> -->
        <footer class="row justify-content-center align-items-center">
            <div class="col-lg-4">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6  text-center">
                        <span class="text-white my-2">&copy; {{ (new Date()).getFullYear() }} | DLAMYOUTHS
                        </span>
                    </div>

                </div>
            </div>



        </footer>
        <div class="text-center mt-2 text-proffix"><span class="text-mute fst-italic">designed by </span>
            ProfficTech - 08139590011
        </div>
    </div>
</template>



<style scoped>
.footer {
    /* background: rgb(245, 243, 243); */
    width: 100%;
    padding: 45px;
    /* font-weight: bold; */
    font-size: 15px;
    background-color: var(--theme-color-6);
    /* background-color: #000; */

    position: absolute;
    bottom: 0;
    width: 100%;
    height: 1rem;
    padding-bottom: 80px;
}

.text-mute {
    color: rgb(109, 107, 107);
}

.text-proffix {
    color: rgb(160, 158, 158);
    font-size: 10px;
}
</style>