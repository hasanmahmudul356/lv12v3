<template>
    <div>
        <div class="flex justify-center items-center h-full">
            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-green-500"></div>
        </div>
        <div class="wrapper">
            <LeftSidebar />
            <Header />
            <router-view />
            <div class="overlay toggle-icon"></div>
            <a href="#" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
            <appFooter />
        </div>
        <themeSwitcher></themeSwitcher>
    </div>
</template>

<script setup>
import Header from "@/views/layouts/Header.vue";
import LeftSidebar from "@/views/layouts/LeftSidebar.vue";
import appFooter from "@/views/layouts/appFooter.vue";
import themeSwitcher from "@/views/layouts/themeSwitcher.vue";
</script>

<style scoped>
/* Your styles here */
</style>
