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

            <div class="ms-2 text-center mt-md-5">
                <h5 class="mb-1 fw-bold border-bottom d-inline-block pb-1">বিদ্যুৎ বিল</h5>
            </div>
            <div class=" mt-4">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-body p-3">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6 border-end">
                                <p class="mb-md-4"><strong>ব্যবহারকারী  বিভাগ  / ব্যক্তির  নাম ও  ঠিকানা:</strong></p>
                                <p class="mb-1"><strong> ব্যবহারকারী / ব্যক্তি:</strong></p>
                                <div class="row mb-1">
                                    <div class="col-3"><strong>নাম:</strong></div>
                                    <div class="col-3">আকম উদ্দিন</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3"><strong>ঠিকানা:</strong></div>
                                    <div class="col-3">টেস্তামারা, বগুড়া</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="row mb-1">
                                    <div class="col-4"><strong>মাসের নাম:</strong></div>
                                    <div class="col-4">মার্চ-২০২৫</div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-4"><strong>বিল নং:</strong></div>
                                    <div class="col-4">৩৬</div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-4"><strong>ইস্যু তারিখ:</strong></div>
                                    <div class="col-4">৮/৩/২০২৫</div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-4"><strong>কাস্টমার নং:</strong></div>
                                    <div class="col-4">৮৪</div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-4"><strong>পরিশোধের শেষ তারিখ:</strong></div>
                                    <div class="col-4">৬/৪/২০২৫</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-body p-4">

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6 border-end">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><strong>বর্তমান রিডিং গ্রহণের তারিখ</strong></span>
                                    <span>৪/৩/২০২৫</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span><strong>পূর্বের রিডিং গ্রহণের তারিখ</strong></span>
                                    <span>৩/২/২০২৫</span>
                                </div>

                                <p class="mb-1 fw-bold border-bottom d-inline-block pb-1">পূর্বের বকেয়া হিসাবের তথ্য</p>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>বকেয়া  মাস:</span>
                                    <span>০</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>বকেয়া বিল</span>
                                    <span>০</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> কত মাসের  বকেয়া:</span>
                                    <span>০</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> বিলম্ব  মাশুল(৫%):</span>
                                    <span>০</span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>মোট বকেয়া:</span>
                                    <span>০</span>
                                </div>


                               <div class="mt-md-5">
                                   <p>বিঃদ্রঃ টিএমএসএস হিসাব শিরোনামে প্রেরনের জন্য অনুরোধ করা হল। <br>(কথায়ঃ)</p>
                               </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><strong>বর্তমান রিডিং</strong></span>
                                    <span>৩৬৩২০ KWH</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span><strong>পূর্বের রিডিং</strong></span>
                                    <span>৩৬১৩৪ KWH</span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>ব্যবহৃত ইউনিট</span>
                                    <span>১৮৬ KWH</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>ব্যবহৃত ইউনিট:  নেসকো</span>
                                    <span>১৩২৬ KWH</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>ব্যবহৃত ইউনিট: জেনারেটর </span>
                                    <span>১৩২৬ KWH</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> ইলেক্টিসিটি  চার্জ:  নেসকো <br> ( প্রতি  ইউনিট  ১২.৫/-)  </span>
                                    <span>১৩২৬  টাকা</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> ইলেক্টিসিটি  চার্জ:  জেনারেটর <br> ( প্রতি  ইউনিট  ২৬.৫/-)  </span>
                                    <span>১৩২৬  টাকা</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> ডিমান চার্জ</span>
                                    <span>২০০ টাকা</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> সিস্টেম  লস (৭%): </span>
                                    <span>৫০ টাকা</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>সার্ভিস চার্জ (৫%):</span>
                                    <span>১০০ টাকা</span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>মোট:</span>
                                    <span > ২৭৫৬৫ টাকা</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span> পূর্বের  বকেয়া:</span>
                                    <span> টাকা</span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>সর্বমোট:</span>
                                    <span class="fw-bold">২৫০০ টাকা</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

