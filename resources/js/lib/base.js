import {computed} from 'vue'
import 'vue-toast-notification/dist/theme-sugar.css'
import {useToast} from 'vue-toast-notification'
import Swal from 'sweetalert2/dist/sweetalert2';
import axios from "axios";


export function useBase() {
    const toast = useToast();

    const toaster = (type = 'success', message = false) => {
        if (message){
            const map = {
                success: msg => toast.success(msg),
                error: msg => toast.error(msg),
                info: msg => toast.info(msg),
                warning: msg => toast.warning(msg),
                default: msg => toast(msg)
            };

            (map[type] || map.default)(message);
        }
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

    const openModal = (modalId = 'fromModal', callback = false) => {
        const modal = document.getElementById(modalId);
        const bsModal = new bootstrap.Modal(modal, {
            backdrop: 'static',
            keyboard: true,
            focus: true
        });
        bsModal.show();
        modal.focus();

        console.log(modalId);

        if (typeof callback === 'function'){
            callback(true);
        }
    };
    const closeModal = (modalId = 'fromModal') => {
        const modal = document.getElementById(modalId);
        const bsModal = bootstrap.Modal.getInstance(modal);
        bsModal.hide();
        document.querySelector('body').focus();
    };

    const handelConfirm = async (callback = false, title = false, message = false) => {
        const result = await Swal.fire({
            title: title ?? "Are you sure?",
            text: message ?? "Are you sure to delete this ??",
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

    const statusBadge = (status, activeText = 'Active', inActiveText = 'Inactive') => {
        const isActive = parseInt(status);
        const badgeClass = isActive ? "bg-success" : "bg-warning";
        const label = isActive ? activeText : inActiveText;

        return `<span class="badge rounded-pill p-2 text-uppercase px-3 ${badgeClass}">
            <i class="bx bxs-circle me-1"></i>
            <span>${label}</span>
        </span>`;
    };

    return {
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
