<script setup>
    import {dataTable,fromModal,tableTop } from '@/components';

    import {ref, onMounted} from 'vue';
    import {useStore} from 'vuex';
    const store = useStore();
    import {useBase, useHttp, appStore} from '@/lib';

    const {getDependency, submitForm, editData, deleteRecord} = {...useHttp()};
    const {formFilter, formObject, openModal, closeModal, useGetters, dataList, httpRequest, pageDependencies, updateId,statusBadge,changeStatus,deleteAllRecords} = {
        ...useBase(),
        ...appStore(),
        ...appStore().useGetters('dataList', 'httpRequest', 'pageDependencies', 'updateId')
    };

    const tableHeaders = ref(['#', {name: '', listObject: dataList}, "Name", "Email", "Phone", "Address","Meter Type", "House Holding No","Birthday", "Image","Status","Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['roles']});
    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop :defaultObject="{role_id:''}"></tableTop>
        </template>
        <template v-slot:topRight v-if="dataList.data !== undefined">
            <a class="btn btn-sm btn-outline-danger radius-30 text-uppercase" @click="deleteAllRecords({dataObject:dataList.data})" v-if="dataList.data.some(each => parseInt(each.checked) === 1)">Delete All</a>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td class="checkbox"><input :checked="item.checked" @change="handleSelectAll($event, [item])" class="form-check-input me-3 pointer" type="checkbox"/></td>
                <td>{{ item.name }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.phone_number }}</td>
                <td>{{ item.address }}</td>
                <td>{{ item.meter_type }}</td>
                <td>{{ item.house_holding_no }}</td>
                <td>{{ item.dob }}</td>
                <td>{{ item.meter_type }}</td>
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
                <label class="col-md-4"><strong>Name : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-validate="'required'" v-model="formObject.name" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Email : </strong></label>
                <div class="col-md-8">
                    <input type="email" v-model="formObject.email" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Phone Number : </strong></label>
                <div class="col-md-8">
                    <input type="tel" v-model="formObject.phone_number" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Address : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.address" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>House Holding No : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.house_holding_no" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Area : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.area" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Birthday : </strong></label>
                <div class="col-md-8">
                    <input type="date" v-model="formObject.dob" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Meter Type : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.meter_type" class="form-control"/>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>User : </strong></label>
                <div class="col-md-8">
                    <input type="number" v-model="formObject.user_id" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Image : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.image" class="form-control"/>
                </div>
            </div>

        </fromModal>
    </dataTable>

</template>
