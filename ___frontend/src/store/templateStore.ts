import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'

export const useTemplateStore = defineStore('templateStore', () => {
    // index.html reads the same key to apply the theme before the first paint.
    const appTheme = useStorage<'dark' | 'light'>('socc_theme', 'dark')
    if (appTheme.value !== 'light') appTheme.value = 'dark'

    function toggleTheme() {
        appTheme.value = appTheme.value == 'dark' ? 'light' : 'dark';
    }

    return {
        appTheme,
        toggleTheme
    }
})
