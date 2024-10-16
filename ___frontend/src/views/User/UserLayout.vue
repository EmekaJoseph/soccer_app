<template>
    <div class="bg-admin min-vh-100">
        <ComponentHeader />
        <ComponentSideBar />
        <div class="py-5 px-lg-4 main-content ">
            <RouterView />
        </div>


    </div>
</template>

<script setup lang="ts">
import ComponentSideBar from './ComponentSideBar.vue';
import ComponentHeader from './ComponentHeader.vue';

</script>

<style scoped>
.bg-admin {
    /* background-color: var(--bs-light); */
    background-color: #f5f6f8;
}

.main-content {
    margin-left: 250px;
}

@media (max-width: 767px) {
    .main-content {
        margin-left: 0px;
    }
}

@media (min-width: 767px) {
    .navbar-toggler-icon {
        display: none;
    }
}

/*  */
</style>