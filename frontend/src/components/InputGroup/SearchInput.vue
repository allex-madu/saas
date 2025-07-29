<template>
  <div class="relative w-full">
    <input
      v-model="inputValue"
      :placeholder="placeholder"
      type="text"
      class="w-full rounded border border-gray-300 px-4 pr-10 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:border-slate-600 dark:text-white"
    />

    <!-- Botão "X" para limpar -->
    <button
      v-if="inputValue"
      @click="clearInput"
      type="button"
      class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
    >
      <!-- Ícone heroicons outline: x-circle -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Buscar...',
  },
})

const emit = defineEmits(['update:modelValue', 'clear'])

const inputValue = ref(props.modelValue)

// Sincronizar modelValue externo → local
watch(() => props.modelValue, val => {
  inputValue.value = val
})

// Emitir local → externo
watch(inputValue, val => {
  emit('update:modelValue', val)
})

// Limpar campo
const clearInput = () => {
  inputValue.value = ''
  emit('clear')
}
</script>
