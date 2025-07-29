<template>
  <!-- Contêiner com borda e padding -->
  <div class="border border-slate-200 dark:border-slate-700 rounded-md p-4">
    
    <!-- Botões de ação -->
    <div class="flex gap-2 mb-4">
      <button
        type="button"
        class="bg-slate-800 text-white px-3 py-1 rounded"
        @click="expandAll(true)"
      >
        Expandir
      </button>
      <button
        type="button"
        class="bg-slate-800 text-white px-3 py-1 rounded"
        @click="expandAll(false)"
      >
        Recolher
      </button>
      <button
        type="button"
        class="bg-slate-800 text-white px-3 py-1 rounded"
        @click="selectAll()"
      >
        Marcar Todos
      </button>
      <button
        type="button"
        class="bg-slate-800 text-white px-3 py-1 rounded"
        @click="clearAll()"
      >
        Limpar
      </button>
    </div>

    <!-- Renderização da árvore de permissões -->
    <PermissionNode
      v-for="(child, index) in nodes"
      :key="index"
      :node="child"
      :checkedPermissions="modelValue"
      :forceExpand="forceExpand"
      @update:checkedPermissions="updateCheckedPermissions"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import PermissionNode from './PermissionNode.vue'

// Props do componente
const props = defineProps({
  nodes: {
    type: Array,
    required: true,
  },
  modelValue: {
    type: Array,
    default: () => [],
  },
})

// Emit para v-model
const emit = defineEmits(['update:modelValue'])

// Controle global de expansão da árvore
const forceExpand = ref(undefined)

// Atualiza as permissões marcadas
const updateCheckedPermissions = (val) => {
  emit('update:modelValue', val)
}

// Expande ou recolhe toda a árvore
const expandAll = (val) => {
  forceExpand.value = val
}

// Marca todas as permissões
const selectAll = () => {
  emit('update:modelValue', collectAllPermissions(props.nodes))
}

// Limpa todas as permissões
const clearAll = () => {
  emit('update:modelValue', [])
}

// Coleta todos os nomes de permissões recursivamente
const collectAllPermissions = (nodes) => {
  let all = []
  for (const node of nodes) {
    if (node.name) all.push(node.name)
    if (node.items) all = all.concat(collectAllPermissions(node.items))
  }
  return all
}
</script>
