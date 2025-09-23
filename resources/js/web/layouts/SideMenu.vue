<script setup>
    import {useBase, appStore, useHttp} from '@/lib';
    const {getImage, authUser, urlGenerate} = {...useBase(), ...appStore(), ...useHttp()};
    const allMenus = [];
</script>
<template>
    <div class="mobile-hidden mt-2">
        <div class="text-center">
            <div class="form-group image_upload" :style="{ backgroundImage: 'url(' + getImage(null, 'images/upload.png') + ')' }" style="background-size: 300px !important">
                <img :class="`img-thumbnail pointer`" :src="getImage(null, 'images/female_staff.jpg')">
                <input name="thumbnail" style="display: none;" id="image" type="file">
            </div>
            <span class="text-warning" style="font-size: 13px">(Max Size 2mb)</span>
        </div>
        <div class="bg-white pt-10">
            <div class="text-center">
                <h3><strong>Mahmudul Hasan</strong></h3>
            </div>
            <hr>
            <ul class="my-account-navigation nav">
                <li>
                    <router-link to="/dashboard"><i class="fa fa-user"></i>Dashboard</router-link>
                </li>
                <li>
                    <router-link to="/profile"><i class="fa fa-user"></i>Profile</router-link>
                </li>
                <li>
                    <router-link to="/complains"><i class="fa fa-user"></i>Complains</router-link>
                </li>
                <li>
                    <router-link to="/complain-add"><i class="fa fa-user"></i>New Complain</router-link>
                </li>
                <template v-for="(menu, index) in allMenus">
                    <li>
                        <router-link :to="menu.link"><i class="fa fa-arrow-right"></i>{{menu.name}}</router-link>
                    </li>
                </template>
                <li><a :href="urlGenerate('auth/logout')"><i class="fa fa-sign-out"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</template>

<style scoped>

</style>