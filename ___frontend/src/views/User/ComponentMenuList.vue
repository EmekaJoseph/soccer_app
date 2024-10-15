<template>

    <ul class="list-group list-group-flush">
        <li v-for="menu in menuList" class="list-group-item" :class="{ 'active-list': menu.link == route.path }">
            <RouterLink v-if="menu.roles?.includes(data.role)" :to="menu.link"><i :class="menu.icon"></i>
                {{ menu.name }}
            </RouterLink>
        </li>


        <!-- <li class="list-group-item mt-5">
            <div class="cursor-pointer" @click="logOut"><i class="bi bi-box-arrow-left"></i> Logout </div>
        </li> -->
    </ul>
</template>

<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router';
import api from '@/store/axiosManager'
import { useAuthStore } from '@/store/authStore';

const menuList = [
    { name: 'Dashboard', icon: 'bi bi-view-stacked', link: '/user/dashboard', roles: ['user', 'admin'] },
    { name: 'Teams', icon: 'bi bi-people', link: '/user/teams', roles: ['admin'] },
    { name: 'Matches', icon: 'bi bi-calendar2-event', link: '/user/matches', roles: ['user', 'admin'] },
    { name: 'Results', icon: 'bi bi-list-check', link: '/user/results', roles: ['user', 'admin'] },
    { name: 'Live', icon: 'bi bi-circle', link: '/user/Live', roles: ['user', 'admin'] },
    { name: 'Predictions', icon: 'bi bi-command', link: '/user/predictions', roles: ['admin'] },
]

const authStore = useAuthStore()

const data: any = authStore.getUserData()

const router = useRouter()
const route = useRoute()


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
.list-group {
    margin-top: 20px;
    background-color: transparent !important;
}

.list-group-item {
    border: 0px;
    margin-block: 7px;
    background-color: transparent;
}

.list-group-item a {
    text-decoration: none;
    padding: 5px 50px 5px 5px;
    font-size: 18px;
    color: #ffff;
}

.list-group-item a:hover,
.list-group-item .active {
    color: var(--theme-color-4) !important;
    font-weight: bolder;
}

.active-list {
    background-color: #04141bf0;
    border-right: solid 3px var(--theme-color-4) !important;
}
</style>