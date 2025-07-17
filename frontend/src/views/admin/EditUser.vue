<template>
  <div>
    <Card title="Editar Usuário">
      <form @submit.prevent="submitForm" class="space-y-6">
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Digite o nome"
          required
        />

        <InputGroup
          label="Email"
          v-model="form.email"
          type="email"
          placeholder="Digite o e-mail"
          required
        />

        <InputGroup
          label="Senha"
          v-model="form.password"
          type="password"
          placeholder="Crie uma senha"
        />

        <!-- Papéis -->
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-white">Papel</label>
          <select
            v-model="form.roles"
            required
            class="w-full rounded border border-gray-300 dark:border-slate-600 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white"
          >
            <option disabled value="">Selecione um papel</option>
            <option v-for="role in roles" :key="role.value" :value="role.value">
              {{ role.label }}
            </option>
          </select>
        </div>

        <div class="flex justify-end gap-2">
          <Button variant="outline" @click="router.back()">Cancelar</Button>
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

const form = ref({
  name: '',
  email: '',
  roles: [],
  password: ''
})

const roles = ref([])
const loading = ref(true)

const loadUser = async (id) => {
  try {
    const { data } = await api.get(`/api/v1/admin/users/${id}`)
    const user = data.user

    form.value.name = user.person?.name || ''
    form.value.email = user.email || ''
    form.value.roles = (user.roles || []).map(role => role.id)
    form.value.password = ''
  } catch (error) {
    toast.error(`Erro ao carregar dados: ${error.message}`)
    console.error(error)
  } finally {
    loading.value = false
  }
}

const loadRoles = async () => {
  try {
    const { data } = await api.get('/api/v1/admin/roles')
    roles.value = data.roles.map(role => ({
      value: role.id,
      label: role.name
    }))
  } catch (error) {
    toast.error(`Erro ao carregar papéis: ${error.message}`)
    console.error(error)
  }
}

const submitForm = async () => {
  loading.value = true
  try {
    const userId = route.params.id
    const payload = {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password || undefined,
      roles: Array.isArray(form.value.roles) ? form.value.roles : [form.value.roles],
    }

    await api.put(`/api/v1/admin/users/${userId}`, payload)
    toast.success('Usuário atualizado com sucesso')
    router.push({ name: 'admin.users', params: { id: userId } })
  } catch (error) {
    toast.error('Erro ao atualizar usuário')
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  const userId = route.params.id
  loadUser(userId)
  loadRoles()
})
</script>
