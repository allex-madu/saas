<template>
  <div>
    <Card title="Editar Usuário">
      <form @submit.prevent="submitForm" class="space-y-6">

        <!-- Nome -->
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Digite o nome"
          prependIcon="heroicons-outline:user"
          required
          merged
        />

        <!-- CPF/CNPJ -->
        <InputGroup
          label="CPF/CNPJ"
          v-model="form.nif"
          type="text"
          placeholder="Digite o CPF ou CNPJ"
          prependIcon="heroicons-outline:identification"
          required
          merged
        />

        <!-- Telefone -->
        <InputGroup
          label="Telefone"
          v-model="form.phone"
          type="text"
          placeholder="Digite o telefone"
          prependIcon="heroicons-outline:phone"
          required
          merged
        />

        <!-- Endereço -->
        <InputGroup
          label="Endereço"
          v-model="form.address"
          type="text"
          placeholder="Digite o endereço"
          prependIcon="heroicons-outline:map"
          required
          merged
        />

        <!-- Cidade -->
        <VueSelect
          label="Cidade"
          v-model="form.city_id"
          :options="cities"
          :optionLabel="'name'"
          :track-by="'id'"
          name="city_id"
          placeholder="Selecione a cidade"
          @search="onSearchCity"
        />

        <!-- Email -->
        <InputGroup
          label="Email"
          v-model="form.email"
          type="email"
          placeholder="Digite o e-mail"
          prependIcon="heroicons-outline:mail"
          required
          merged
        />

        <!-- Nova senha -->
        <InputGroup
          label="Nova Senha"
          v-model="form.password"
          type="password"
          placeholder="Crie uma nova senha (ou deixe em branco)"
          prependIcon="heroicons-outline:lock-closed"
          merged
        />

        <!-- Papéis -->
        <VueSelect
          label="Atribuições"
          v-model="form.roles"
          :options="roles"
          multiple
          :optionLabel="'label'"
          :track-by="'value'"
          placeholder="Selecione os papéis"
        />

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
import { debounce } from 'lodash'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const loading = ref(false)
const cities = ref([])
const roles = ref([])

const form = ref({
  name: '',
  email: '',
  password: '',
  nif: '',
  phone: '',
  address: '',
  city_id: null,
  roles: [],
})

const loadUser = async (id) => {
  try {
    const { data } = await api.get(`/api/v1/admin/users/${id}/edit`)
    const user = data.user

    form.value = {
      name: user.person?.name || '',
      email: user.email || '',
      password: '',
      nif: user.person?.nif || '',
      phone: user.person?.phone || '',
      address: user.person?.address || '',
      city_id: user.person?.city
        ? {
            id: user.person.city.id,
            name: `${user.person.city.title} - ${user.person.city.state?.letter || ''}`
          }
        : null,
        roles: user.roles.map(role => ({ label: role.name, value: role.id })),
    }

    if (form.value.city_id) {
      cities.value = [form.value.city_id]
    }
  } catch (error) {
    toast.error('Erro ao carregar dados do usuário.')
    console.error(error)
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
    toast.error('Erro ao carregar papéis.')
    console.error(error)
  }
}

const onSearchCity = debounce(async (term) => {
  if (term.length >= 2) {
    try {
      const { data } = await api.get('/api/v1/admin/cities', {
        params: { search: term }
      })
      cities.value = data.data
    } catch (error) {
      console.error('Erro ao buscar cidades', error)
    }
  } else {
    cities.value = []
  }
}, 300)


const submitForm = async () => {
  loading.value = true
  try {
    const userId = route.params.id
    const payload = {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password || undefined,
      nif: form.value.nif,
      phone: form.value.phone,
      address: form.value.address,
      city_id: form.value.city_id?.id || null,
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
