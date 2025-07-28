<template>
  <div>
    <Card title="Editar Usuário">
      <form @submit.prevent="submitForm" class="space-y-6">
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Digite o nome"
          prependIcon="heroicons-outline:user"
          required
          merged
        />

        <InputGroup
          label="Email"
          v-model="form.email"
          type="email"
          placeholder="Digite o e-mail"
          prependIcon="heroicons-outline:mail"
          required
          merged
        />

        <InputGroup
          label="Senha"
          v-model="form.password"
          type="password"
          placeholder="Crie uma nova senha (ou deixe em branco)"
          prependIcon="heroicons-outline:lock-closed"
          merged
        />

        <!-- Papéis -->
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-white">Atribuições</label>
          <VueSelect
            v-model="form.roles"
            :options="roles"
            multiple
            placeholder="Selecione as atribuições"
          />
        </div>

        <div class="flex justify-end gap-2">
          <Button btnClass="btn-dark" variant="outline" type="button" @click="router.back()">Cancelar</Button>
          <Button btnClass="btn-primary" type="submit" :loading="loading">Salvar</Button>
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
import VueSelect from '@/components/Select/VueSelect.vue'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const form = ref({
  name: '',
  email: '',
  password: '',
  roles: [], // será array de objetos {label, value}
})

const roles = ref([])
const loading = ref(true)

const loadUser = async (id) => {
  try {
    const { data } = await api.get(`/api/v1/admin/users/${id}`)
    const user = data.user

    form.value.name = user.person?.name || ''
    form.value.email = user.email || ''
    form.value.password = ''

    form.value.roles = (user.roles || []).map(role => ({
      label: role.name,
      value: role.id,
    }))
  } catch (error) {
    toast.error(`Erro ao carregar dados do usuário.`)
    console.error(error)
  } finally {
    loading.value = false
  }
}

const loadRoles = async () => {
  try {
    const { data } = await api.get('/api/v1/admin/roles')
    roles.value = data.roles.map(role => ({
      label: role.name,
      value: role.id,
    }))
  } catch (error) {
    toast.error(`Erro ao carregar papéis`)
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
      roles: form.value.roles.map(role => role.value),
    }

    await api.put(`/api/v1/admin/users/${userId}`, payload)
    toast.success('Usuário atualizado com sucesso')
    router.push({ name: 'admin.users' })
  } catch (error) {
    toast.error('Erro ao atualizar usuário')
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  const userId = route.params.id
  loadRoles()
  loadUser(userId)
})
</script>
