<template>
  <div
    :class="`mobile-sidebar bg-white dark:bg-slate-800 ${
      themeSettingsStore.theme === 'bordered'
        ? 'border border-gray-5002'
        : 'shadow-base'
    }`"
  >
    <div class="logo-segment flex justify-between items-center px-4 py-6">
      <router-link :to="{ name: 'home' }">
        <img
          src="@/assets/images/logo/logo.svg"
          alt="Logo"
          v-if="!themeSettingsStore.isDark"
        />
        <img
          src="@/assets/images/logo/logo-white.svg"
          alt="Logo White"
          v-else
        />
      </router-link>
      <span
        class="cursor-pointer text-slate-900 dark:text-white text-2xl"
        @click="toggleMsidebar"
      >
        <Icon icon="heroicons:x-mark" />
      </span>
    </div>

    <perfect-scrollbar class="sidebar-menu px-4 h-[calc(100%-100px)]">
      <Navmenu :items="menuItems" />
      <div
        class="bg-slate-900 mb-[100px] mt-14 p-4 relative text-center rounded-2xl text-white"
        v-if="!themeSettingsStore.sidebarCollasp"
      >
        <img
          src="@/assets/images/svg/rabit.svg"
          alt="Rabbit"
          class="mx-auto relative -mt-[73px]"
        />
        <div class="max-w-[160px] mx-auto mt-6">
          <div class="widget-title">Unlimited Access</div>
          <div class="text-xs font-light">
            Upgrade your system to business plan
          </div>
        </div>
        <div class="mt-6">
          <button
            class="btn bg-white hover:bg-opacity-80 text-slate-900 btn-sm w-full block"
          >
            Upgrade
          </button>
        </div>
      </div>
    </perfect-scrollbar>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue'
import Navmenu from './Navmenu.vue'
import { menuItems } from '@/constant/data'
import { useThemeSettingsStore } from '@/store/themeSettings'

// Store
const themeSettingsStore = useThemeSettingsStore()

// Métodos
const toggleMsidebar = () => {
  themeSettingsStore.toggleMsidebar()
}
</script>

<style lang="scss" scoped>
.mobile-sidebar {
  @apply fixed ltr:left-0 rtl:right-0 top-0 h-full z-[9999] w-[280px];
}
</style>
