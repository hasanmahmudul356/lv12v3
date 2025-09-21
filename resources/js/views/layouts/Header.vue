<script setup>
    import { ref, onMounted, onBeforeUnmount } from 'vue';
    import { useBase, useHttp, appStore } from '@/lib';
    import { useI18n } from 'vue-i18n'
    const { t, locale } = useI18n();
    const user = ref(null);

    const {_l,  getImage,httpReq, formatDate, useGetters, urlGenerate, submitForm, assignStore } = {...useBase(), ...useHttp(), ...appStore()};
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

    let notification = ref(false);
    let intervalId = null;

    const loadNotifications = async (page = null) => {
        const notificationData = appNotifications.value;

        const params = {
            limit: notificationData.limit,
            page: page ?? notificationData.page
        };
        try {
            const data = await httpReq({
                method: 'get',
                url: urlGenerate('api/app_notification'),
                params
            });
            if (data) {
                assignStore('appNotifications', data);
            }
        } catch (err) {
            console.error('Failed to load notifications:', err);
        }
    };

    const startNotificationLoop = () => {
        stopNotificationLoop();
        const loop = async () => {
            let notifyPerMinute = parseInt(appConfigs.value.notify_per_minuit);
            let intervalMs = Math.floor(60000 / notifyPerMinute);

            await loadNotifications();
            intervalId = setTimeout(loop, intervalMs);
        };
        loop();
    };

    const stopNotificationLoop = () => {
        if (intervalId) {
            clearTimeout(intervalId);
            intervalId = null;
        }
    };

    const openNotification = () => {
        notification.value = !notification.value;
        if (notification.value) {
            appNotifications.value.page = 1;
            const intervalMs = Math.floor(60000 / parseInt(appConfigs.value.notify_per_minuit));
            if (intervalMs > 0) startNotificationLoop();
        }
    };

    onMounted(() => {
        loadNotifications();

        const intervalMs = Math.floor(60000 / parseInt(appConfigs.value.notify_per_minuit));
        if (intervalMs > 0) startNotificationLoop(intervalMs);
    });

    onBeforeUnmount(() => {
        stopNotificationLoop();
    });




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
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative pointer" :class="notification ? 'show' : ''" @click="openNotification"><span class="alert-count">{{appNotifications.total}}</span>
                                <i class='bx bx-bell'></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" :class="notification ? 'show' : ''">
                                <a>
                                    <div class="msg-header">
                                        <p class="msg-header-title" @click="openNotification">{{_l('notification')}}s ({{appNotifications.total}})</p>
                                        <p class="msg-header-badge">
                                            <i class="bx bxs-arrow-from-right" @click="loadNotifications(parseInt(appNotifications.page)-1)"></i>
                                        </p>
                                        <p class="msg-header-badge">
                                            <i class="bx bxs-arrow-from-left" @click="loadNotifications(parseInt(appNotifications.page)+1)"></i>
                                        </p>
                                        <p class="msg-header-badge text-danger" v-if="notification" @click="openNotification">X</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list">
                                    <template v-if="appNotifications.data !== undefined">
                                        <a class="dropdown-item" v-for="item in appNotifications.data">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img :src="getImage(null, 'backend/images/avatars/notification.png')" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1 pointer" @click="(()=>{
                                                item.toggle = !item.toggle;
                                                httpReq({ method: 'get', url: `${urlGenerate('api/app_notification')}/${item.id}`})
                                                })">
                                                    <h6 class="msg-name">{{item.title}}<span class="msg-time float-end">{{item.created_at}}</span></h6>
                                                    <p class="msg-info">{{item.short_text}}</p>
                                                </div>
                                            </div>
                                            <template v-if="item.toggle">
                                                <hr>
                                                <div>
                                                    <template v-if="item.link">
                                                        <router-link :to="item.link">Details</router-link>
                                                    </template>
                                                    <p class="msg-info">{{item.notification}}</p>
                                                </div>
                                            </template>
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
                        <img :src="getImage(authUser.image, 'backend/images/avatars/avatar-26.png')" class="user-img" alt="user avatar">
                        <div class="user-info">
                            <p class="user-name mb-0">{{authUser.name}}</p>
                            <p class="designattion mb-0">{{authUser.designation}}</p>
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
                            <router-link class="dropdown-item d-flex align-items-center" to="/activities"><i class="bx bx-home-circle fs-5"></i><span>Activities</span></router-link>
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