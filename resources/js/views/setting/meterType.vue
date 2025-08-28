<script setup>
import {dataTable, fromModal, tableTop} from "@/components";
import {ref, onMounted} from 'vue';
import {useStore} from 'vuex';
const store = useStore();
import {useBase, useHttp, appStore} from '@/lib';

const {getDependency, submitForm, editData, deleteRecord} = {...useHttp()};
const {formFilter, formObject, openModal, closeModal, useGetters, dataList, httpRequest, pageDependencies, updateId} = {
    ...useBase(),
    ...appStore(),
    ...appStore().useGetters('dataList', 'httpRequest', 'pageDependencies', 'updateId')
};

const tableHeaders = ref(["#", "Name", "Username", "Status", "Actions"]);
const {getDataList, httpReq} = useHttp();

onMounted(() => {
    getDataList();
    getDependency({dependency : ['users']});
});
</script>

<template>
   <dataTable :headings="tableHeaders" :setting="true">
       <template v-slot:tableTop>
           <tableTop></tableTop>
       </template>
       <template v-slot:data>
           <tr>
               <td></td>
               <td></td>
               <td></td>
               <td>
                   <a class="badge rounded-pill p-2 text-uppercase px-3" >
                       <i class='bx bxs-circle me-1'></i>
                       <span></span>
                   </a>
               </td>
               <td>
                   <a  class="btn btn-outline-secondary action">
                       <i class='bx bxs-edit text-warning'></i>
                   </a>
                   <a  class="btn btn-outline-secondary action">
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
               <label class="col-md-4"><strong>Name : </strong></label>
               <div class="col-md-8">
                   <input type="text" v-model="formObject.name" class="form-control"/>
               </div>
           </div>
           <div class="row mb-2">
               <label class="col-md-4"><strong>Role : </strong></label>
               <div class="col-md-8">
                   <select type="text" v-model="formObject.user_id" class="form-control">
                       <option value="">Select</option>
                       <template v-for="user in pageDependencies.users">
                           <option :value="user.id">{{user.name}}</option>
                       </template>
                   </select>
               </div>
           </div>
       </fromModal>
   </dataTable>
</template>
