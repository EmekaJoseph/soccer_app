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
})
</script>

<style>
.siteBody {
  padding-bottom: 0.001rem;
}
</style>
