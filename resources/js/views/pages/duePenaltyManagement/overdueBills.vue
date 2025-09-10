<script setup>
    import {dataTable, fromModal, tableTop} from "@/components";

    import {ref,watch,onMounted} from 'vue';
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

    const tableHeaders = ref(["#", "Meter No","Due Date", "Due Amount", "Penalty Amount", "Total Due", "Status", "Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['meter_num','meter','bill_info','customer']});

    });
    watch(
        () => [formObject.value.meter_no, formObject.value.billing_month],
        async ([meter_no, billing_month]) => {
            if (!meter_no || !billing_month) return;

            const response = await httpReq({
                url: '/over_due',
                method: 'get',
                params: { meter_no, billing_month },
            });

            if (response) {
                formObject.value.due_date = response.due_date;
                formObject.value.due_amount = response.due_amount;
                formObject.value.penalty_due = response.penalty_due;
                formObject.value.total_due = response.total_due_bill;
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
                <td>{{item.due_date}}</td>
                <td>{{item.due_amount}}</td>
                <td>{{item.penalty_due}}</td>
                <td>{{item.total_due}}</td>
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
                <label class="col-md-4"><strong>Meter No : </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.meter_no" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="item in pageDependencies.meter">
                            <option :value="item.meter_number">{{ item.meter_number }}</option>
                        </template>
                    </select>

                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Billing Month : </strong></label>
                <div class="col-md-8">
                    <input type="month" v-model="formObject.billing_month" class="form-control" />
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Due Date : </strong></label>
                <div class="col-md-8">
                    <input type="date" v-model="formObject.due_date" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Due Amount : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.due_amount" class="form-control" readonly/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Penalty Amount : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.penalty_due" class="form-control" readonly/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Total Due : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.total_due" class="form-control" readonly/>
                </div>
            </div>

        </fromModal>
    </dataTable>
</template>

