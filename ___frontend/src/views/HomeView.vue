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
            <img class="anglican_logo bouncing" src="@/assets/images/anglican_logo.png" alt="anglican_logo">
            <div>DIOCESE OF LAGOS MAINLAND</div>
            <div class="fw-bolder fs-5 d-none d-md-block">CHURCH OF NIGERIA ANGLICAN COMMUNION</div>
            <div class="fw-bolder fs-5 d-md-none">CHURCH OF NIGERIA <br> ANGLICAN COMMUNION</div>
            <div class="large-text animate__animated animate__pulse animate__delay-1s">Bishop's Cup 2023</div>

            <div> 11TH AUG. 2023 - 23 AUG. 2023 </div>
          </div>
        </div>
      </div>



      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-10 col-lg-6">
            <div class="row justify-content-center g-3">
              <div class="col-md-6">
                <RouterLink to="stats/01h4299vwq5mkm8nzdpcdkskmv" class="btn btn-primary w-100 hover-tilt-Y btn-lg">Men's
                  Category <i class="bi bi-chevron-right"></i></RouterLink>
              </div>
              <div class="col-md-6">
                <RouterLink to="stats/01h429avf0ykmdah080bdj5t43" class="btn btn-primary w-100 hover-tilt-Y btn-lg">
                  Women's Category <i class="bi bi-chevron-right"></i></RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- </div> -->
    </div>
  </div>
</template>

<style scoped>
.main {
  background-color: var(--bs-light);
  background-image: linear-gradient(90deg, #fff, #fcf5e1, #fff) !important;
  /* background: url("@/assets/images/big_bg.jpg") no-repeat center/cover; */
}

.anglican_logo {
  width: 100px;
  margin-block: 1rem;
}

.large-text {
  font-weight: 700;
  font-size: 5rem;
  margin-top: 1rem;
  color: var(--theme-color-5);
  text-shadow: 2px 2px 8px #f3f8a5;

}

@media screen and (max-width: 992px) {
  .anglican_logo {
    width: 70px;
  }

  .large-text {
    font-size: 3.5rem;
    margin-top: 3rem;
    line-height: 55px;
  }
}

/* .tint {
  background-color: rgba(234, 248, 185, 0.945);
} */
</style>
