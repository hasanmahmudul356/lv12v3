<script setup>
    import {useHttp, useBase, appStore} from '@/lib';
    import {useStore} from 'vuex';
    const store = useStore();

    const {openModal, formFilter, getDataList, httpRequest} = {...useBase(), ...appStore(store), ...useHttp(store), ...appStore(store).useGetters('httpRequest')};

</script>
<template>
    <div class="align-items-center mb-4 gap-3">
        <div class="row">
            <div class="col-md-9 text-left">
                <div class="row">
                    <div class="col-md-3">
                        <input v-model="formFilter.keyword" type="text" class="form-control radius-30" placeholder="Search Order">
                    </div>
                    <slot name="filter"></slot>
                    <div class="col-md-2">
                        <button v-if="httpRequest" type="button" class="btn btn-light radius-30">
                            <i class='bx bx-loader bx-spin text-warning'></i>
                            <span class="text-warning">Loading..</span>
                        </button>
                        <button v-else @click="getDataList(store)" type="button" class="btn btn-outline-primary radius-30">
                            <i class='bx bx-search text-danger'></i>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <a @click="openModal()" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add</a>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>