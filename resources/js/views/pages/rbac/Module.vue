<script setup>
    import {ref, reactive, onMounted} from 'vue'
    import {useStore} from 'vuex'
    import {useRouter} from 'vue-router'
    const store  = useStore();
    const router  = useRouter();

    import {dataTable, tableTop, fromModal, pageTop} from '@/components'
    import moduleForm from "@/views/pages/rbac/moduleForm.vue";

    import {appStore, useHttp, useBase} from "@/lib";

    const {useGetters, getDataList, submitForm, editData, deleteRecord, getDependency,changeStatus, openModal, handleSelectAll, statusBadge} = {
        ...appStore(),
        ...useHttp(),
        ...useBase()
    };
    const { httpRequest, dataList, pageDependencies } = useGetters('httpRequest', 'dataList', 'pageDependencies');

    const tableHeaders = reactive(['#', {name: '', listObject: dataList}, 'Display Name', 'Name', 'Status', 'Action']);
    const permissions = reactive(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'status']);

    const formObject = reactive({
        permissions: [],
    });

    onMounted(()=>{
        getDataList();
        getDependency({dependency : ['permissions']});
    });

</script>
<template>
    <dataTable :loading="httpRequest" :headings="tableHeaders" :loader="false">
        <template v-slot:topRight>
            <a class="btn btn-danger">Delete All</a>
        </template>
        <template v-slot:data>
            <template v-for="(item, index) in dataList.data" :key="item.id">
                <tr >
                    <td>{{index+1}}</td>
                    <td><input :checked="item.checked" @change="handleSelectAll($event, [item])" class="form-check-input me-3 pointer" type="checkbox"/></td>
                    <td>{{ item.display_name }}</td>
                    <td>{{ item.name }}</td>
                    <td><a @click="changeStatus({obj:item})" class="pointer" v-html="statusBadge(item.status)"></a></td>
                    <td>
                        <a @click="editData({id:item.id, modal:'fromModal', formObj : formObject})" class="btn btn-outline-secondary action">
                            <i class='bx bxs-edit text-warning'></i>
                        </a>
                        <a @click="deleteRecord({targetId:item.id,listIndex:index, listObject:dataList.data})" class="btn btn-outline-secondary action">
                            <i class='bx bxs-trash text-danger'></i>
                        </a>
                    </td>
                </tr>
                <template v-for="(subItem, index2) in item.submenus" :key="item.id">
                    <tr >
                        <td>{{index+1}}.{{index2+1}} </td>
                        <td><input :checked="subItem.checked" @change="handleSelectAll($event, [subItem])" class="form-check-input me-3 pointer" type="checkbox"></td>
                        <td>{{ subItem.display_name }}</td>
                        <td>{{ subItem.name }}</td>
                        <td><a @click="changeStatus({obj:subItem})" class="pointer" v-html="statusBadge(subItem.status)"></a></td>
                        <td>
                            <a @click="editData(subItem, 'fromModal', formObject)" class="btn btn-outline-secondary action">
                                <i class='bx bxs-edit text-warning'></i>
                            </a>
                            <a @click="deleteRecord({targetId:subItem.id,listIndex:index, listObject:dataList.data})" class="btn btn-outline-secondary action">
                                <i class='bx bxs-trash text-danger'></i>
                            </a>
                        </td>
                    </tr>
                </template>
            </template>
        </template>

        <fromModal title="Module Form" modal-size="modal-xs" @submit="submitForm({
            data: formObject,
            modal: 'fromModal',
            callback: function (retData) {
                Object.assign(formObject, { permissions: [] });
                getDataList();
            }
        })">
            <moduleForm :dependencies="pageDependencies" :formObject="formObject"></moduleForm>
        </fromModal>
    </dataTable>
</template>

<style scoped>

</style>