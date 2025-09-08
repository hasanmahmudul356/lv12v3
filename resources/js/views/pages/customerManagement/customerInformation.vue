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

    const tableHeaders = ref(['#', {name: '', listObject: dataList}, "Name", "Address", "Area","Meter Type", "House Holding No","Status","Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['meter_type','customer_area']});
    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <tableTop :defaultObject="{meter_type_id:'', area_id: '',solar:'',generator:'',nesco:''}"></tableTop>
        </template>
        <template v-slot:topRight v-if="dataList.data !== undefined">
            <a class="btn btn-sm btn-outline-danger radius-30 text-uppercase" @click="deleteAllRecords({dataObject:dataList.data})" v-if="dataList.data.some(each => parseInt(each.checked) === 1)">Delete All</a>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td class="checkbox"><input :checked="item.checked" @change="handleSelectAll($event, [item])" class="form-check-input me-3 pointer" type="checkbox"/></td>
                <td>{{ item.name }}</td>
                <td>{{ item.address }}</td>
                <td>{{ item.area_name}}</td>
                <td>{{ item.meter_name }}</td>
                <td>{{ item.house_holding_no }}</td>
                <td><a @click="changeStatus({obj:item})" class="pointer" v-html="statusBadge(item.status)"></a></td>
                <td>
                    <router-link :to="{ name: 'customer_show', params: { id: item.id } }" class="btn btn-outline-secondary action">
                        <i class='bx bxs-show text-primary'></i>
                    </router-link>
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
                <label class="col-md-4"><strong>Date of Birth : </strong></label>
                <div class="col-md-8">
                    <input type="date" v-model="formObject.dob" class="form-control"/>
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
                <label class="col-md-4"><strong>Area: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.area_id" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="type in pageDependencies.customer_area">
                            <option :value="type.id">{{type.name}}</option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4"><strong>Meter Type: </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.meter_type_id" class="form-control" v-validate="'required'">
                        <option value="">Select</option>
                        <template v-for="type in pageDependencies.meter_type">
                            <option :value="type.id">{{type.name}}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Nesco</strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.nesco" class="form-control">
                        <option value="">Select</option>
                        <option value="3">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Solar : </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.solar" class="form-control">
                        <option value="">Select</option>
                        <option value="2">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Generator : </strong></label>
                <div class="col-md-8">
                    <select v-model="formObject.generator" class="form-control">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
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
