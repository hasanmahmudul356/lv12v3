import {computed} from 'vue'
import 'vue-toast-notification/dist/theme-sugar.css'
import {useToast} from 'vue-toast-notification'
import Swal from 'sweetalert2/dist/sweetalert2';
import {appStore, useValidator} from "@/lib";
import {useStore} from 'vuex';

import { useI18n } from 'vue-i18n'


export function useBase() {
    const toast = useToast();
    const store = useStore();
    const { t } = useI18n();

    let {formObject, assignStore, resetValidation} = {...appStore(), ...useValidator()};

    const toaster = (type = 'success', message = false, title = false) => {
        if (!message) return;

        const capitalize = str => str.charAt(0).toUpperCase() + str.slice(1);
        title = title || capitalize(type);

        const formatMessage = (msg, t) =>
            t ? `<strong>${t}</strong><br><span>${msg}</span>` : `<span>${msg}</span>`;

        const map = {
            success: msg => toast.success(formatMessage(msg, title)),
            error: msg => toast.error(formatMessage(msg, title)),
            info: msg => toast.info(formatMessage(msg, title)),
            warning: msg => toast.warning(formatMessage(msg, title)),
            default: msg => toast(formatMessage(msg, title)),
        };

        (map[type] || map.default)(message);
    };

    const _l = (key) => {
        return t(key);
    };

    const getImage = (imagePath) => {
        return 'https://static.vecteezy.com/system/resources/thumbnails/057/068/323/small/single-fresh-red-strawberry-on-table-green-background-food-fruit-sweet-macro-juicy-plant-image-photo.jpg';
        if (!imagePath) return '/images/default.png'; // fallback image
        return `/storage/${imagePath}`;
    };
    const openFile = (url, title = '', customWidth = false, customHeight = false) => {
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const width = parseFloat(window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width);
        const height = parseInt(window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height);

        const w = !customWidth ? width - 250 : customWidth;
        const h = !customHeight ? height - 100 : customHeight;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft;
        const top = (height - h) / 2 / systemZoom + dualScreenTop;
        const newWindow = window.open(url, title, `scrollbars=yes,width=${w / systemZoom},height=${h / systemZoom},top=${top},left=${left}`);

        if (window.focus) {
            newWindow.focus();
        }
    };

    const openModal = (options = {}) => {
        let {
            modalId = 'fromModal',
            defaultObject = {},
            callback = false,
            resetForm = true
        } = options;

        resetValidation();

        if (resetForm){
            assignStore('formObject', {});
            assignStore('updateId', null);
        }

        if (Object.keys(defaultObject).length > 0) {
            store.commit('resetForm', defaultObject);
        }

        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal with id "${modalId}" not found`);
            return;
        }

        const bsModal = new bootstrap.Modal(modal, {
            backdrop: 'static',
            keyboard: true,
            focus: true
        });
        bsModal.show();

        const firstInput = modal.querySelector('input, textarea, select');
        if (firstInput) {
            firstInput.focus();
        } else {
            modal.focus();
        }

        if (typeof callback === 'function') {
            callback({ success: true, modalId, formObject });
        }
    };
    const closeModal = (modalId = 'fromModal') => {
        const modal = document.getElementById(modalId);
        const bsModal = bootstrap.Modal.getInstance(modal);
        bsModal.hide();
        document.querySelector('body').focus();
    };

    const handelConfirm = async (options = {}) => {
        const {
            title = "Are you sure?",
            message = "Are you sure to delete this ??",
            callback = false
        } = options;

        const result = await Swal.fire({
            title: title,
            text: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<i class="fadeIn animated bx bx-check-circle"></i> Yes',
            cancelButtonText: '<i class="fadeIn animated bx bx-window-close"></i> No',
        });

        if (typeof callback === 'function' && result.isConfirmed){
            callback(true);
        }
        return result.isConfirmed;
    };
    const handleSelectAll = (event, dataList) => {
        if (dataList !== undefined) {
            $.each(dataList, (index, item) => {
                item.checked = event.target.checked ? 1 : 0;
                $.each(item.submenus, (sIndex, sItem) => {
                    sItem.checked = event.target.checked ? 1 : 0;
                })
            });
        }
    };

    const statusBadge = (status, activeText = 'active', inActiveText = 'inactive') => {
        const isActive = parseInt(status);
        const badgeClass = isActive ? "bg-success" : "bg-warning";
        const label = isActive ? _l(activeText) : _l(inActiveText);

        return `<span class="badge rounded-pill p-2 text-uppercase px-3 ${badgeClass}">
            <i class="bx bxs-circle me-1"></i>
            <span>${label}</span>
        </span>`;
    };

    return {
        _l,
        getImage,
        openFile,
        toaster,
        openModal,
        closeModal,
        handelConfirm,
        handleSelectAll,
        statusBadge,
    };
}
