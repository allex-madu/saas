<template>
  <div>
    <Card title="Criar Nova Permissão">
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
        <div class="flex justify-end gap-2">
          <Button type="button" variant="outline" @click="$router.back()">Cancelar</Button>
          <Button type="submit" :loading="loading">Salvar</Button>
        </div>
      </form>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'

import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'

const router = useRouter()
const toast = useToast()
const loading = ref(false)
const errors = ref({})

const form = ref({
  name: '',
  description: '',
})

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    const response = await api.post('/api/v1/admin/permissions', form.value)

    toast.success(`Permissão "${response.data.permission.name}" criada com sucesso!`, { timeout: 2000 })

    router.push({ name: 'admin.permissions.index' })
  } catch (err) {
    toast.error('Erro ao criar permissão.', { timeout: 2000 })

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
