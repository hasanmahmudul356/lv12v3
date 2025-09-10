<script setup>
    import {fileUpload} from '@/components'
    import {onMounted} from 'vue'
    import {useStore} from 'vuex'
    const store = useStore();
    import {useBase, appStore, useHttp} from "@/lib";
    const {getImage, formObject, submitForm, useGetters, httpReq, urlGenerate} = {
        ...useBase(),
        ...appStore(),
        ...useHttp()
    };

    onMounted(async ()=>{
        const authUser = await httpReq({
            method : 'get',
            url : urlGenerate('api/profile'),
            loader:true
        });
        if (authUser){
            store.commit('formObject', authUser);
        }
    });
</script>
<template>
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            </div>
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <form @submit.prevent="submitForm({data : formObject, method:'post'})">
                                            <div class="row mb-3">
                                                <div class="col-md-3 offset-4">
                                                    <fileUpload :object="formObject" :column="'image'" :height="200"></fileUpload>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Full Name</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input v-model="formObject.name" type="text" class="form-control" value="John Doe" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Email</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="formObject.email" class="form-control" value="john@example.com" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Email</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="formObject.username" class="form-control" value="john@example.com" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Phone</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="formObject.phone" class="form-control" value="(239) 816-9029" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Designation</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="formObject.designation" class="form-control" value="(239) 816-9029" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <h5>Theme & Locale</h5>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Theme</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly v-model="formObject.theme" class="form-control" value="(239) 816-9029" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Locale</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly v-model="formObject.locale" class="form-control" value="(239) 816-9029" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <h5>Password</h5>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Old Password</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="formObject.password" class="form-control" value="(239) 816-9029" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">New Password</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="formObject.new_password" class="form-control" value="(239) 816-9029" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                    <button type="submit" class="btn btn-light px-4" >Save Changes </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>