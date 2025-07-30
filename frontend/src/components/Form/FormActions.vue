<template>
  <div class="flex justify-end gap-2">
    <Button
      btnClass="btn-dark"
      type="button"
      variant="outline"
      @click="onCancel"
    >
      {{ cancelLabel }}
    </Button>
    <Button
      btnClass="btn-primary"
      type="submit"
      :loading="loading"
    >
      {{ isEdit ? submitLabelEdit : submitLabel }}
    </Button>
  </div>
</template>

<script setup>
import Button from '@/components/Button'
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({
  isEdit: Boolean,
  loading: Boolean,
  cancelLabel: {
    type: String,
    default: 'Cancelar'
  },
  submitLabel: {
    type: String,
    default: 'Salvar'
  },
  submitLabelEdit: {
    type: String,
    default: 'Salvar Alterações'
  },
  onCancelOverride: Function
})

const onCancel = () => {
  if (props.onCancelOverride) return props.onCancelOverride()
  router.back()
}
</script>
