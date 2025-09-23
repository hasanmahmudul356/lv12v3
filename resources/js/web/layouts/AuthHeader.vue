<script setup>
    import {useBase, appStore} from '@/lib';
    const {getImage, authUser} = {...useBase(), ...appStore()};
    const allMenus = [];
</script>

<template>
    <div class="header-section d-none d-lg-block">
        <div class="header-bottom">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="primary-menu">
                            <a href="/">
                                <img :src="getImage(null, 'frontend/images/logo/logo_1.png')" alt="Lawyer Attorneys">
                            </a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="primary-menu">
                            <nav>
                                <ul>
                                    <li><a href="login">Login</a></li>
                                    <li><a href="register">Registration</a></li>
                                    <li><a href="track">Track Complain</a></li>
                                    <li> <a>| </a> </li>
                                    <li><a href="admin">Admin Login</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-header d-block d-lg-none">
        <div class="container">
            <div class="row">
                <div class="col d-flex align-items-center">
                    <div class="header-logo mobile-header-logo">
                        <a href="/">
                            <img :src="getImage(null, 'frontend/images/logo/logo_1.png')" alt="Lawyer Attorneys">
                        </a>
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center">
                    <div class="mobile-header-right-toggles">
                        <button class="mobile-menu-toggle"><i class="ion-navicon"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="offcanvas" class="offcanvas-section">
        <button class="offcanvas-close" data-target="#offcanvas">&times;</button>
        <div class="offcanvas-wrap">
            <div class="inner">

                <div class="offcanvas-menu">
                    <nav>
                        <ul>
                            <li><a href="login">Login</a></li>
                            <li><a href="register">Registration</a></li>
                            <li><a href="track">Track Complain</a></li>
                            <li><a href="admin">Admin Login</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="offcanvas-info mt-auto">
                    <p><i class="fa fa-envelope-o"></i><a href="mailto:contact@yoursite.com">contact@yoursite.com</a></p>
                    <p><i class="fa fa-phone"></i><a href="#">1.800.123.4569</a></p>
                </div>
                <div class="offcanvas-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-google-plus"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-youtube-play"></i></a>
                    <a href="#"><i class="fa fa-vimeo"></i></a>
                </div>

            </div>
        </div>
    </div>
    <div class="offcanvas-overlay"></div>
</template>

<style scoped>

</style>