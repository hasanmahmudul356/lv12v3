<template >
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-light setting_button">{{settingTitle}}</button>
            <button type="button" class="btn btn-light setting_button dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
<!--                <a class="dropdown-item" href="#">Action</a>-->
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        title: String,
    });

    const settingTitle = props.title ?? 'Settings';
</script>

<style scoped>

</style>