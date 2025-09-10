<script setup>
    import {dataTable, fromModal, tableTop} from "@/components";
    import {ref, onMounted, watch} from 'vue';
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



    watch(
        () => [formObject.value.meter_no, formObject.value.bill_month],
        async ([meter_no, bill_month]) => {
            if (!meter_no || !bill_month) return;

            const response = await httpReq({
                url: '/recordPayment',
                method: 'get',
                params: { meter_no, bill_month },
            });

            // console.log('response:', response);

            if (response && response.bill_amount) {
                formObject.value.bill_amount = response.bill_amount;
            }
        }
    );




</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop></tableTop>
        </template>
        <template v-slot:data>
            <tr  v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{ item.meter_no}}</td>
                <td>{{item.bill_month}}</td>
                <td>{{item.bill_amount}}</td>
                <td>{{item.payment_amount}}</td>
                <td>{{item.payment_date}}</td>
                <td>{{item.payment_method}}</td>
                <td>{{item.payment_status}}</td>
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
                    <input type="text" v-model="formObject.meter_no" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Bill Month: </strong></label>
                <div class="col-md-8">
                    <input type="month" v-model="formObject.bill_month" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Bill Amount: </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.bill_amount" class="form-control" readonly />
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
                    <select v-model="formObject.payment_method" class="form-control">
                        <option value="">Select</option>
                        <option value="1">Cash</option>
                        <option value="2">Bank</option>
                        <option value="3">Card</option>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Payment Status: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.payment_status" class="form-control">
                        <option value="">Select</option>
                        <option value="1">Paid</option>
                        <option value="2">Partially Paid</option>
                        <option value="3">Unpaid</option>
                    </select>
                </div>
            </div>

        </fromModal>
    </dataTable>
</template>

