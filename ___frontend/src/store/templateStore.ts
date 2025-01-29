import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useTemplateStore = defineStore('templateStore', () => {
    const appTheme = ref<'dark' | 'light'>('light')

    function toggleTheme() {
        appTheme.value = appTheme.value == 'dark' ? 'light' : 'dark';
    }

    return {
        appTheme,
        toggleTheme
    }
})
