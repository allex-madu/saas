<template>
  <div>
    <Card :title="isEdit ? 'Editar Permissão' : 'Criar Nova Permissão'">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Nome -->
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Ex: create_products"
          prependIcon="heroicons-outline:identification"
          required
        />
        <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>

        <!-- Descrição -->
        <InputGroup
          label="Descrição"
          v-model="form.description"
          type="text"
          placeholder="Descrição da permissão (opcional)"
          prependIcon="heroicons-outline:document-text"
        />
        <span v-if="errors.description" class="text-sm text-red-500">{{ errors.description[0] }}</span>

        <!-- Botões -->
        <FormActions :isEdit="isEdit" :loading="loading" />
      </form>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import api from '@/plugins/axios'

import InputGroup from '@/components/InputGroup'
import Card from '@/components/Card'
import FormActions from '@/components/Form/FormActions'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const loading = ref(false)
const errors = ref({})
const isEdit = computed(() => !!route.params?.id)

const form = ref({
  name: '',
  description: '',
})

// Carrega permissão para edição
onMounted(async () => {
  if (isEdit.value) {
    loading.value = true
    try {
      const { data } = await api.get(`/api/v1/admin/permissions/${route.params.id}`)
      form.value.name = data.permission.name
      form.value.description = data.permission.description
    } catch (err) {
      toast.error('Erro ao carregar dados da permissão.')
      router.push({ name: 'admin.permissions.index' })
    } finally {
      loading.value = false
    }
  }
})

// Submete o formulário
const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    let response
    if (isEdit.value) {
      response = await api.put(`/api/v1/admin/permissions/${route.params.id}`, form.value)
      toast.success(`Permissão atualizada com sucesso!`)
    } else {
      response = await api.post('/api/v1/admin/permissions', form.value)
      toast.success(`Permissão "${response.data.permission.name}" criada com sucesso!`)
    }

    router.push({ name: 'admin.permissions.index' })
  } catch (err) {
    toast.error(isEdit.value ? 'Erro ao atualizar permissão.' : 'Erro ao criar permissão.')
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors || {}
    } else {
      console.error(err)
    }
  } finally {
    loading.value = false
  }
}
</script>
