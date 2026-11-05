import { beforeEach, describe, expect, it } from 'vitest'
import { nextTick } from 'vue'
import { createPinia, setActivePinia } from 'pinia'
import { useTemplateStore } from '@/store/templateStore'

describe('theme', () => {
    beforeEach(() => {
        localStorage.clear()
        setActivePinia(createPinia())
    })

    it('defaults to dark', () => {
        expect(useTemplateStore().appTheme).toBe('dark')
    })

    it('remembers the choice for the next visit', async () => {
        useTemplateStore().toggleTheme()
        await nextTick()
        expect(localStorage.getItem('socc_theme')).toBe('light')

        setActivePinia(createPinia())
        expect(useTemplateStore().appTheme).toBe('light')
    })

    it('falls back to dark when the saved value is not a theme', () => {
        localStorage.setItem('socc_theme', 'purple')
        expect(useTemplateStore().appTheme).toBe('dark')
    })
})
