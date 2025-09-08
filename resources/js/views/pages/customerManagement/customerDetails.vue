<script setup>
    import { onMounted, ref } from 'vue';
    import { useRoute } from 'vue-router';
    import { useStore } from 'vuex';
    import { useHttp } from '@/lib/http';

    const route = useRoute();
    const store = useStore();
    const { httpReq, urlGenerate } = useHttp();

    const detailsData = ref({});

    const details = async (id) => {
        try {
            const url = `${urlGenerate()}/${id}`;
            const retData = await httpReq({ url, method: 'get', loader: true });
            if (retData) {
                detailsData.value = { ...retData };
            }
        } catch (error) {
            console.error('Failed to fetch details:', error);
        }
    };

    onMounted(() => {
        if (route.params.id) {
            details(route.params.id);
        }
    });
</script>




<template>
    <div class="container" style="margin-top: 80px">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Customer Details</h5>
                <router-link :to="{name: 'customer_information'}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Back To List
                </router-link>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Name:</strong>{{detailsData.name}}</div>
                    <div class="col-md-6"><strong>Email:</strong>{{detailsData.email}}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Phone Number:</strong>{{detailsData.phone_number}}</div>
                    <div class="col-md-6"><strong>Date of Birth:</strong>{{detailsData.dob}}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>House Holding No:</strong>{{detailsData.house_holding_no}}</div>
                    <div class="col-md-6"><strong>Area ID:</strong>{{detailsData.area_id}}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Meter Type ID:</strong>{{detailsData.meter_type_id}}</div>
                    <div class="col-md-6"><strong>NESCO:</strong>{{detailsData.nesco}}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Solar:</strong>{{detailsData.solar}}</div>
                    <div class="col-md-6"><strong>Generator:</strong>{{detailsData.generator}}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12"><strong>Address:</strong>{{detailsData.address}}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Image:</strong><br />
                        <img :src="detailsData.image ? `/storage/${detailsData.image}` : 'https://via.placeholder.com/150x150?text=No+Image'" class="img-thumbnail mt-2" width="150"/>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

