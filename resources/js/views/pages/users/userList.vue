<script setup>
    import dataTable from '@/components/table/dataTable.vue';
    import Setting from '@/components/Setting.vue';
    import fromModal from '@/components/fromModal.vue';
    import pageTopLeft from '@/components/pageTopLeft.vue';

    import {ref, onMounted, computed} from 'vue';

    let modalInstance = null;
    import {useStore} from 'vuex';
    const store = useStore();
    import {useBase, useHttp, appStore} from '@/lib';

    const {formFilter, formObject, openModal, closeModal, useGetters, dataList, httpRequest} = {
        ...useBase(),
        ...appStore(store),
        ...appStore().useGetters('dataList', 'httpRequest')
    };
    const tableHeaders = ref(["#", {
        name: '',
        data: dataList.data
    }, "Name", "Email", "Username", "Phone", "Opening Date", "Actions"]);

    const {getDataList, httpReq} = useHttp();


    onMounted(() => {
        getDataList();
    });
</script>

<template>
    <dataTable :headings="tableHeaders" :setting="true">
        <template v-slot:tableTop>
            <pageTopLeft></pageTopLeft>
        </template>
        <template v-slot:topRight>
            <Setting title="Options">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Action</a>
            </Setting>
        </template>
        <template v-slot:data>
            <tr v-for="(item, index) in dataList.data" :key="item.id">
                <td>{{index+1}}</td>
                <td><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></td>
                <td>{{ item.name }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.username }}</td>
                <td>{{ item.created_at }}</td>
                <td>
                    <a class="badge rounded-pill p-2 text-uppercase px-3" :class="parseInt(item.status) === 1 ? 'bg-success' : 'bg-warning'">
                        <i class='bx bxs-circle me-1'></i>
                        <span>Active</span>
                    </a>
                </td>
                <td>
                    <a href="#" class="btn btn-outline-secondary action"><i class='bx bxs-edit text-warning'></i></a>
                    <a href="#" class="btn btn-outline-secondary action"><i class='bx bxs-trash text-danger'></i></a>
                </td>
            </tr>
        </template>

        <fromModal></fromModal>
    </dataTable>

</template>
