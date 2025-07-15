<template>
  <router-link :to="{ name: 'home' }">
    <img
      src="@/assets/images/logo/logo-c.svg"
      alt=""
      v-if="!themeStore.isDark"
    />

    <img
      src="@/assets/images/logo/logo-c-white.svg"
      alt=""
      v-if="themeStore.isDark"
    />
  </router-link>
</template>

<script setup>
import { useThemeSettingsStore } from '@/store/themeSettings'

const themeStore = useThemeSettingsStore()
</script>
