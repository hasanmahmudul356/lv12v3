<script setup>
    import {useStore} from 'vuex';
    const store = useStore();
    import {ref, onMounted, nextTick, watch} from 'vue';
    import { useBase, useHttp, appStore } from '@/lib';
    const {_l, getImage, allMenus, loadConfigurations, useGetters} = {...useBase(), ...appStore(), ...useHttp()};
    const {Config, appConfigs} = useGetters('Config', 'appConfigs');

    onMounted(() => {
        loadConfigurations({
            callback: async (retData) => {
                await nextTick(() => {
                    $("#menu").metisMenu();

                    let currentUrl = window.location.href;
                    let activeItem = $(".metismenu li a").filter(function () {
                        return this.href === currentUrl;
                    });
                    if (activeItem.length) {
                        let li = activeItem.parent().addClass("mm-show");

                        while (li.length) {
                            let parentUl = li.parent();
                            if (parentUl.hasClass("metismenu")) break;

                            parentUl.addClass("mm-show");  // expand submenu
                            dd(parentUl);
                            li = parentUl.parent().addClass("mm-active"); // mark parent li active
                        }
                    }
                });

            }
        });
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

    const menu_keyword = ref('');
    const menus = ref([]);

    const icon = (icon) => {
        return icon ? icon : 'bx bx-home-alt';
    };

    const addMenu = (menu) => {
        menus.value.push({
            display_name: menu.display_name,
            id: menu.id,
            link: menu.link,
            name: menu.name,
            status: menu.status,
            submenus: [],
        });
    };
    watch(menu_keyword, (newVal) => {
        menus.value = [];
        if (newVal !== '') {
            allMenus.value.forEach(pMenu => {
                addMenu(pMenu);

                pMenu.submenus?.forEach(sMenu => {
                    addMenu(sMenu);

                    sMenu.submenus?.forEach(ssMenu => {
                        addMenu(ssMenu);
                    });
                });
            });

            const foundValue = menus.value.filter(eachData =>
                eachData.display_name.toLowerCase().includes(newVal.toLowerCase())
            );

            store.commit('allMenus', foundValue);
        } else {
            store.commit('allMenus', Config.value.menus);
        }
    });

</script>

<template>
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img :src="appConfigs.app_logo" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text">{{appConfigs.app_name}}</h4>
            </div>
            <div class="toggle-icon ms-auto">
                <i class='bx bx-arrow-back'></i>
            </div>
        </div>
        <div class="search-bar flex-grow-1">
            <div class="position-relative search-bar-box">
                <input v-model="menu_keyword" type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
            </div>
        </div>
        <ul class="metismenu" id="menu">
            <template v-for="(mainMenu, mainIndex) in allMenus">
                <li v-if="mainMenu.submenus.length > 0">
                    <router-link class="has-arrow" :to="mainMenu.link">
                        <div class="parent-icon"><i :class="icon(mainMenu.icon)"></i></div>
                        <div class="menu-title">{{_l(mainMenu.name)}}</div>
                    </router-link>
                    <ul>
                        <template v-for="(sub2Menu, sub2Index) in mainMenu.submenus">
                            <li v-if="sub2Menu.submenus.length > 0">
                                <router-link class="has-arrow" :to="sub2Menu.link">
                                    <i :class="icon(sub2Menu.icon)"></i>
                                    <span>{{_l(sub2Menu.name)}}</span>
                                </router-link>
                                <ul>
                                    <template v-for="(sub3Menu, sub3Index) in sub2Menu.submenus">
                                        <li v-if="sub3Menu.submenus.length > 0">
                                            <router-link class="has-arrow" :to="sub3Menu.link">
                                                <i :class="icon(sub3Menu.icon)"></i>{{_l(sub3Menu.name)}}</router-link>
                                            <ul>
                                                <template v-for="(sub4Menu, sub4Index) in sub3Menu.submenus">
                                                    <li>
                                                        <router-link :to="sub4Menu.link">
                                                            <i :class="icon(sub4Menu.icon)"></i>
                                                            {{_l(sub4Menu.name)}}
                                                        </router-link>
                                                    </li>
                                                </template>
                                            </ul>
                                        </li>
                                        <li v-else>
                                            <router-link :to="sub3Menu.link">
                                                <i :class="icon(sub3Menu.icon)"></i>
                                                {{_l(sub3Menu.name)}}
                                            </router-link>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                            <li v-else>
                                <router-link :to="sub2Menu.link">
                                    <i :class="icon(sub2Menu.icon)"></i>
                                    {{_l(sub2Menu.name)}}
                                </router-link>
                            </li>
                        </template>
                    </ul>
                </li>
                <li v-else>
                    <router-link :to="mainMenu.link">
                        <div class="parent-icon">
                            <i :class='icon(mainMenu.icon)'></i>
                        </div>
                        <div class="menu-title">{{_l(mainMenu.name)}}</div>
                    </router-link>
                </li>
            </template>
        </ul>
    </div>
</template>
