<script setup>
    import {ref, onMounted, computed, nextTick} from 'vue';
    import {useStore} from 'vuex';
    const store = useStore();
    import { useBase, useHttp, appStore } from '@/lib';

    const {getImage, allMenus, loadConfigurations} = {
        ...useBase(),
        ...appStore(),
        ...useHttp()
    };

    onMounted(() => {
        loadConfigurations(async (retData) => {
            await nextTick();

            $("#menu").metisMenu();

            let e = window.location;
            let o = $(".metismenu li a").filter(function () {
                    return this.href === e.href
                }).parent().addClass("mm-active");

            while (o.is("li")) {
                o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")
            }
        })
    });

    onMounted(() => {
        if (window.innerWidth <= 1024) {
            $(".mobile-toggle-menu").on("click", function () {
                $(".wrapper").addClass("toggled");
            });

            $(".toggle-icon").on("click", function () {
                if ($(".wrapper").hasClass("toggled")) {
                    $(".wrapper").removeClass("toggled");
                    $(".sidebar-wrapper").unbind("hover");
                } else {
                    $(".wrapper").addClass("toggled");
                    $(".sidebar-wrapper").hover(
                        function () {
                            $(".wrapper").addClass("sidebar-hovered");
                        },
                        function () {
                            $(".wrapper").removeClass("sidebar-hovered");
                        }
                    );
                }
            });

            $(window).on("scroll", function () {
                $(this).scrollTop() > 300
                    ? $(".back-to-top").fadeIn()
                    : $(".back-to-top").fadeOut();
            });

            $(".back-to-top").on("click", function () {
                $("html, body").animate({ scrollTop: 0 }, 600);
                return false;
            });
        }
    });

</script>

<template>
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img :src="getImage('backend/images/logo-icon.png')" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text">Dashtrans</h4>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
            </div>
        </div>
        <ul class="metismenu" id="menu">
            <template v-for="(mainMenu, mainIndex) in allMenus">
                <li v-if="mainMenu.submenus.length > 0">
                    <router-link class="has-arrow" :to="mainMenu.link">
                        <div class="parent-icon"><i :class="mainMenu.icon"></i></div>
                        <div class="menu-title">{{mainMenu.display_name}}</div>
                    </router-link>
                    <ul>
                        <template v-for="(sub2Menu, sub2Index) in mainMenu.submenus">
                            <li v-if="sub2Menu.submenus.length > 0">
                                <router-link class="has-arrow" :to="sub2Menu.link">
                                    <i :class="sub2Menu.icon"></i>
                                    <span>{{sub2Menu.display_name}}</span>
                                </router-link>
                                <ul>
                                    <template v-for="(sub3Menu, sub3Index) in sub2Menu.submenus">
                                        <li v-if="sub3Menu.submenus.length > 0">
                                            <router-link class="has-arrow" :to="sub3Menu.link">
                                                <i :class="sub3Menu.icon"></i>{{sub3Menu.display_name}}</router-link>
                                            <ul>
                                                <template v-for="(sub4Menu, sub4Index) in sub3Menu.submenus">
                                                    <li>
                                                        <router-link :to="sub4Menu.link">
                                                            <i :class="sub4Menu.icon"></i>{{sub4Menu.display_name}}</router-link>
                                                    </li>
                                                </template>
                                            </ul>
                                        </li>
                                        <li v-else>
                                            <router-link :to="sub3Menu.link">
                                                <i :class="sub3Menu.icon"></i>{{sub3Menu.display_name}}</router-link>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                            <li v-else>
                                <router-link :to="sub2Menu.link">
                                    <i :class="sub2Menu.icon"></i>{{sub2Menu.display_name}}</router-link>
                            </li>
                        </template>
                    </ul>
                </li>
                <li v-else>
                    <router-link :to="mainMenu.link">
                        <div class="parent-icon"><i :class='mainMenu.icon'></i>
                        </div>
                        <div class="menu-title">{{mainMenu.display_name}}</div>
                    </router-link>
                </li>
            </template>
        </ul>
    </div>
</template>
