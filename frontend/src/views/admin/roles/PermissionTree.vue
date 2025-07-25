<template>
  <div class="ml-4">
    <!-- Checkbox (opcional) e Título com ícone de expandir/recolher -->
    <div class="flex items-center space-x-2 mb-1">
      <!-- Renderiza o checkbox somente se houver nome -->
      <input
        v-if="item.name"
        type="checkbox"
        :value="item.name"
        v-model="modelValue"
        class="accent-primary-500"
      />

      <!-- Título clicável com toggle de expansão -->
      <span
        class="cursor-pointer select-none font-medium flex items-center"
        @click="toggle"
      >
        {{ item.title || item.description }}
        <!-- Ícone para expandir/recolher se houver filhos -->
        <i
          v-if="hasChildren"
          :class="expanded ? 'fa fa-caret-down ml-2' : 'fa fa-caret-right ml-2'"
        ></i>
      </span>
    </div>

    <!-- Recursão: renderiza os filhos apenas se expandido -->
    <div v-if="expanded && hasChildren" class="ml-4 border-l pl-2">
      <PermissionTree
        v-for="child in item.items"
        :key="child.name || child.title || child.description" 
        :item="child"
        v-model="modelValue"
      />
    </div>
  </div>
</template>

<script setup>
// Importa a própria árvore para renderização recursiva
import { ref, computed } from 'vue'
import PermissionTree from './PermissionTree.vue'

// Props e emits do componente
const props = defineProps({
  item: Object,
  modelValue: Array,
})
const emit = defineEmits(['update:modelValue'])

// Estado de expansão do grupo
const expanded = ref(false)

// Verifica se há filhos no item atual
const hasChildren = computed(() => {
  return Array.isArray(props.item?.items) && props.item.items.length > 0
})

// Alterna o estado de expansão
const toggle = () => {
  if (hasChildren.value) {
    expanded.value = !expanded.value
  }
}
</script>
