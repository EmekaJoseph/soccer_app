/// <reference types="vitest/config" />
import { fileURLToPath, URL } from 'node:url'
import { defineConfig, type Plugin } from 'vite'
import vue from '@vitejs/plugin-vue'

/** vue3-easy-data-table ships `var(easy-table-…)` (missing "--"), which Lightning CSS refuses to minify. */
const fixEasyDataTableCss = (): Plugin => ({
  name: 'fix-easy-data-table-css',
  enforce: 'pre',
  transform(code, id) {
    if (!id.includes('vue3-easy-data-table') || !id.includes('.css')) return
    return code.replace(/var\((easy-table-[\w-]+)\)/g, 'var(--$1)')
  },
})

// https://vite.dev/config/
export default defineConfig({
  // Absolute URLs (e.g. /icons/soccer.svg) point into public/ and must stay plain URLs.
  plugins: [fixEasyDataTableCss(), vue({ template: { transformAssetUrls: { includeAbsolute: false } } })],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    host: true,
  },
  test: {
    environment: 'jsdom',
    include: ['src/**/__tests__/*.spec.ts'],
  },
})
