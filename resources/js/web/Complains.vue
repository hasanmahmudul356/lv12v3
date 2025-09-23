<script setup>
   import {useBase} from '@/lib';
   const {getImage} = useBase();
</script>
<template>
    <div class="row mb-30 mt-20">
        <div class="col-md-12">
            <div class="header-section">
                <h2 class="text-primary"><strong>Complain List</strong></h2>
                <hr>
            </div>
            <div class="body-section">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Complain Subject</th>
                                <th>Occrance Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>Bad Management</td>
                                <td>2025-05-31</td>
                                <td>Submitted</td>
                                <td>
                                    <a class="btn btn-warning"><i class="fa fa-pencil-square-o"></i></a>
                                    <a class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Bad Management</td>
                                <td>2025-05-31</td>
                                <td>Submitted</td>
                                <td>
                                    <a class="btn btn-warning"><i class="fa fa-pencil-square-o"></i></a>
                                    <a class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Bad Management</td>
                                <td>2025-05-31</td>
                                <td>Submitted</td>
                                <td>
                                    <a class="btn btn-warning"><i class="fa fa-pencil-square-o"></i></a>
                                    <a class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Bad Management</td>
                                <td>2025-05-31</td>
                                <td>Submitted</td>
                                <td>
                                    <a class="btn btn-warning"><i class="fa fa-pencil-square-o"></i></a>
                                    <a class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>