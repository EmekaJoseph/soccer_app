<template>
    <div class="bg-admin min-vh-100">
        <nav class="navbar py-4 px-lg-3 shadow-sm bg-white sticky-lg-top ">
            <div class="container-fluid">
                <div>
                    <span ref="btnClose" class="navbar-toggler-icon cursor-pointer" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"></span>
                    <span class="ms-4 text-uppercase fw-bold">{{ route.name }}</span>
                </div>
                <div class="dropdown d-none d-md-block">
                    <button class=" bg-transparent border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ data.firstame ?? data.email }}
                    </button>
                    <ul class="dropdown-menu">
                        <li @click="logOut"><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="py-5 container ">

            <RouterView />

        </div>


        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">SOCCER APP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <RouterLink to="/user/dashboard"><i class="bi bi-view-stacked"></i> Dashboard</RouterLink>
                    </li>
                    <li v-if="data.role == 'admin'" class="list-group-item">
                        <RouterLink to="/user/teams"><i class="bi bi-people"></i> Teams</RouterLink>
                    </li>
                    <li class="list-group-item">
                        <RouterLink to="/user/schedules"><i class="bi bi-calendar2-event"></i> Schedules</RouterLink>
                    </li>
                    <li class="list-group-item">
                        <RouterLink to="/user/results"><i class="bi bi-list-check"></i> Results</RouterLink>
                    </li>
                    <li class="list-group-item">
                        <RouterLink to="/user/live"><i class="bi bi-circle"></i> Live Update</RouterLink>
                    </li>

                    <li v-if="data.role == 'admin'" class="list-group-item">
                        <RouterLink to="/user/predictions"><i class="bi bi-command"></i> Predictions</RouterLink>
                    </li>

                    <li class="list-group-item mt-5">
                        <div class="cursor-pointer" @click="logOut"><i class="bi bi-box-arrow-left"></i> Logout </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore';

const authStore = useAuthStore()

const data: any = authStore.getUserData()

const route = useRoute()
const router = useRouter()
const btnClose = ref<any>()

watch(() => route.path, () => {
    btnClose.value.click()
})

function logOut() {
    btnClose.value.click()
    try {
        api.logout()
    } catch (error) {
        // 
    }
    finally {
        authStore.logout()
        router.replace({ path: '/' })
    }

}

</script>

<style scoped>
.dropdown-menu {
    /* font-size: 14px; */
    min-width: 100% !important;
    border: 1px solid #eee;
    /* border-top: 3px solid v-bind(ccThk); */
    padding-top: 0px;
    margin: 0px;
}

@media (max-width: 768px) {
    .offcanvas {
        width: 250px !important;
    }
}


.list-group {
    margin-top: 20px;
}

.list-group-item {
    border: 0px;
    margin-block: 7px;
}

.list-group-item a {
    text-decoration: none;
    padding: 5px 50px 5px 5px;
    font-size: 18px;
    color: #000;
}

.list-group-item .active {
    background-color: var(--bs-light);
}

.bg-admin {
    /* background-color: var(--bs-light); */
    background-color: #f5f6f8;
}
</style>