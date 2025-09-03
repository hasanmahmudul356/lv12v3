<script setup>
    import {dataTable, fromModal, tableTop} from "@/components";
    import {ref, onMounted} from 'vue';
    import {useStore} from 'vuex';
    const store = useStore();
    import {useBase, useHttp, appStore} from '@/lib';

    const {getDependency, submitForm, editData, deleteRecord} = {...useHttp()};
    const {changeStatus, statusBadge, formFilter, formObject, openModal, closeModal, useGetters, dataList, httpRequest, pageDependencies, updateId} = {
        ...useBase(),
        ...useHttp(),
        ...appStore(),
        ...appStore().useGetters('dataList', 'httpRequest', 'pageDependencies', 'updateId')
    };

    const tableHeaders = ref(["#", "Area Name", "Area Code", "Zone", "City", "Officer Name", "Status", "Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['officer']});
    });

</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop></tableTop>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{item.name}}</td>
                <td>{{item.code}}</td>
                <td>{{item.zone}}</td>
                <td>{{item.city}}</td>
                <td>{{item.area_staff ? item.area_staff.name : '-'}}</td>
                <td>
                    <a @click="changeStatus({obj:item})" class="pointer" v-html="statusBadge(item.status)"></a>
                </td>
                <td>
                    <a @click="editData({data:item, id:item.id, modal:'fromModal'})" class="btn btn-outline-secondary action">
                        <i class='bx bxs-edit text-warning'></i>
                    </a>
                    <a @click="deleteRecord({targetId:item.id,listIndex:index, listObject:dataList.data})" class="btn btn-outline-secondary action">
                        <i class='bx bxs-trash text-danger'></i>
                    </a>
                </td>
            </tr>
        </template>

        <fromModal @submit="submitForm({
            modal: 'fromModal',
            callback: function (retData) {
                Object.assign(formObject, {});
                getDataList();
            }
        })">
            <div class="row mb-2">
                <label class="col-md-4"><strong>Area Name : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.name" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Area Code : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.code" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Zone : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.zone" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>City : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.city" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Area Officer: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.officer_id" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="item in pageDependencies.officer">
                            <option :value="item.id">{{item.name}}</option>
                        </template>
                    </select>
                </div>
            </div>
        </fromModal>
    </dataTable>
</template>

