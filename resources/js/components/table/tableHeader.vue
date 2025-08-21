<script setup>
    const props = defineProps({
        headings: Array,
    });
    const headings = props.headings ?? [];
</script>

<template>
    <template v-if="headings.length > 0">
        <template v-for="hading in headings">
            <template v-if="typeof hading === 'object'">
               <th>
                   <div class="d-flex align-items-center">
                       <div>
                           <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                       </div>
                       <div class="ms-2">
                           <h6 class="mb-0 font-14">{{hading.name}}</h6>
                       </div>
                   </div>
               </th>
            </template>
            <template v-else>
                <th >{{hading}}</th>
            </template>
        </template>
    </template>
</template>

<style scoped>

</style>