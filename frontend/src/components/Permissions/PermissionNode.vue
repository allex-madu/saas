<template>
  <div class="ml-4">
    <!-- Linha principal: checkbox + título + botão de expandir -->
    <div class="flex items-center gap-2 mb-0.5">
      <input
        type="checkbox"
        :checked="isChecked"
        @change="toggleCheck"
        class="accent-primary-500"
      />

      <div class="flex items-center gap-1">
        <!-- Título do item ou permissão -->
        <span
          :class="[
            hasChildren
              ? 'capitalize text-[13px] text-slate-800 dark:text-slate-300'
              : 'text-[12px] text-slate-500 dark:text-slate-400'
          ]"
        >
          <template v-if="hasChildren">
            {{ node.title }}
          </template>
          <template v-else>
            {{ node.description || node.name?.split('.').pop() }}
          </template>
        </span>

        <!-- Botão para expandir/recolher (▾ ou ▸) -->
        <button
          v-if="hasChildren"
          @click="collapsed = !collapsed"
          type="button"
          class="text-xs w-4 h-4 text-gray-500 hover:text-gray-700"
        >
          {{ collapsed ? '▸' : '▾' }}
        </button>
      </div>
    </div>

    <!-- Área de filhos (apenas se expandido) -->
    <div
      v-if="hasChildren && !collapsed"
      class="mt-0.5 ps-[1.3rem] border-s-[0,1px] border-l border-slate-200 dark:border-slate-600 dark:text-gray-500"
    >
      <!-- Renderiza em linha se todos os filhos forem permissões -->
      <div
        v-if="onlyLeafChildren"
        class="flex flex-wrap gap-x-0 gap-y-0.5 items-center"
      >
        <PermissionNode
          v-for="(child, index) in node.items"
          :key="index"
          :node="child"
          :checkedPermissions="checkedPermissions"
          :forceExpand="forceExpand"
          @update:checkedPermissions="val => emit('update:checkedPermissions', val)"
        />
      </div>

      <!-- Caso contrário, renderiza os filhos empilhados -->
      <div v-else class="space-y-1">
        <PermissionNode
          v-for="(child, index) in node.items"
          :key="index"
          :node="child"
          :checkedPermissions="checkedPermissions"
          :forceExpand="forceExpand"
          @update:checkedPermissions="val => emit('update:checkedPermissions', val)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

// Props esperadas
const props = defineProps({
  node: Object,
  checkedPermissions: Array,
  forceExpand: {
    type: Boolean,
    default: undefined,
  },
})

// Emite alterações de permissões marcadas
const emit = defineEmits(['update:checkedPermissions'])

const collapsed = ref(true)

// Reage à prop forceExpand para expandir/recolher
watch(() => props.forceExpand, (val) => {
  if (val !== undefined) {
    collapsed.value = !val ? true : false
  }
}, { immediate: true })

// Verifica se o nó atual possui filhos
const hasChildren = computed(() =>
  Array.isArray(props.node.items) && props.node.items.length > 0
)

// Define se o item está selecionado
const isChecked = computed(() => {
  if (props.node.name) {
    return props.checkedPermissions.includes(props.node.name)
  }
  if (hasChildren.value) {
    return props.node.items.some(child => isChildChecked(child))
  }
  return false
})

// Se todos os filhos forem folhas (sem filhos)
const onlyLeafChildren = computed(() => {
  return hasChildren.value &&
    props.node.items.every(child => child.name && !child.items)
})

// Verifica recursivamente se algum filho está marcado
const isChildChecked = (child) => {
  if (child.name) return props.checkedPermissions.includes(child.name)
  if (child.items) return child.items.some(grandChild => isChildChecked(grandChild))
  return false
}

// Alterna seleção do grupo (pai e filhos)
const toggleCheck = () => {
  const allNames = collectNames(props.node)
  const allSelected = allNames.every(p => props.checkedPermissions.includes(p))

  let updated = [...props.checkedPermissions]

  if (allSelected) {
    updated = updated.filter(p => !allNames.includes(p))
  } else {
    updated = Array.from(new Set([...updated, ...allNames]))
  }

  emit('update:checkedPermissions', updated)
}

// Coleta todos os "name" das permissões (recursivamente)
const collectNames = (node) => {
  let names = []
  if (node.name) names.push(node.name)
  if (Array.isArray(node.items)) {
    node.items.forEach(child => {
      names = names.concat(collectNames(child))
    })
  }
  return names
}
</script>
