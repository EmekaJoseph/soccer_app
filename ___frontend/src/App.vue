<template>
  <!-- <div class="sticky-top text-center bg-warning small">Demo</div> -->
  <div class="siteBody">
    <RouterView />
  </div>
</template>

<script setup lang="ts">
import { watchEffect } from 'vue';
import { useTemplateStore } from './store/templateStore';
const template = useTemplateStore()

watchEffect(() => {
  document.documentElement.setAttribute('data-bs-theme', template.appTheme)
  document.querySelector('meta[name="theme-color"]')?.setAttribute('content', template.appTheme == 'dark' ? '#0a0c0b' : '#f6f7f4')
})
</script>

<style>
.siteBody {
  padding-bottom: 0.001rem;
}
</style>
