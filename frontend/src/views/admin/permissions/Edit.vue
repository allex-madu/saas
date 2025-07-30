<template>
  <div>
    <Card title="Editar Permissão">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Nome -->
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Digite o nome da permissão"
          prependIcon="heroicons-outline:tag"
          required
        />
        <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>

        <!-- Descrição -->
        <InputGroup
          label="Descrição"
          v-model="form.description"
          type="text"
          placeholder="Opcional"
          prependIcon="heroicons-outline:pencil-alt"
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
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'

import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const loading = ref(false)
const errors = ref({})
const form = ref({
  name: '',
  description: '',
})

/**
 * Carrega os dados da permissão
 */
const loadPermission = async () => {
  loading.value = true
  try {
    const { data } = await api.get(`/api/v1/admin/permissions/${route.params.id}`)
    form.value.name = data.permission.name
    form.value.description = data.permission.description
  } catch (err) {
    toast.error('Erro ao carregar dados da permissão.')
    console.error(err)
  } finally {
    loading.value = false
  }
}

/**
 * Envia atualização
 */
const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    await api.put(`/api/v1/admin/permissions/${route.params.id}`, form.value)
    toast.success(`Permissão atualizada com sucesso!`, { timeout: 2000 })
    router.push({ name: 'admin.permissions.index' })
  } catch (err) {
    toast.error('Erro ao atualizar permissão.', { timeout: 2000 })
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors || {}
    } else {
      console.error(err)
    }
  } finally {
    loading.value = false
  }
}

onMounted(loadPermission)
</script>
