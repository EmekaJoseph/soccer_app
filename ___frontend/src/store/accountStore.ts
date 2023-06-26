import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'

export const useAccount = defineStore('account', () => {
    const nullState = {
        id: null,
        username: null,
        role: null,
    }

    const state = ref<any>(useStorage('dlam_cup_user', nullState, localStorage))
    const token = ref<any>(useStorage('dlam_cup_T', '', localStorage))

    return {
        state,
        nullState,
        token,
    }

})
