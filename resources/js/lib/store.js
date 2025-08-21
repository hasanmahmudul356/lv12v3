import {computed} from "vue";

export function appStore(store) {

    const formFilter = computed({
        get() {
            return store.getters.formFilter;
        },
        set(value) {
            store.commit('formFilter', value);
        }
    });
    const dataList = computed({
        get() {
            return store.getters.dataList;
        },
        set(value) {
            store.commit('dataList', value);
        }
    });
    const allMenus = computed({
        get() {
            return store.getters.allMenus;
        },
        set(value) {
            store.commit('allMenus', value);
        }
    });
    const formObject = computed({
        get() {
            return store.getters.formObject;
        },
        set(value) {
            store.commit('formObject', value);
        }
    });
    const setState = (state, key, value) => {
        state.value = {
            ...state.value,
            [key]: value
        };
    };

    const checkUncheckAll = ($event, state, objectName, checkValue, unCheckValue) => {
        if ($event.target.checked) {
            setState(state, objectName, checkValue);
        } else {
            setState(state, objectName, unCheckValue);
        }
    };

    // const useGetters = (...names) => {
    //     const result = {};
    //     names.forEach(name => {
    //         result[name] = computed(() => store.getters[name]);
    //     });
    //     return result;
    // };
    const useGetters = (storeInstance, ...names) => {
        const result = {};
        names.forEach(name => {
            result[name] = computed(() => storeInstance.getters[name]);
        });
        return result;
    };

    return{
        formFilter,
        formObject,
        dataList,
        allMenus,
        useGetters,
        setState
    }
}