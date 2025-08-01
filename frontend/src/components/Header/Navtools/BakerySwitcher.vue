<!-- src/components/Header/Navtools/BakerySwitcher.vue -->
<template>
  <div class="relative">
    <select
      v-model="activeBakeryId"
      @change="onBakeryChange"
      class="border rounded px-2 py-1 text-sm dark:bg-slate-800 dark:text-white"
    >
      <option disabled value="">Selecione uma padaria</option>
      <option
        v-for="bakery in bakeries"
        :key="bakery.id"
        :value="bakery.id"
      >
        {{ bakery.name }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useActiveBakeryStore } from '@/store/activeBakeryStore'

  const activeBakeryStore = useActiveBakeryStore()

  // Corrige o acesso à lista de padarias
  const bakeries = computed(() => activeBakeryStore.myBakeries?.data || [])

  // v-model do <select>
  const activeBakeryId = computed({
    get: () => activeBakeryStore.activeBakery?.id || '',
    set: (id) => {
      const selected = bakeries.value.find(b => b.id === parseInt(id))
      if (selected) activeBakeryStore.setActiveBakery(selected)
    }
  })

  function onBakeryChange() {
    // Você pode colocar alguma ação aqui se quiser
  }
</script>

