<script setup>
    import {ref, reactive, onMounted} from 'vue'
    import {useStore} from 'vuex'
    import {useRouter} from 'vue-router'
    const store  = useStore();
    const router  = useRouter();

    import {dataTable, tableTop, pageTopLeft, fromModal} from '@/components'

    import {appStore, useHttp, useBase} from "@/lib";

    const {useGetters, getDataList, submitForm, editData, getDependency} = {...appStore(store), ...useHttp(store, router)};
    const { httpRequest, dataList, pageDependencies } = useGetters(store,
        'httpRequest', 'dataList', 'pageDependencies'
    );

    const tableHeaders = reactive(['#', {name: '', data: dataList.data}, 'Display Name', 'Name', 'Status', 'Action']);
    const permissions = reactive(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'status']);
    const {openModal} = useBase();

    const formObject = reactive({
        permissions: [],
    });

    const checkUncheck = ($event, permissions) => {
        permissions.forEach((value) => {
            if ($event.target.checked) {
                if (!formObject.permissions.includes(value)) {
                    formObject.permissions.push(value);
                }
            } else {
                const index = formObject.permissions.indexOf(value);
                if (index > -1) {
                    formObject.permissions.splice(index, 1);
                }
            }
        });
    };

    const handleSubmit = () => {
        submitForm(store, {
            data: formObject,
            modal: 'fromModal',
            callback: function (retData) {
                Object.assign(formObject, { permissions: [] });
                getDataList(store);
            }
        });
    };

    const checkSelected = (permission) => {
        return formObject.permissions?.some(
            (assigned) => assigned.actual === permission.name
        ) ?? false
    };

    onMounted(()=>{
        getDataList(store);
        getDependency(store, {dependency : ['permissions']});
    });

</script>
<template>
    <dataTable :loading="httpRequest" :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <pageTopLeft></pageTopLeft>
        </template>
        <template v-slot:data>
            <template v-for="(item, index) in dataList.data" :key="item.id">
                <tr >
                    <td>{{index+1}}</td>
                    <td><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></td>
                    <td>{{ item.display_name }}</td>
                    <td>{{ item.name }}</td>
                    <td>
                        <a class="badge rounded-pill p-2 text-uppercase px-3" :class="parseInt(item.status) === 1 ? 'bg-success' : 'bg-warning'">
                            <i class='bx bxs-circle me-1'></i>
                            <span>Active</span>
                        </a>
                    </td>
                    <td>
                        <a @click="editData(item, 'fromModal', formObject)" class="btn btn-outline-secondary action"><i class='bx bxs-edit text-warning'></i></a>
                        <a href="#" class="btn btn-outline-secondary action"><i class='bx bxs-trash text-danger'></i></a>
                    </td>
                </tr>
                <template v-for="(item, index2) in item.submenus" :key="item.id">
                    <tr >
                        <td>{{index+1}}.{{index2+1}} </td>
                        <td><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></td>
                        <td>{{ item.display_name }}</td>
                        <td>{{ item.name }}</td>
                        <td>
                            <a class="badge rounded-pill p-2 text-uppercase px-3" :class="parseInt(item.status) === 1 ? 'bg-success' : 'bg-warning'">
                                <i class='bx bxs-circle me-1'></i>
                                <span>Active</span>
                            </a>
                        </td>
                        <td>
                            <a @click="editData(item, 'fromModal', formObject)" class="btn btn-outline-secondary action"><i class='bx bxs-edit text-warning'></i></a>
                            <a href="#" class="btn btn-outline-secondary action"><i class='bx bxs-trash text-danger'></i></a>
                        </td>
                    </tr>
                </template>
            </template>
        </template>

        <fromModal title="Module Form" modal-size="modal-xs" @submit="handleSubmit">
            <div class="row mb-2">
                <label class="col-md-4"><strong>Display Name : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.display_name" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Name :</strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.name" class="form-control" />
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Link : </strong></label>
                <div class="col-md-8">
                    <input type="text" v-model="formObject.link" class="form-control" />
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4"><strong>Icon : </strong></label>
                <div class="col-md-8">
                    <select class="form-control" v-model="formObject.icon">
                        <option value="">Select</option>
                        <option value="bx bx-cookie">bx bx-cookie</option>
                        <option value="bx bx-menu">bx bx-menu</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row mb-2">
                <label class="col-md-4 pointer" for="allPermissions">
                        <strong>Permission : </strong>
                        <input @change="checkUncheck($event, pageDependencies.permissions)" class="form-check-input" type="checkbox" id="allPermissions" value="all">
                </label>
                <div class="col-md-8">
                    <div class="form-check form-check-inline" v-for="(permission, index) in pageDependencies.permissions">
                        <input class="form-check-input" @change="checkUncheck($event, [permission])" type="checkbox" :checked="checkSelected(permission)" :id="permission.name" :value="permission.name">
                        <label class="form-check-label text-uppercase pointer" :for="permission.name">{{permission.id}} {{permission.display_name}}</label>
                    </div>
                </div>
            </div>
        </fromModal>
    </dataTable>
</template>

<style scoped>

</style>