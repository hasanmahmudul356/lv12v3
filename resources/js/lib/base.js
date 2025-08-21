import {computed} from 'vue'
import {useToast} from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css'
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
    const getUser = () => {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
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

    const showConfirmation = (text = "Data will be delete Permanently??", callback = false) => {
        // You'll need to replace this with your preferred confirmation dialog
        const confirmed = confirm(text);

        if (confirmed && typeof callback === 'function') {
            callback()
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

    return {
        getImage,
        getUser,
        openFile,
        toaster,
        showConfirmation,
        openModal,
        closeModal
    };
}
