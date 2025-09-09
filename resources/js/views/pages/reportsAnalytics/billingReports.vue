<script setup>
    import {ref} from "vue";
    const tableHeaders = ref(["#", "Bill Number", "Customer Name", "Billing Month", "Amount", "Penalty", "Total Due", "Payment Status", "Payment Date", "Status",]);
    const dataList = ref([
        {
            bill_number: "BILL-001",
            customer: "John Doe",
            billing_month: "2025-09",
            amount: 2500,
            penalty: 122,
            total_due: 2622,
            payment_status: "Paid",
            payment_date: "2025-09-02",
            status: 1,
        },
        {
            bill_number: "BILL-002",
            customer: "Jane Smith",
            billing_month: "2025-08",
            amount: 1800,
            penalty: 50,
            total_due: 1850,
            payment_status: "Unpaid",
            payment_date: null,
            status: 0,
        },
    ]);
    const searchMonth = ref("");
    const filteredList = ref([]);
    const showTable = ref(false);

    const searchReport = () => {
        if (!searchMonth.value) {
            showTable.value = false;
            return;
        }
        filteredList.value = dataList.value.filter(
            (item) => item.billing_month === searchMonth.value
        );
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
                        <td>{{ item.bill_number }}</td>
                        <td>{{ item.customer }}</td>
                        <td>{{ item.billing_month }}</td>
                        <td>{{ item.amount }}</td>
                        <td>{{ item.penalty }}</td>
                        <td>{{ item.total_due }}</td>
                        <td>
                            <span :class="item.payment_status === 'Paid'? 'badge rounded-pill p-2 text-uppercase px-3 badge bg-success': 'badge rounded-pill p-2 text-uppercase px-3 badge bg-warning'">  <i class="bx bxs-circle me-1"></i>{{ item.payment_status }}</span>
                        </td>
                        <td>{{ item.payment_date ?? "-" }}</td>
                        <td>
                            <span :class="item.status === 1 ? 'badge rounded-pill p-2 text-uppercase px-3 badge bg-success' : 'badge rounded-pill p-2 text-uppercase px-3 badge bg-danger'"> <i class="bx bxs-circle me-1"></i>{{ item.status === 1 ? "Active" : "Inactive" }}</span>
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
