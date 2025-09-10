<script setup>
    import {watch} from 'vue';
    import {appStore} from "./lib";

    const {httpRequest} = {...appStore().useGetters('httpRequest')};

    watch(httpRequest, (status) => {
        if (status) {
            document.querySelectorAll('.page-content button, .page-content input').forEach((el) => {
                    el.setAttribute('disabled', 'disabled')
                })
        } else {
            document.querySelectorAll('.page-content button, .page-content input').forEach((el) => {
                el.removeAttribute('disabled')
            });
        }
    })
</script>

<template>
    <router-view></router-view>
</template>
