<template>
  <header :class="navbarTypeClass">
      <div
        :class="`app-header md:px-6 px-[15px] dark:bg-slate-800 shadow-base dark:shadow-base3 bg-white ${borderSwitchClass.value} ${
          themeSettingsStore.navbarColor
        } ${
          themeSettingsStore.menuLayout === 'horizontal' && width.value > 1280
            ? 'py-1'
            : 'md:py-6 py-3'
        }`"
      >

      <div class="flex justify-between items-center h-full">
        <div
          v-if="themeSettingsStore.menuLayout === 'vertical'"
          class="flex items-center md:space-x-4 space-x-2 rtl:space-x-reverse"
        >
          <button
            class="ltr:mr-5 rtl:ml-5 text-xl text-slate-900 dark:text-white"
            v-if="themeSettingsStore.sidebarCollasp && width > 1280"
            @click="themeSettingsStore.sidebarCollasp = false"
          >
            <Icon
              icon="akar-icons:arrow-right"
              v-if="!themeSettingsStore.direction"
            />
            <Icon
              icon="akar-icons:arrow-left"
              v-if="themeSettingsStore.direction"
            />
          </button>
          <MobileLogo v-if="width < 1280" />
          <HandleMobileMenu v-if="width < 1280 && width > 768" />
          <SearchModal />
        </div>
        <div
          v-if="themeSettingsStore.menuLayout === 'horizontal'"
          class="flex items-center space-x-4 rtl:space-x-reverse"
        >
          <Logo v-if="width > 1280" />
          <MobileLogo v-else />
          <HandleMobileMenu v-if="width < 1280" />
        </div>
        <Mainnav
          v-if="themeSettingsStore.menuLayout === 'horizontal' && width > 1280"
        />
        <div
          class="nav-tools flex items-center lg:space-x-5 space-x-3 rtl:space-x-reverse"
        >
          <LanguageVue />
          <SwitchDark />
          <MonochromeMode />
          <Carti />
          <Message v-if="width > 768" />
          <Notification v-if="width > 768" />
          <Profile v-if="width > 768" />
          <HandleMobileMenu v-if="width < 768" />
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useWindowSize } from '@vueuse/core'

// Components
import Carti from "./Navtools/Carti.vue";
import Profile from "./Navtools/Profile.vue";
import Notification from "./Navtools/Notification.vue";
import Message from "./Navtools/Message.vue";
import SwitchDark from "./Navtools/SwitchDark.vue";
import MonochromeMode from "./Navtools/MonochromeMode.vue";
import Mainnav from "./horizental-nav.vue";
import Icon from "../Icon";
import SearchModal from "./Navtools/SearchModal.vue";
import LanguageVue from "./Navtools/Language.vue";
import Logo from "./Navtools/Logo.vue";
import MobileLogo from "./Navtools/MobileLogo.vue";
import window from "@/mixins/window";
import HandleMobileMenu from "./Navtools/HandleMobileMenu.vue";
import { useThemeSettingsStore } from '@/store/themeSettings'


// Stores & Utils
const themeSettingsStore = useThemeSettingsStore()
const { width } = useWindowSize()

// Computed classes
const navbarTypeClass = computed(() => {
  switch (themeSettingsStore.navbarType) {
    case 'floating':
      return 'floating'
    case 'sticky':
      return 'sticky top-0 z-[999]'
    case 'static':
      return 'static'
    case 'hidden':
      return 'hidden'
    default:
      return 'sticky top-0'
  }
})

const borderSwitchClass = computed(() => {
  if (
    themeSettingsStore.skin === 'bordered' &&
    themeSettingsStore.navbarType !== 'floating'
  ) {
    return 'border-b border-gray-5002 dark:border-slate-700'
  } else if (
    themeSettingsStore.skin === 'bordered' &&
    themeSettingsStore.navbarType === 'floating'
  ) {
    return 'border border-gray-5002 dark:border-slate-700'
  } else {
    return 'dark:border-b dark:border-slate-700 dark:border-opacity-60'
  }
})

  
</script>

<style lang="scss" scoped>
.floating .app-header {
  @apply md:mx-6 md:my-8 mx-[15px] my-[15px] rounded-md;
}
</style>
