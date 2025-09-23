<script setup>
    import AuthHeader from "@/web/layouts/AuthHeader.vue";
    import AuthFooter from "@/web/layouts/AuthFooter.vue";
    import SideMenu from "@/web/layouts/SideMenu.vue";

    import {useBase, appStore} from '@/lib';
    const {getImage, authUser} = {...useBase(), ...appStore()};
    const allMenus = [];
</script>

<template>
    <div class="main-wrapper">
        <AuthHeader />
        <div class="section fix" :data-bg-image="getImage(null, 'frontend/images/bg/contact-1.jpg')" :style="{ backgroundImage: `url(${getImage(null, 'frontend/images/bg/contact-1.jpg')})` }">
            <div class="container-fluid">
                <div class="row mbn-30">
                    <div class="col-md-10 offset-md-1 mb-30">
                        <div class="row">
                            <div class="col-md-3">
                                <SideMenu></SideMenu>
                            </div>
                            <div class="col-md-9">
                                <router-view></router-view>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AuthFooter />
    </div>
</template>
