<script setup>
    import {ref, reactive, onMounted} from 'vue'
    import {useStore} from 'vuex'
    import {useRouter} from 'vue-router'

    import {dataTable, tableTop, fromModal, pageTop} from '@/components'
    import moduleForm from "@/views/pages/rbac/moduleForm.vue";
    import {appStore, useHttp, useBase} from "@/lib";
    const store  = useStore();
    const router  = useRouter();

    const {_l, useGetters, getDataList, submitForm, editData, deleteRecord, getDependency,changeStatus, openModal, handleSelectAll, statusBadge, deleteAllRecords} = {
        ...appStore(),
        ...useHttp(),
        ...useBase()
    };
    const { httpRequest, dataList, pageDependencies } = useGetters('httpRequest', 'dataList', 'pageDependencies');

    const tableHeaders = reactive(['sl', 'User','Controller','Action','Route','IP','Date']);
    const permissions = reactive(['directives.js', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'status']);

    onMounted(()=>{
        getDataList();
    });

</script>
<template>
    <dataTable :headings="tableHeaders" :loader="true">
        <template v-slot:tableTop>
            <tableTop></tableTop>
        </template>
        <template v-slot:data>
            <template v-for="(log, index) in dataList.data" :key="log.id">
                <tr>
                    <td>{{ index + 1 }}</td>
                    <td>{{ log.user?.name || 'Guest' }}</td>
                    <td>{{ log.controller ? `${log.controller}` : '-' }}</td>
                    <td>{{ log.method }}</td>
                    <td>{{ log.route_name }}</td>
                    <td>{{ log.ip_address }}</td>
                    <td>{{ new Date(log.created_at).toLocaleString() }}</td>
                </tr>
            </template>
        </template>
    </dataTable>
</template>

<style scoped>

</style>