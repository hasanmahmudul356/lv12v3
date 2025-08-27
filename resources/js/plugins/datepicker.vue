<script setup>
    import { ref, onMounted, toRefs } from 'vue';
    import 'jquery-ui/ui/widgets/datepicker';   // import the datepicker widget
    import 'jquery-ui/themes/base/all.css';

    // Props
    const props = defineProps({
        name: String,
        value: {
            type: [String, Date],
            default: '',
        },
        icon: String,
        input_class: {
            type: String,
            default: 'form-control',
        },
        view_mode: {
            type: String,
            default: 'years',
        },
        id: {
            type: [String, Boolean, Number],
            default: false,
        },
        readonly: {
            type: [String, Boolean, Number],
            default: false,
        },
        editable: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            default: 'Select Date',
        },
        format: {
            type: String,
            default: 'yy-mm-dd',
        },
        validate: { type: [String, Object], default: '' },
        validation_name: String,
        vTooltipRight: Function,
        dataVvAs: String,
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    // Emits
    const emit = defineEmits(['input', 'change', 'update', 'keyup', 'keydown', 'blur']);

    // Refs
    const inputId = ref('');
    const inputValue = ref('');

    // Generate random ID
    const makeId = (length = 5) => {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    };

    // Get ID (prop or random)
    const getId = () => {
        return props.id || makeId();
    };

    // Handle input events
    const dateInputed = (event) => {
        emit('input', event.target.value);
        emit('change');
        emit('update');
        emit('keyup');
        emit('keydown');
        emit('blur');
    };

    // Mounted
    onMounted(() => {
        inputId.value = getId();

        $(function () {
            $("#" + inputId.value).datepicker({
                autoclose: true,
                todayHighlight: true,
                changeMonth: true,
                changeYear: true,
                dateFormat: props.format,
                yearRange: "-100:+15",
                showOtherMonths: true,
                selectOtherMonths: true,
                beforeShow: function (input, inst) {
                    $(document).off('focusin.bs.modal');
                },
                onClose: function () {
                    $(document).on('focusin.bs.modal');
                },
                onSelect: function (dateText) {
                    emit('input', dateText);
                    emit('change');
                    emit('update');
                    emit('keyup');
                    emit('keydown');
                    emit('blur');
                }
            });
        });
    });
</script>

<template>
    <input type="text" autocomplete="off" @change="dateInputed" :readonly="readonly" :id="inputId" :name="name" :data-vv-as="validation_name" :placeholder="placeholder" v-validate="validate" :value="value" :disabled="disabled" :class="input_class"/>
</template>

<style scoped>
    /* your styles here */
</style>
