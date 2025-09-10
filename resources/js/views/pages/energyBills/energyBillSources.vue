
<script setup>
    import {dataTable,fromModal,tableTop } from '@/components';

    import {ref, onMounted} from 'vue';
    import {useStore} from 'vuex';
    const store = useStore();
    import {useBase, useHttp, appStore} from '@/lib';

    const {getDependency, submitForm, editData, deleteRecord} = {...useHttp()};
    const {formFilter, formObject, openModal, closeModal, useGetters,httpReq, dataList,changeStatus,statusBadge, httpRequest, pageDependencies, updateId} = {
        ...useBase(),
        ...appStore(),
        ...useHttp(),
        ...appStore().useGetters('dataList', 'httpRequest', 'pageDependencies', 'updateId')
    };

    const tableHeaders = ref(["#", "Energy Type","Bill Month", "Unit", "Unit Rate", "Customer Unit", "Status", "Actions"]);
    const {getDataList, } = useHttp();

    onMounted(() => {
        getDataList();
    });
    const calculate = async ()=>{
        const response = await httpReq({
            url: '/customerKwh',
            method: 'get',
            params: { type: formObject.value.type, unit: formObject.value.unit }
        });
        if (response )
        {
            formObject.value.customer_unit = response.customerUnit;
            formObject.value.totalCustomers = response.totalCustomers;
        }

    }

</script>
<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop >
            <tableTop :defaultObject="{type:''}"></tableTop>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{ item.type === 1 ? 'Generator' : item.type === 2 ? 'Solar' : item.type ===3 ? 'Nesco' : '' }}</td>
                <td>{{ item.billing_month}}</td>
                <td>{{ item.unit }}</td>
                <td>{{ item.unit_rate }}</td>
                <td>{{ item.customer_unit }}</td>
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
                <label class="col-md-4"><strong>Energy Type: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.type" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <option value="1">Generator</option>
                        <option value="2">Solar</option>
                        <option value="3">Nesco</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Billing Month : </strong></label>
                <div class="col-md-8">
                    <input type="month" v-validate="'required'" v-model="formObject.billing_month" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Unit(kwh): </strong></label>
                <div class="col-md-7">
                    <input type="number" v-model="formObject.unit" v-validate="'required'" step="0.01" class="form-control"/>
                </div>
                <div class="col-md-1 font-24" @click="calculate">
                    <i class="bx bx-calculator"></i>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Total Customer: </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.totalCustomers" class="form-control" readonly/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Customer Unit(kwh): </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.customer_unit" class="form-control" readonly/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Unit Rate(Per kwh): </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.unit_rate" step="0.01" class="form-control"/>
                </div>
            </div>
        </fromModal>

    </dataTable>

</template>