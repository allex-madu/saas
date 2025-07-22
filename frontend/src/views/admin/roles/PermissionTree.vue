<template>
  <div class="ml-4">
    <!-- Checkbox e Título -->
    <div class="flex items-center space-x-2 mb-1">
      <input
        v-if="item.name"
        type="checkbox"
        :value="item.name"
        v-model="modelValue"
        class="accent-primary-500"
      />
      <span
        class="cursor-pointer font-medium select-none"
        @click="toggle"
      >
        {{ item.title || item.description }}
        <i
          v-if="hasChildren"
          :class="expanded ? 'fa fa-caret-down ml-1' : 'fa fa-caret-right ml-1'"
        ></i>
      </span>
    </div>

    <!-- Recursão -->
    <div v-if="expanded && hasChildren" class="ml-4 border-l pl-2">
      <PermissionTree
        v-for="(child, index) in item.items"
        :key="index"
        :item="child"
        v-model="modelValue"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import PermissionTree from './PermissionTree.vue'

const props = defineProps({
  item: Object,
  modelValue: Array
})

const emit = defineEmits(['update:modelValue'])

const expanded = ref(false)

const hasChildren = computed(() => {
  return Array.isArray(props.item?.items) && props.item.items.length > 0
})

const toggle = () => {
  if (hasChildren.value) expanded.value = !expanded.value
}
</script>
