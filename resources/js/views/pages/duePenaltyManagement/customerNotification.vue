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

    const tableHeaders = ref(["#", "Customer", "Type Title", "Message", "Delivery Method",  "Status", "Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['customer']});

    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop :defaultObject="{customer_id:'',}"></tableTop>
        </template>
        <template v-slot:data>
            <tr  v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td>{{ item.customer ? item.customer.name : '-' }}</td>
                <td>{{item.title}}</td>
                <td>{{item.message}}</td>
                <td>{{item.delivery_method}}</td>
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
                <label class="col-md-4"><strong>Customer Name: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.customer_id" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="item in pageDependencies.customer">
                            <option :value="item.id">{{ item.name }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Title : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-validate="'required'" v-model="formObject.title" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong> Message: </strong></label>
                <div class="col-md-8">
                    <textarea class="form-control" v-validate="'required'"  v-model="formObject.message" ></textarea>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Delivery Method: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.delivery_method" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                    </select>
                </div>
            </div>

        </fromModal>

    </dataTable>
</template>

