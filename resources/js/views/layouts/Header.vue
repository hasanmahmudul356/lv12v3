<script setup>
    import { ref, onMounted, onBeforeUnmount } from 'vue';
    import { useBase, useHttp, appStore } from '@/lib';
    import { useI18n } from 'vue-i18n'
    const { t, locale } = useI18n();
    const user = ref(null);

    const { getImage,httpReq, formatDate, useGetters, urlGenerate, submitForm, assignStore } = {...useBase(), ...useHttp(), ...appStore()};
    const {localization, authUser, appNotifications, appConfigs} = useGetters('localization', 'authUser','appNotifications', 'appConfigs');

    let dfLocale = ref(window.locale || 'en');
    const switchLang = (lang) => {
        submitForm({
            data : {request:'locale',locale:lang},
            url : `api/profile`,
            validation : false,
            callback : function(retData){
                locale.value = lang;
                dfLocale.value = lang;
            }
        });
    };

    const loadNotifications = async () =>{
        const notificationData = appNotifications.value;
        let data = await httpReq({
            method : 'get',
            url : urlGenerate('api/app_notification'),
            params : {
                limit:notificationData.limit,
                skip:notificationData.skip
            }
        });
        if (data){
            assignStore('appNotifications', data)
        }
    };

    let intervalId = null;
    onMounted(()=>{
        let configValue = appConfigs.value;
        loadNotifications();

        const intervalMs = parseInt(configValue.notify_per_minuit) * 60 * 1000;
        if (intervalMs > 0){
            intervalId = setInterval(async () => {
                loadNotifications();
            }, intervalMs);
        }
    });
    onBeforeUnmount(() => {
        if (intervalId) clearInterval(intervalId)
    })


</script>

<template>
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand gap-3">
                <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                </div>
                <div class="search-bar flex-grow-1">
                    <div class="position-relative search-bar-box">
                        <input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                        <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                    </div>
                </div>
                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center gap-1">
                        <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                            <a class="nav-link" href="#"><i class='bx bx-search'></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret text-uppercase" href="#" data-bs-toggle="dropdown">
                               <code> {{localization.find(l => l.locale === dfLocale)?.locale}}</code>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <template v-for="(locale, lIndex) in localization">
                                    <li @click="switchLang(locale.locale)">
                                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                                            <span class="ms-2">{{locale.name}}</span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </li>
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">{{appNotifications.total}}</span>
                                <i class='bx bx-bell'></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#">
                                    <div class="msg-header">
                                        <p class="msg-header-title">Notifications</p>
                                        <p class="msg-header-badge">{{appNotifications.total}} New</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list">
                                    <template v-if="appNotifications.data !== undefined">
                                        <a class="dropdown-item" v-for="item in appNotifications.data" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img :src="getImage('(backend/images/avatars/avatar-1.png')" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">{{item.title}}<span class="msg-time float-end">{{item.created_at}}</span></h6>
                                                    <p class="msg-info">The standard chunk of lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                <a href="#">
                                    <div class="text-center msg-footer">
                                        <button class="btn btn-light w-100">View All Notifications</button>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="user-box dropdown px-3">
                    <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img :src="getImage('backend/images/avatars/avatar-2.png')" class="user-img" alt="user avatar">
                        <div class="user-info">
                            <p class="user-name mb-0">{{authUser.name}}</p>
                            <p class="designattion mb-0">Web Designer</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <router-link class="dropdown-item d-flex align-items-center" to="/profile"><i class="bx bx-user fs-5"></i><span>Profile</span></router-link>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-download fs-5"></i><span>Downloads</span></a>
                        </li>
                        <li>
                            <div class="dropdown-divider mb-0"></div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" :href="urlGenerate('logout')"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>