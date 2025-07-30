<template>
  <!-- Contêiner com borda e padding -->
  <div class="border border-slate-200 dark:border-slate-700 rounded-md p-4">
    
    <!-- Botões de ação -->
    <div class="flex gap-2 mb-4">
      <Button @click="expandAll(true)" type="button" text="Expandir" btnClass="btn-dark btn-sm"/>
      <Button @click="expandAll(false)" type="button" text="Recolher" btnClass="btn-dark btn-sm"/>
      <Button @click="selectAll()" type="button" text="Todos" btnClass="btn-dark btn-sm"/>
      <Button @click="clearAll()" type="button" text="Limpar" btnClass="btn-dark btn-sm"/>
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
import Button from '@/components/Button'

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
