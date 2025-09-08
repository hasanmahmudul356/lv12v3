<script setup>

</script>

<template>
    <div class="container" style="margin-top: 80px">
        <div class="card shadow-sm">
            <div class="card-header  text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Customer Details</h5>
                <router-link :to="{name: 'customer_information'}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Back To List
                </router-link>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Name:</strong> Mehedi</div>
                    <div class="col-md-6"><strong>Email:</strong> mehedi@gmail.com</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Phone Number:</strong> 017XXXXXXXX</div>
                    <div class="col-md-6"><strong>Date of Birth:</strong> 1999-05-10</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>House Holding No:</strong> 12345</div>
                    <div class="col-md-6"><strong>Area ID:</strong> Bogura-01</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Meter Type ID:</strong> MT-002</div>
                    <div class="col-md-6"><strong>NESCO:</strong> Yes</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6"><strong>Solar:</strong> No</div>
                    <div class="col-md-6"><strong>Generator:</strong> Yes</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12"><strong>Address:</strong> Bogura</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Image:</strong><br />
                        <img src="https://t3.ftcdn.net/jpg/15/60/58/00/360_F_1560580047_vQXhYbq3RWn4BnxGVM3Oi62ERKcVonUm.jpg" class="img-thumbnail mt-2" width="150" />
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

