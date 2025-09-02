<script setup>
    import {dataTable,fromModal,tableTop } from '@/components';

    import {ref, onMounted} from 'vue';
    import {useStore} from 'vuex';
    const store = useStore();
    import {useBase, useHttp, appStore} from '@/lib';

    const {getDependency, submitForm, editData, deleteRecord} = {...useHttp()};
    const {formFilter, formObject, openModal, closeModal, useGetters, dataList, httpRequest, pageDependencies, updateId,statusBadge,changeStatus,deleteAllRecords} = {
        ...useBase(),
        ...useHttp(),
        ...appStore(),
        ...appStore().useGetters('dataList', 'httpRequest', 'pageDependencies', 'updateId')
    };

    const tableHeaders = ref(['#', {name: '', listObject: dataList}, "Meter Number", "Billing Month", "Start Reading", "End Reading","Units Consumed", "Bill Amount","Bill Status","Status","Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['']});
    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop :defaultObject="{bill_status:''}"></tableTop>
        </template>
        <template v-slot:topRight v-if="dataList.data !== undefined">
            <a class="btn btn-sm btn-outline-danger radius-30 text-uppercase" @click="deleteAllRecords({dataObject:dataList.data})" v-if="dataList.data.some(each => parseInt(each.checked) === 1)">Delete All</a>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td class="checkbox"><input :checked="item.checked" @change="handleSelectAll($event, [item])" class="form-check-input me-3 pointer" type="checkbox"/></td>
                <td>{{ item.meter_number }}</td>
                <td>{{ item.billing_month }}</td>
                <td>{{ item.start_reading }}</td>
                <td>{{ item.end_reading }}</td>
                <td>{{ item.units_consumed }}</td>
                <td>{{ item.bill_amount }}</td>
                <td>
                    <span v-if="item.bill_status == 0">Unpaid</span>
                    <span v-else-if="item.bill_status == 1">Paid</span>
                    <span v-else-if="item.bill_status == 2">Pending</span>
                    <span v-else>Unknown</span>
                </td>
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
                    <input type="text" v-validate="'required'" v-model="formObject.meter_number" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Billing Month : </strong></label>
                <div class="col-md-8">
                    <input type="month" v-model="formObject.billing_month" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Start Reading (kWh) : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.start_reading" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>End Reading (kWh) : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.end_reading" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Units Consumed (kWh) : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.units_consumed" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Bill Amount : </strong></label>
                <div class="col-md-8">
                    <input type="number" step="0.01" v-model="formObject.bill_amount" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Bill Status : </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.bill_status" class="form-control">
                        <option value="">Select</option>
                        <option value="0">Unpaid</option>
                        <option value="1">Paid</option>
                        <option value="2">Pending</option>
                    </select>
                </div>
            </div>

        </fromModal>
    </dataTable>

</template>
