<template>
  <div :class="themeSettingsStore.semidark ? 'dark' : ''">
    <div
      :class="`sidebar-wrapper bg-white dark:bg-slate-800 
        ${themeSettingsStore.skin === 'bordered' ? 'border-r border-gray-5002 dark:border-slate-700' : 'shadow-base'}
        ${themeSettingsStore.sidebarCollasp ? closeClass : openClass}
        ${themeSettingsStore.isMouseHovered ? 'sidebar-hovered' : ''}`"
      @mouseenter="themeSettingsStore.isMouseHovered = true"
      @mouseleave="themeSettingsStore.isMouseHovered = false"
    >
      <div
        :class="`logo-segment flex justify-between items-center bg-white dark:bg-slate-800 z-[9] py-6 sticky top-0 px-4 
          ${themeSettingsStore.sidebarCollasp ? closeClass : openClass}
          ${themeSettingsStore.skin === 'bordered' ? 'border-b border-r border-gray-5002 dark:border-slate-700' : 'border-none'}
          ${themeSettingsStore.isMouseHovered ? 'logo-hovered' : ''}`"
      >
        <router-link
          :to="{ name: 'home' }"
          v-if="!themeSettingsStore.sidebarCollasp || themeSettingsStore.isMouseHovered"
        >
          <img
            src="@/assets/images/logo/logo.svg"
            alt="logo"
            v-if="!themeSettingsStore.isDark && !themeSettingsStore.semidark"
          />
          <img
            src="@/assets/images/logo/logo-white.svg"
            alt="logo white"
            v-else
          />
        </router-link>

        <router-link
          :to="{ name: 'home' }"
          v-else
        >
          <img
            src="@/assets/images/logo/logo-c.svg"
            alt="logo compact"
            v-if="!themeSettingsStore.isDark && !themeSettingsStore.semidark"
          />
          <img
            src="@/assets/images/logo/logo-c-white.svg"
            alt="logo compact white"
            v-else
          />
        </router-link>

        <span
          class="cursor-pointer text-slate-900 dark:text-white text-2xl"
          v-if="!themeSettingsStore.sidebarCollasp || themeSettingsStore.isMouseHovered"
          @click="themeSettingsStore.sidebarCollasp = !themeSettingsStore.sidebarCollasp"
        >
          <div
            class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150"
            :class="themeSettingsStore.sidebarCollasp ? '' : 'ring-2 ring-inset ring-offset-4 ring-black-900 dark:ring-slate-400 bg-slate-900 dark:bg-slate-400 dark:ring-offset-slate-700'"
          ></div>
        </span>
      </div>

      <div
        class="h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none"
        :class="[shallShadowBottom ? 'opacity-100' : 'opacity-0']"
      ></div>

      <perfect-scrollbar
        class="sidebar-menu px-4 h-[calc(100%-80px)]"
        @ps-scroll-y="(evt) => shallShadowBottom = evt.srcElement.scrollTop > 0"
        ref="shadowbase"
      >
        <Navmenu :items="menuItems" />

        <Transition @enter="enterWidget" @leave="leaveWidget">
          <div
            class="bg-slate-900 mb-10 mt-24 p-4 relative text-center rounded-2xl text-white"
            v-if="!themeSettingsStore.sidebarCollasp"
          >
            <img
              src="@/assets/images/svg/rabit.svg"
              alt="rabbit"
              class="mx-auto relative -mt-[73px]"
            />
            <div class="max-w-[160px] mx-auto mt-6">
              <div class="widget-title">Unlimited Access</div>
              <div class="text-xs font-light">Upgrade your system to business plan</div>
            </div>
            <div class="mt-6">
              <button class="btn bg-white hover:bg-opacity-80 text-slate-900 btn-sm w-full block">
                Upgrade
              </button>
            </div>
          </div>
        </Transition>
      </perfect-scrollbar>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { gsap } from 'gsap'
import { useThemeSettingsStore } from '@/store/themeSettings'
import Navmenu from './Navmenu.vue'
import { menuItems } from '@/constant/data'
import perfectScrollbar from 'simplebar-vue'

// Store
const themeSettingsStore = useThemeSettingsStore()

// Classes do sidebar
const openClass = 'w-[248px]'
const closeClass = 'w-[72px] close_sidebar'

// Shadow inferior da sidebar
const shallShadowBottom = ref(false)

// Animações da widget
const enterWidget = (el) => {
  gsap.fromTo(el, { x: 0, opacity: 0, scale: 0.5 }, { x: 0, opacity: 1, duration: 0.3, scale: 1 })
}
const leaveWidget = (el) => {
  gsap.fromTo(el, { x: 0, opacity: 1, scale: 1 }, { x: 0, opacity: 0, duration: 0.3, scale: 0.5 })
}
</script>

<style lang="scss">
.sidebar-wrapper {
  @apply fixed ltr:left-0 rtl:right-0 top-0 h-screen z-[999];
  transition: width 0.2s cubic-bezier(0.39, 0.575, 0.565, 1);
  will-change: width;
}

.nav-shadow {
  background: linear-gradient(
    rgb(255, 255, 255) 5%,
    rgba(255, 255, 255, 0.75) 45%,
    rgba(255, 255, 255, 0.2) 80%,
    transparent
  );
}

.dark .nav-shadow {
  background: linear-gradient(
    rgba(#1e293b, 1) 5%,
    rgba(#1e293b, 0.75) 45%,
    rgba(#1e293b, 0.2) 80%,
    transparent
  );
}

.sidebar-wrapper.sidebar-hovered {
  width: 248px !important;
}
.logo-segment.logo-hovered {
  width: 248px !important;
}
</style>
