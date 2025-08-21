<script setup>
import tableHeader from "@/components/table/tableHeader.vue";
import tableTop from "@/components/table/tableTop.vue";

const props = defineProps({
    headings: Array,
    loading: Boolean,
    currentPage: Number,
    setting: Boolean,
});

const headings = props.headings ?? [];

defineEmits(['page-change'])

</script>


<template>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <slot name="tableTop"></slot>
            <slot name="topRight"></slot>
        </div>
        <div class="card">
            <div class="card-body data-table">
                <tableTop></tableTop>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                        <tr>
                            <template v-if="$slots.header">
                                <slot name="header"></slot>
                            </template>
                            <template v-else>
                                <tableHeader :headings="headings"></tableHeader>
                            </template>
                        </tr>
                        </thead>
                        <tbody>
                        <slot name="data"></slot>
                        </tbody>
                        <tfoot v-if="$slots.footer">
                            <tr>
                                <td :colspan="columnCount">
                                    <slot name="footer"></slot>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <slot></slot>
    </div>
</div>
</template>
