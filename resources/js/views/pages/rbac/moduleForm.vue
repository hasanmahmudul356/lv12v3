<script setup>
    const props = defineProps({
        formObject: {type: Object, default: () => ({})},
        dependencies: {type: Object, default: () => ({})},
    });
    const formObject = props.formObject;
    const pageDependencies = props.dependencies;

    const checkUncheck = ($event, permissions) => {
        permissions.forEach((value, index) => {
            if ($event.target.checked && !formObject.permissions.includes(value)) {
                formObject.permissions.push(value);
            } else {
                let index = formObject.permissions.indexOf(value);
                if (index !== -1) {
                    formObject.permissions.splice(index, 1);
                }
            }
        });
    };
</script>
<template>
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
            <div class="form-check form-check-inline" v-for="permission in pageDependencies.permissions">
                <input class="form-check-input" @change="checkUncheck($event, [permission])" type="checkbox" :checked="formObject.permissions.includes(permission)" :id="permission" :value="permission">
                <label class="form-check-label text-uppercase pointer" :for="permission">{{permission}}</label>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>