<template>
  <div>
    <PermissionNode
      v-for="(child, index) in nodes"
      :key="index"
      :node="child"
      :checkedPermissions="modelValue"
      @update:checkedPermissions="updateCheckedPermissions"
    />
  </div>
</template>


<script setup>
import PermissionNode from './PermissionNode.vue'

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

const emit = defineEmits(['update:modelValue'])

const updateCheckedPermissions = (val) => {
  emit('update:modelValue', val)
}
</script>

