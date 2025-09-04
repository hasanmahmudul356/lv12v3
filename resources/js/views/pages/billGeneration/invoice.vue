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

    const tableHeaders = ref(['#', {name: '', listObject: dataList}, "Billing Month", "Unit Rate","Status","Actions"]);
    const {getDataList, httpReq} = useHttp();

    onMounted(() => {
        getDataList();
        getDependency({dependency : ['']});
    });
</script>

<template>
    <div class="page-wrapper">
        <div class="page-content">
           <div class="d-flex align-items-baseline justify-content-between mb-3">
<!--               logo-->
               <div style="width:70px;height:70px;overflow:hidden;">
                   <img src="https://www.tbsnews.net/sites/default/files/styles/big_3/public/organization/logo/tmss.jpg" alt="Logo" class="img-fluid" style="max-height:100%; object-fit:contain;">
               </div>

<!--               company name-->
               <div class="ms-2 text-center">
                   <h5 class="mb-1 fw-bold">প্রকৌশল বিভাগ</h5>
                   <div class="small text-secondary">ফাউন্ডেশন অফিস, স্কুল রোড, টেস্তামারা, বগুড়া।</div>
                   <div class="small">ই-মেইলঃ <span class="text-primary">enggdept.tmss@gmail.com</span></div>
               </div>

<!--               copy part -->
               <div>
                   <span class="badge rounded-pill bg-secondary-subtle text-dark border px-3 py-2 fs-6">গ্রাহক কপি</span>
               </div>
           </div>

            <div class="ms-2 text-center mt-3">
                <h5 class="mb-1 fw-bold"> বিদ্যুৎ বিল</h5>
            </div>
        </div>
    </div>
</template>

