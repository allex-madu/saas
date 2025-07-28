<template>
  <div
    :class="cn('card rounded-md bg-white dark:bg-slate-800', props.class, {
      'border border-gray-5002 dark:border-slate-700': themeSettingsStore.skin === 'bordered',
      'shadow-base': themeSettingsStore.skin !== 'bordered',
    })"
    v-if="!overlay"
  >
    <div :class="cn('card-body flex flex-col', bodyClass)">
      <!-- Header (slot ou title/subtitle) -->
      <header
        v-if="title || subtitle || $slots.header"
        :class="cn('flex mb-5 items-center justify-between', {
          'order-1': imgTop,
          'border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6': !noborder
        })"
      >
        <template v-if="$slots.header">
          <slot name="header" />
        </template>
        <template v-else>
          <div>
            <div
              v-if="title"
              :class="cn('card-title text-slate-900 dark:text-white', titleClass)"
            >
              {{ title }}
            </div>
            <div v-if="subtitle" :class="cn('card-subtitle', subtitleClass)">
              {{ subtitle }}
            </div>
          </div>
        </template>
      </header>

      <!-- Imagem -->
      <div
        v-if="img"
        :class="cn('image-box', {
          'order-0': imgTop,
          '-mx-6': gapNull,
          '-mt-6': gapNull && imgTop,
          '-mb-6': gapNull && imgBottom,
          'order-3 mt-6': imgBottom,
          'mb-6': !imgBottom
        })"
      >
        <img
          :src="img"
          alt=""
          :class="cn('block w-full h-full object-cover', imaClass)"
        />
      </div>

      <!-- Conteúdo -->
      <div :class="cn('card-text h-full', { 'order-2': imgTop })">
        <slot />
      </div>
    </div>
  </div>

  <!-- Overlay card -->
  <div
    :class="cn('rounded-md overlay bg-no-repeat bg-center bg-cover card', customClass)"
    v-else
    :style="{ backgroundImage: `url(${img})` }"
  >
    <div :class="cn('card-body h-full flex flex-col justify-center', bodyClass)">
      <header class="mb-5">
        <div
          v-if="title"
          :class="cn('card-title text-slate-900 dark:text-white', titleClass)"
        >
          {{ title }}
        </div>
        <div v-if="subtitle" :class="cn('card-subtitle', subtitleClass)">
          {{ subtitle }}
        </div>
      </header>
      <div class="card-text h-full">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import { cn } from "@/lib/utils";
import { useThemeSettingsStore } from "@/store/themeSettings";

const themeSettingsStore = useThemeSettingsStore();

const props = defineProps({
  class: String,
  title: String,
  titleClass: String,
  subtitle: String,
  subtitleClass: String,
  img: String,
  imaClass: String,
  imgTop: Boolean,
  imgBottom: Boolean,
  gapNull: Boolean,
  overlay: Boolean,
  noborder: Boolean,
  bodyClass: {
    type: String,
    default: "p-6",
  },
  customClass: String,
});
</script>

<style lang="scss" scoped>
.card-title {
  @apply font-medium capitalize md:text-xl md:leading-[28px] text-lg leading-[24px];
}
.card-subtitle {
  @apply text-sm leading-5 font-medium text-slate-600 dark:text-slate-300 mt-1;
}
.overlay {
  @apply relative z-[1] after:absolute after:inset-0 after:w-full after:h-full after:bg-slate-900 after:bg-opacity-40 after:rounded-md after:z-[-1];
  .card-title,
  .card-subtitle {
    @apply text-white;
  }
}
</style>
