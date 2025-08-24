<script setup>
    import {ref, defineProps} from 'vue'
    import {appStore, useHttp} from '@/lib';

    import {useStore} from 'vuex';

    const store = useStore();

    const {formFilter, useGetters, routeMeta, getDataList} = {...appStore(), ...useHttp()};
    const {dataList} = useGetters('dataList', 'httpRequest');
    const perPage = ref([10, 20, 50, 100, 200]);

    const props = defineProps({
        listPage: {type: Boolean, default: true},
        title: {type: String, default: ''},
    });

    const title = (props.title !== '') ? props.title : (routeMeta('title') ?? 'Data List');
    const listPage = props.listPage ?? (routeMeta('listPage') ?? true);
</script>

<template>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="#"><i class="bx bx-home-alt"></i></a>
                </li>
                <template v-if="listPage">
                    <li class="breadcrumb-item active" aria-current="page">
                        <select @change="getDataList" class="btn btn-outline-secondary perPage" v-model="formFilter.per_page">
                            <option v-for="page in perPage" :value="page">{{title}} {{page}}</option>
                        </select>
                    </li>
                    <li v-if="dataList.total !== undefined">
                        <span class="page_top_text">|| <b>TOTAL</b> : {{ dataList.total }} || <b>SHOWING</b> : {{ dataList.from }} to {{ dataList.to }} (<b>Per page</b> : {{ dataList.per_page }})</span>
                    </li>
                </template>
                <template v-else>
                    <li class="breadcrumb-item active" aria-current="page">{{title}}</li>
                </template>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <slot></slot>
        </div>
    </div>
</template>

<style scoped>

</style>