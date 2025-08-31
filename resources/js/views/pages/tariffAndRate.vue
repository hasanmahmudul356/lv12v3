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

    const tableHeaders = ref(["#", "Customer Category", "Unit Rate(Per kwh)", "Effective From",  "Status", "Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['roles']});
    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop :defaultObject="{meter_type:''}"></tableTop>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{ item.meter_type }}</td>
                <td>{{ item.unit_rate }}</td>
                <td>{{ item.effective_from }}</td>
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
                <label class="col-md-4"><strong>Meter Type: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.meter_type" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="role in pageDependencies.roles">
                            <option :value="role.id">{{role.name}}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Unit Rate(Per kwh): </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.unit_rate" v-validate="'required'" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Effective From : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.effective_from" v-validate="'required'" class="form-control"/>
                </div>
            </div>
        </fromModal>
    </dataTable>

</template>
