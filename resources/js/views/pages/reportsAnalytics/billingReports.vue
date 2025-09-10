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
    const tableHeaders = ref(["#", "Bill Number", "Customer Name", "Billing Month", "Amount", "Penalty", "Total Due", "Payment Status", "Payment Date", "Status",]);
    const {getDataList, } = useHttp();

    onMounted(() => {
        getDataList();
    });
    const searchMonth = ref("");
    const filteredList = ref([]);
    const showTable = ref(false);

    const searchReport = () => {
        if (!searchMonth.value) {
            showTable.value = false;
            return;
        }
        filteredList.value = (dataList.value.data || []).filter((item) => {
            const formattedBillMonth = item.bill_month.substring(0, 7);
            return formattedBillMonth === searchMonth.value;
        });
        showTable.value = true;
    };

</script>

<template>
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row mb-3 align-items-center">
                <div class="row mb-2">
                    <label class="col-md-1">Billing Month:</label>
                    <div class="col-md-8">
                        <input type="month" v-model="searchMonth" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" @click="searchReport">
                            <i class="bx bx-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showTable" class="table-responsive shadow-sm border rounded p-3">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                    <tr>
                        <th v-for="(head, i) in tableHeaders" :key="i">{{ head }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filteredList" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>Bill Number</td>
                        <td>Customer</td>
                        <td>{{ item.bill_month }}</td>
                        <td>{{ item.payment_amount }}</td>
                        <td>20</td>
                        <td>1420</td>
                        <td>
<!--                            <span :class="item.payment_status === 'Paid'? 'badge rounded-pill p-2 text-uppercase px-3 badge bg-success': 'badge rounded-pill p-2 text-uppercase px-3 badge bg-warning'">  <i class="bx bxs-circle me-1"></i>{{ item.payment_status }}</span>-->
                        </td>
                        <td>{{ item.payment_date ?? "-" }}</td>
                        <td>
<!--                            <span :class="item.status === 1 ? 'badge rounded-pill p-2 text-uppercase px-3 badge bg-success' : 'badge rounded-pill p-2 text-uppercase px-3 badge bg-danger'"> <i class="bx bxs-circle me-1"></i>{{ item.status === 1 ? "Active" : "Inactive" }}</span>-->
                        </td>
                    </tr>
                    <tr v-if="filteredList.length === 0">
                        <td colspan="10" class="text-center text-muted">
                            No records found for {{ searchMonth }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
