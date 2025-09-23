<script setup>
   import {useBase} from '@/lib';
   const {getImage} = useBase();
</script>
<template>
    <div class="row mt-30 mt-20">
        <div class="col-md-12">
            <div class="header-section">
                <h2 class="text-primary"><strong>New Complain</strong></h2>
                <hr>
            </div>
            <div class="body-section row mt-15">
                <div class="col-lg-10 col-sm-12">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Complain Subject</strong></label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label><strong>Occupancy Date</strong></label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label><strong>Complain Details</strong></label>
                                <textarea type="text" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 mt-5">
                                <button class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>