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

    const tableHeaders = ref(["#", "Meter Number", "Billing Month", "Bill Amount", "Payment Amount", "Payment Date", "Payment Mode", "Payment Status",  "Status", "Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        // getDependency({dependency : ['']});

    });

</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop></tableTop>
        </template>
        <template v-slot:data>
            <tr  v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{ item.meter ? item.meter.meter_number : '-' }}</td>
                <td>{{item.reading_date}}</td>
                <td>{{item.current_reading}}</td>
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
                <label class="col-md-4"><strong>Meter No: </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.meter_no" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Bill Month: </strong></label>
                <div class="col-md-8">
                    <input type="date" v-model="formObject.bill_month" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Bill Amount: </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.bill_amount" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Payment Amount: </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.payment_amount" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Payment Date: </strong></label>
                <div class="col-md-8">
                    <input type="date" v-model="formObject.payment_date" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Payment Mode: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.payment_mode" class="form-control">
                        <option value="">Select</option>
                        <option value="1">Cash</option>
                        <option value="2">Bank</option>
                        <option value="3">Card</option>
                    </select>
                </div>
            </div>

        </fromModal>
    </dataTable>
</template>

