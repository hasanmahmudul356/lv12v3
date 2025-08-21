import {ref} from 'vue'
import {useRouter} from 'vue-router'
import axios from 'axios'

import {useBase, appStore} from "@/lib";

const {can, assignValidationError, toaster, closeModal, openModal} = useBase();
const {formObject, useGetters} = appStore();


export function useHttp(store, routeInstance = false) {
    const router = useRouter();
    const route = routeInstance ? routeInstance.currentRoute.value : router.currentRoute.value;


    const {formFilter, formType, updateId} = useGetters(store, 'formFilter', 'formType', 'updateId');

    const uploadProgress = ref(0);

    const getRoute = (key = false) => {
        if (key && route[key] !== undefined) {
            return route[key];
        }
        return route;
    };
    const routeMeta = (key = false) => {
        if (route.meta !== undefined) {
            return route.meta[key] !== undefined ? route.meta[key] : false;
        }
        return false;
    };

    const httpRequest = (status = true) => {
        store.commit('httpRequest', status);
    };
    const getUrl = () => {
        return (route.meta.dataUrl !== undefined) ? route.meta.dataUrl : '';
    };
    const urlGenerate = (customUrl = false) => {
        return customUrl ? `${baseUrl}/${customUrl}` : `${baseUrl}/${getUrl()}`;
    };

    const httpReq = async (store, options = {}) => {
        const {
            url = '',
            method = 'get',
            params = {},
            data = {},
            callback = false,
            loader = false,
        } = options;

        // try {
            if (!url) {
                toaster('error', 'No URL provided and no dataUrl in route meta');
                return;
            }

            if (loader) store.commit('httpRequest', true);

            const response = await axios({
                method: method,
                url: url,
                params: params,
                data: data,
            });

            if (loader) store.commit('httpRequest', false);

            const status = parseInt(response.data.status);

            if (status === 5001) {
                toaster(response.data.type || 'error', response.data.message);
                router.push({path: '/dashboard'});
                return false;
            }

            if (status === 5000) {
                toaster(response.data.type || 'error', response.data.message);
                return false;
            }

            if (status === 2000) {
                toaster('success', response.data.message);
                return response.data.result ?? true;
            }

            toaster('info', response.data.message);
            return false;

        // } catch (error) {
        //     if (loader) store.commit('httpRequest', false);
        //     toaster('error', error.message || 'Whoops..!! something went wrong');
        // }
    };
    const getDataList = async (store, options = {}) => {
        const {
            url = false,
            method = 'get',
            params = {},
            callback = false,
            page = 1,
        } = options;

        // try {
            store.commit('currentPagination', page || 1);

            const dataFilter = route.meta.dataUrl !== undefined ? route.meta.dataUrl : {};

            store.commit('dataList', {});

            const dateList = await httpReq(store,{
                method: method,
                url: urlGenerate(url),
                params: Object.assign(formFilter.value, {page: 1}, params, dataFilter),
                loader: true,
            });

            if (dateList){
                store.commit('dataList', dateList);

                if (typeof callback === 'function') {
                    callback(dateList)
                }
            }
        // } catch (error) {
        //     toaster('error', error.message);
        // }
    };
    const submitForm = async (store, options = {}) => {
        // try {
            const {
                data = {},
                modal = false,
                callback = false,
                validation = false,
                url = false,
                params = {},
                redirectTo = false,
                refresh = true,
                method = parseInt(updateId) ? 'put' : 'post',
            } = options;

            const pageNumber = 1;
            const valid = true;

            if (valid || !validation) {
                const retData = await httpReq(store,{
                    method: method,
                    url: parseInt(updateId) ? `${urlGenerate(url)}/${updateId}` : `${urlGenerate(url)}`,
                    data: data,
                    params: params,
                    loader: true,
                });

                if (retData){
                    if (modal) {
                        closeModal(modal);
                    }
                    if (typeof callback === "function") {
                        callback(retData);
                    }
                }
            }
        // } catch (error) {
        //     toaster('error', error.message);
        // }
    };

    const getDependency = async (store, options = {}) => {
        const {
            dependency = [],
            callback = false
        } = options;
        const retData = await httpReq(store, {
            method: 'post',
            url: urlGenerate('api/general'),
            data: dependency,
            loader: false
        });
        if (retData){
            $.each(retData, function (key, value) {
                store.commit("pageDependencies", { key: key, value })
            });
        }
    };

    const editData = (data, modalId = false, formObject = null)=>{
        if (!formObject) return {};
        if (modalId){
            openModal(modalId, function (retData) {
                Object.assign(formObject, data);
            });
        } else {
            Object.assign(formObject, data);
        }
    };


    const changeStatus = async (obj = {}, permissionName = '', showMessage = true, columnName = false) => {
        try {
            if (permissionName !== '' && !can(permissionName)) {
                toast.warning('Not permitted');
                return false
            }

            store.commit('httpRequest', true);
            const dataObject = (typeof obj === 'object') ? obj : {id: obj};

            if (columnName) {
                dataObject.column = columnName
            }

            const response = await axios({
                method: "post",
                url: `${urlGenerate()}/status`,
                data: dataObject
            });

            store.commit('httpRequest', false);
            getDataList(1);

            if (showMessage) {
                toast(response.data.message, {type: response.data.result})
            }
        } catch (error) {
            store.commit('httpRequest', false);
            if (showMessage) {
                const retData = error.response.data;
                toast.error(retData.message)
            }
        }
    };
    const deleteInformation = async (index, ID, url = false, callback = false, callDataList = true) => {
        // You'll need to replace this with your preferred confirmation dialog
        const confirmed = confirm('Are you sure..?? Data will be delete Permanently??');

        if (confirmed) {
            try {
                const URL = !url ? `${urlGenerate()}/${ID}` : url;
                const response = await axios.delete(URL);

                const retData = response.data;
                store.commit('httpRequest', false);

                if (parseInt(retData.status) === 2000) {
                    toast.success(retData.message);
                    if (callDataList) {
                        getDataList()
                    }
                    if (typeof callback === 'function') {
                        callback(true)
                    }
                } else {
                    toast.warning(retData.message)
                }
            } catch (error) {
                store.commit('httpRequest', false);
                toast.error('Something Wrong')
            }
        }
    };
    const loadConfigurations = async (store, options = {}) => {
        try {
            const {
                callback = false,
                url = false,
            } = options;

           const retData = await httpReq(store,{
                method: 'post',
                url: url ? urlGenerate(url) : urlGenerate('api/configurations'),
                loader: true,
            });

           if (retData){
               store.commit('Config', retData);
               store.commit('authUser', retData.user);
               store.commit('allMenus', retData.menus);

               if (typeof callback === 'function') {
                   callback(retData)
               }
           }

        } catch (error) {
            httpRequest(false);
            toaster('error', error.message);
        }
    };
    const uploadFile = async (event, imageObject = {}, dataModel = null, callback = false, url = false, onlyUrl = false) => {
        try {
            const input = event.target.files[0];
            const formData = new FormData();
            formData.append("type", imageObject.type);
            formData.append("file_type", 1);
            formData.append("file", input);

            if (onlyUrl) {
                formData.append("only_url", 'yes')
            }

            const URL = url ? urlGenerate(url) : urlGenerate('api/file_upload');
            store.commit('httpRequest', true);
            store.commit('uploadProgress', 0);

            const config = {
                onUploadProgress: function (progressEvent) {
                    const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    store.commit('uploadProgress', percentCompleted)
                }
            };

            const response = await axios.post(URL, formData, config);
            store.commit('httpRequest', false);

            if (parseInt(response.data.status) === 2000) {
                imageObject[dataModel] = response.data.result;

                if (typeof callback === 'function') {
                    callback(response.data)
                }
            } else if (parseInt(response.data.status) === 3000) {
                setTimeout(() => {
                    store.commit('uploadProgress', 0)
                }, 1000);
                toast.error(response.data.message)
            }
        } catch (error) {
            store.commit('httpRequest', false)
        }
    };

    return {
        uploadProgress,
        httpReq,
        getDataList,
        changeStatus,
        submitForm,
        deleteInformation,
        uploadFile,
        getUrl,
        urlGenerate,
        loadConfigurations,
        getRoute,
        routeMeta,
        editData,
        getDependency
    }
}
