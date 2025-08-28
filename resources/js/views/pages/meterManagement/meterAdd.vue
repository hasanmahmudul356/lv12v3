<script setup>
    import {dataTable,fromModal,tableTop } from '@/components';

    import {ref, onMounted} from 'vue';
    import {useStore} from 'vuex';
    const store = useStore();
    import {useBase, useHttp, appStore} from '@/lib';

    const {getDependency, submitForm, editData, deleteRecord} = {...useHttp()};
    const {formFilter, formObject, openModal, closeModal, useGetters, dataList,changeStatus,statusBadge, httpRequest, pageDependencies, updateId} = {
        ...useBase(),
        ...appStore(),
        ...useHttp(),
        ...appStore().useGetters('dataList', 'httpRequest', 'pageDependencies', 'updateId')
    };

    const tableHeaders = ref(["#", "Meter Number", "Customer Name", "Connection Date", "Meter Type", "Status", "Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['roles']});
    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop :defaultObject="{customer_id:'',meter_type:''}"></tableTop>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{ item.meter_number }}</td>
                <td>{{ item.customer_id }}</td>
                <td>{{ item.connection_date }}</td>
                <td>{{ item.meter_type }}</td>
                <td><a @click="changeStatus({obj:item})" class="pointer" v-html="statusBadge(item.status)"></a></td>

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
                <label class="col-md-4"><strong>Meter Number : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.meter_number" v-validate="'required'"  class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Customer Name: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.customer_id" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="role in pageDependencies.roles">
                            <option :value="role.id">{{role.name}}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Connection Date : </strong></label>
                <div class="col-md-8">
                    <input type="date" v-model="formObject.connection_date" v-validate="'required'" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Meter Type : </strong></label>
                <div class="col-md-8">
                    <select  v-model="formObject.meter_type" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="role in pageDependencies.roles">
                            <option :value="role.id">{{role.name}}</option>
                        </template>
                    </select>
                </div>
            </div>
        </fromModal>
    </dataTable>

</template>
