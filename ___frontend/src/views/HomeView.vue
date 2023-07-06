<template>
  <div class="main min-vh-100  general-body">
    <!-- <nav class="navbar m-0 p-0">
      <div class="container-fluid p-0">
        <span></span>
        <RouterLink to="/about" class="m-3 float-end text-muted small  fw-bolder">About Cup
        </RouterLink>
      </div>
    </nav> -->

    <!-- <div class="tint px-lg-5 min-vh-100"> -->
    <div class="animate__animated  animate__bounceInUp">
      <div class="container pb-5">
        <div class="d-flex justify-content-center align-items-center pt-lg-5 py-4 text-center">
          <div class="col-12 col-md-8 ">
            <img class="dlam_academy" src="@/assets/images/dlam_academy.png" alt="anglican logo">
            <img class="stationery_logo" src="@/assets/images/stationery_logo.png" alt="stationeryfc logo">

            <div class="d-none d-md-block">
              CHURCH OF NIGERIA ANGLICAN COMMUNION
            </div>
            <div class="d-md-none">
              CHURCH OF NIGERIA

            </div>
            <div class="d-md-none">
              ANGLICAN COMMUNION

            </div>

            <div class="fw-bolder fs-5">
              <vue-writer :array="['DIOCESE OF LAGOS MAINLAND']" :iterations='1' :typeSpeed="100" :start="1000" />
            </div>


            <small class=" fst-italic">
              <vue-writer :array="['in conjuction with']" :iterations='1' :typeSpeed="100" :start="2000" />
            </small>

            <div class="fw-bolder fs-5">
              <vue-writer :array="['STATIONERY STORES FC']" :iterations='1' :typeSpeed="100" :start="3000" />
            </div>

            <div class="large-text animate__animated animate__pulse animate__delay-1s">
              DLAM Football Competition 2023
            </div>

            <!-- <div> 11TH AUG. 2023 - 23 AUG. 2023 </div> -->
          </div>
        </div>
      </div>

      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-10 col-lg-6">
            <div class="row justify-content-center g-3">
              <div class="col-md-6">
                <RouterLink to="stats/01h4299vwq5mkm8nzdpcdkskmv" class="btn btn-primary w-100 hover-tilt-Y btn-lg">
                  SEE STATS
                  <i class="bi bi-chevron-right"></i>
                </RouterLink>
              </div>
              <!-- <div class="col-md-6">
                <RouterLink to="stats/01h429avf0ykmdah080bdj5t43" class="btn btn-primary w-100 hover-tilt-Y btn-lg">
                  Women's Category <i class="bi bi-chevron-right"></i></RouterLink>
              </div> -->
            </div>
          </div>
        </div>
      </div>

      <!-- </div> -->
    </div>

    <!-- <shareSite /> -->
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';


onMounted(() => {
  // const $toast = useToast();

  // var today = new Date()
  // var curHr = today.getHours()
  // let text = ''

  // if (curHr < 12) {
  //   text = '<i class="bi bi-sunrise"></i> Good Morning'
  // } else if (curHr < 18) {
  //   text = '<i class="bi bi-brightness-alt-high"></i> Good Afternoon'
  // } else {
  //   text = '<i class="bi bi-moon"></i> Good Evening'
  // }

  // $toast.default(text, { position: 'top-left' });
})
</script>



<style scoped>
.main {
  background-color: var(--bs-light);
  background-image: linear-gradient(90deg, #fff, #fcf5e1, #fff) !important;
  /* background: url("@/assets/images/big_bg.jpg") no-repeat center/cover; */
}

.dlam_academy,
.stationery_logo {
  width: 100px;
  margin-block: 1rem;
}

.large-text {
  font-weight: 700;
  font-size: 3rem;
  margin-top: 1rem;
  color: var(--theme-color-5);
  /* text-shadow: 2px 2px 8px #f3f8a5; */

}

@media screen and (max-width: 992px) {

  .dlam_academy,
  .stationery_logo {
    width: 70px;
  }

  .large-text {
    font-size: 2.7rem;
    margin-top: 3rem;
    line-height: 55px;
  }
}

/* .tint {
  background-color: rgba(234, 248, 185, 0.945);
} */
</style>
