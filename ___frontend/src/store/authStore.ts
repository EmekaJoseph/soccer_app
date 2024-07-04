import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
// @ts-ignore
import Cookies from 'js-cookie';

export const useAuthStore = defineStore('authStore', () => {
    const tokenName: string = import.meta.env.VITE_TOKEN_NAME
    const token = ref('')
    const socc_user: any = useStorage('socc_user', '')

    const isLoggedIn = computed(() => token.value || Cookies.get(tokenName));

    const login = (data: any) => {
        Cookies.set(tokenName, data.token, { expires: 7 });
        token.value = data.token;
        socc_user.value = JSON.stringify(data);
    }

    const logout = () => {
        Cookies.remove(tokenName);
        token.value = '';
        window.location.reload();
    }

    // const getUserToken = () => Cookies.get(tokenName)
    const getUserData = () => JSON.parse(socc_user.value)



    return {
        isLoggedIn,
        tokenName,
        login,
        logout,
        getUserData,
        // getUserToken
    }

})
