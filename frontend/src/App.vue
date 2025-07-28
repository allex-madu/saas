<template>
  <router-view v-slot="{ Component }">
    <transition name="router-animation" mode="out-in" appear>
      <component :is="Component" />
    </transition>
  </router-view>
</template>

<script setup>
// Aqui não precisa atribuir o store globalmente
// Quem precisar do themeSettings, importa o useThemeSettingsStore direto
</script>
