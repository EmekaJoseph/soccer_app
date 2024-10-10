<template>
    <div class="sidebar">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <RouterLink to="/user/dashboard"><i class="bi bi-view-stacked"></i> Dashboard</RouterLink>
            </li>
            <li v-if="data.role == 'admin'" class="list-group-item">
                <RouterLink to="/user/teams"><i class="bi bi-people"></i> Teams</RouterLink>
            </li>
            <li class="list-group-item">
                <RouterLink to="/user/matches"><i class="bi bi-calendar2-event"></i> Matches</RouterLink>
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
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import api from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore';

const authStore = useAuthStore()

const data: any = authStore.getUserData()

const router = useRouter()


function logOut() {
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


.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 250px !important;
    /* border-right: 1px solid v-bind('templateStore.borderInDark') !important; */
    background-color: #ffffff;
    overflow-y: auto;
    padding-right: 0px;
    padding-top: 100px;
}

.main-content {
    margin-left: 250px;
}

@media (max-width: 767px) {
    .sidebar {
        display: none;
    }

    .main-content {
        margin-left: 0px;
    }
}
</style>