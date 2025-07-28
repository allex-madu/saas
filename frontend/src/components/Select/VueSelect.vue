<template>
  <div
    class="fromGroup relative"
    :class="{
      'has-error': error,
      'flex': horizontal,
      'is-valid': validate
    }"
  >
    <label
      v-if="label"
      :for="name"
      :class="`${classLabel} inline-block input-label`"
    >
      {{ label }}
    </label>

    <div class="relative">
      <slot v-if="$slots.default"></slot>

      <vSelect
        v-else
        v-model="localValue"
        :name="name"
        :id="name"
        :readonly="isReadonly"
        :disabled="disabled"
        :multiple="multiple"
        :options="options"
        :placeholder="placeholder"
        :label="optionLabel"
        :track-by="trackBy"
        :filterable="false"
        @search="$emit('search', $event)"
      >
        <!-- Slot para mensagem personalizada quando não houver opções -->
        <template #no-options>
          <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
            Digite pelo menos 2 letras para realizar a busca...
          </div>
        </template>
        <template #no-results>
          <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
            Nenhum registro encontrado com esses parâmetros.
          </div>
        </template>
      </vSelect>

      <div class="flex text-xl absolute right-[14px] top-1/2 -translate-y-1/2">
        <span v-if="error" class="text-danger-500">
          <Icon icon="heroicons-outline:information-circle" />
        </span>
        <span v-if="validate" class="text-success-500">
          <Icon icon="bi:check-lg" />
        </span>
      </div>
    </div>

    <span
      v-if="error"
      class="mt-2"
      :class="msgTooltip
        ? 'inline-block bg-danger-500 text-white text-[10px] px-2 py-1 rounded'
        : 'text-danger-500 block text-sm'"
    >
      {{ error }}
    </span>

    <span
      v-if="validate"
      class="mt-2"
      :class="msgTooltip
        ? 'inline-block bg-success-500 text-white text-[10px] px-2 py-1 rounded'
        : 'text-success-500 block text-sm'"
    >
      {{ validate }}
    </span>

    <span
      v-if="description"
      class="block text-secondary-500 font-light leading-4 text-xs mt-2"
    >
      {{ description }}
    </span>
  </div>
</template>


<script setup>
import { computed } from 'vue'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import Icon from '@/components/Icon/index.vue'

const props = defineProps({
  modelValue: [String, Array, Object],
  name: String,
  label: String,
  placeholder: {
    type: String,
    default: 'Selecione uma opção',
  },
  classLabel: {
    type: String,
    default: '',
  },
  error: String,
  validate: String,
  msgTooltip: {
    type: Boolean,
    default: false,
  },
  isReadonly: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  horizontal: {
    type: Boolean,
    default: false,
  },
  description: String,
  multiple: {
    type: Boolean,
    default: false,
  },
  options: {
    type: Array,
    default: () => [],
  },
  optionLabel: {
    type: String,
    default: 'label',
  },
  trackBy: {
    type: String,
    default: 'value',
  },
})

const emit = defineEmits(['update:modelValue', 'search'])


const localValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})
</script>

<style lang="scss">
@import "vue-select/dist/vue-select.css";

.fromGroup {
  .vs__dropdown-toggle {
    @apply bg-transparent dark:bg-slate-900 border-slate-200 dark:border-slate-700 dark:text-white min-h-[40px] text-slate-900 text-sm;
  }

  .v-select {
    @apply dark:text-slate-300;
  }

  &.has-error .vs__dropdown-toggle {
    @apply border-danger-500;
  }

  .vs__dropdown-option {
    @apply dark:text-slate-100;
  }

  .vs__dropdown-option--highlight {
    @apply bg-slate-900 dark:bg-slate-600 dark:bg-opacity-20 py-2 text-sm;
  }

  .vs__dropdown-menu {
    @apply shadow-dropdown bg-white dark:bg-slate-800 text-sm border-0 dark:border dark:border-slate-700;

    li {
      @apply capitalize;
    }
  }

  .vs__search::placeholder {
    @apply text-secondary-500;
  }

  .vs__actions svg {
    @apply fill-secondary-500 w-[15px] h-[15px] mt-[6px] scale-[.8];
  }

  .vs--multiple {
    .vs__selected {
      @apply text-xs text-slate-900 dark:text-slate-300 font-light bg-white dark:bg-slate-700 border-slate-200 dark:border-slate-700 border rounded-[3px] h-fit;
      padding: 4px 8px !important;
    }

    .vs__deselect {
      @apply dark:fill-slate-300;
    }

    .vs__selected-options {
      @apply items-center capitalize;

      svg {
        @apply scale-[0.8];
      }
    }
  }

  .vs--single .vs__selected {
    @apply dark:text-slate-300;
  }

  .vs__dropdown-option--disabled {
    @apply bg-slate-50 dark:bg-slate-700;
  }
}
</style>
