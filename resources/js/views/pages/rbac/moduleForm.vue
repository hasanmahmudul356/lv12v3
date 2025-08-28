<script setup>
    import {appStore} from "@/lib";
    const { useGetters, formObjectField} = appStore();
    let {formObject} = useGetters('formObject');

    const props = defineProps({
        formObject: {type: Object, default: () => ({})},
        dependencies: {type: Object, default: () => ({})},
    });
    const pageDependencies = props.dependencies;

    const checkUncheck = ($event, permissions) => {
        let savPermissions = [...formObject.value.permissions];
        permissions.forEach((value, index) => {
            if ($event.target.checked && !savPermissions.includes(value)) {
                savPermissions.push(value);
            } else {
                let index = savPermissions.indexOf(value);
                if (index !== -1) {
                    savPermissions.splice(index, 1);
                }
            }
        });
        formObjectField('permissions', savPermissions);
    };
</script>
<template>
    <div class="row mb-2">
        <label class="col-md-4"><strong>Display Name : </strong></label>
        <div class="col-md-8">
            <input type="text" v-validate="'required|numeric'" v-model="formObject.display_name" class="form-control"/>
<!--            <datepicker validate="required" :value="formObject.date" v-model="formObject.date" class="form-control"/>-->
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-md-4"><strong>Display Name : </strong></label>
        <div class="col-md-8">
            <input type="text" v-validate="'required|numeric'" v-model="formObject.display_name" class="form-control"/>
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-md-4"><strong>Name :</strong></label>
        <div class="col-md-8">
            <input type="text" v-validate="'required'" v-model="formObject.name" class="form-control" />
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-md-4"><strong>Link : </strong></label>
        <div class="col-md-8">
            <input type="text" v-validate="'required'" v-model="formObject.link" class="form-control" />
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-md-4"><strong>Icon : </strong></label>
        <div class="col-md-8">
            <select class="form-control" v-validate="'required'" v-model="formObject.icon">
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
        <div class="col-md-8" v-if="formObject.permissions !== undefined">
            <div class="form-check form-check-inline" v-for="permission in pageDependencies.permissions">
                <input class="form-check-input" @change="checkUncheck($event, [permission])" type="checkbox" :checked="formObject.permissions.includes(permission)" :id="permission" :value="permission">
                <label class="form-check-label text-uppercase pointer" :for="permission">{{permission}}</label>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>