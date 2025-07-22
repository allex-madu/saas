<template>
  <div class="ml-4">
    <div class="flex items-center gap-2">
      <button
        v-if="hasChildren"
        @click="collapsed = !collapsed"
        type="button"
        class="text-sm"
      >
        {{ collapsed ? '▶' : '▼' }}
      </button>

      <input
        type="checkbox"
        :checked="isChecked"
        @change="toggleCheck"
        class="accent-primary-500"
      />

      <span class="capitalize text-sm font-medium">
        <template v-if="hasChildren">
          {{ node.title }}
        </template>
        <template v-else>
          {{ node.description || node.name?.split('.').pop() }}
        </template>
      </span>
    </div>

    <div v-if="hasChildren && !collapsed" class="ml-6 space-y-1 mt-1">
      <PermissionNode
        v-for="(child, index) in node.items"
        :key="index"
        :node="child"
        :checkedPermissions="checkedPermissions"
        @update:checkedPermissions="val => emit('update:checkedPermissions', val)"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  node: Object,
  checkedPermissions: Array
})

const emit = defineEmits(['update:checkedPermissions'])
const collapsed = ref(true)

const hasChildren = computed(() => Array.isArray(props.node.items) && props.node.items.length > 0)

// ✅ Pai está marcado se pelo menos um filho estiver marcado (com children), ou ele mesmo está marcado (sem children)
const isChecked = computed(() => {
  if (props.node.name) {
    return props.checkedPermissions.includes(props.node.name)
  }

  if (hasChildren.value) {
    return props.node.items.some(child => isChildChecked(child))
  }

  return false
})

const isChildChecked = (child) => {
  if (child.name) return props.checkedPermissions.includes(child.name)
  if (child.items) return child.items.some(grandChild => isChildChecked(grandChild))
  return false
}

const toggleCheck = () => {
  const allNames = collectNames(props.node)
  const allSelected = allNames.every(p => props.checkedPermissions.includes(p))

  let updated = [...props.checkedPermissions]

  if (allSelected) {
    // Desmarca todos
    updated = updated.filter(p => !allNames.includes(p))
  } else {
    // Marca todos
    updated = Array.from(new Set([...updated, ...allNames]))
  }

  emit('update:checkedPermissions', updated)
}

const collectNames = (node) => {
  let names = []

  if (node.name) {
    names.push(node.name)
  }

  if (Array.isArray(node.items)) {
    node.items.forEach(child => {
      names = names.concat(collectNames(child))
    })
  }

  return names
}
</script>


