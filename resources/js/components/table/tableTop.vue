<script setup>
    import {useHttp, useBase, appStore} from '@/lib';
    const props = defineProps({
        formObject: {type: Object, default: () => ({})},
        defaultObject: {type: Object, default: () => ({})},
    });

    const {openModal, formFilter, getDataList, httpRequest} = {
        ...useBase(),
        ...appStore(),
        ...useHttp(),
        ...appStore().useGetters('httpRequest')
    };

</script>
<template>
    <div class="align-items-center mb-3 gap-3">
        <div class="row">
            <div class="col-md-9 text-left">
                <div class="row">
                    <div class="col-md-3">
                        <input v-model="formFilter.keyword" type="text" class="form-control radius-30" placeholder="Search Order">
                    </div>
                    <slot name="filter"></slot>
                    <div class="col-md-2">
                        <button v-if="httpRequest" type="button" class="btn btn-light radius-30">
                            <i class='bx bx-loader bx-spin text-white'></i>
                            <span class="text-white text-uppercase">Loading..</span>
                        </button>
                        <button v-else @click="getDataList()" type="button" class="btn btn-outline-dark radius-30">
                            <i class='bx bx-search text-white'></i>
                            <span class="text-white text-uppercase">Search</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <a @click="openModal({defaultObject : defaultObject})" class="btn btn-outline-secondary radius-30 mt-2 mt-lg-0 text-uppercase"><i class="bx bxs-plus-square"></i>Add New</a>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>