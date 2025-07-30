<template>
  <div>
    <Card :title="isEdit ? 'Editar Usuário' : 'Criar Novo Usuário'">
      <form @submit.prevent="handleSubmit" class="space-y-6">
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
        <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>

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
        <span v-if="errors.nif" class="text-sm text-red-500">{{ errors.nif[0] }}</span>

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
        <span v-if="errors.phone" class="text-sm text-red-500">{{ errors.phone[0] }}</span>

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
        <span v-if="errors.address" class="text-sm text-red-500">{{ errors.address[0] }}</span>

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
          :error="errors.city_id?.[0]"
        />
        <span v-if="errors.city_id" class="text-sm text-red-500">{{ errors.city_id[0] }}</span>

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
        <span v-if="errors.email" class="text-sm text-red-500">{{ errors.email[0] }}</span>

        <!-- Senha -->
        <InputGroup
          :label="isEdit ? 'Nova Senha' : 'Senha'"
          v-model="form.password"
          type="password"
          placeholder="mínimo 8 caracteres"
          prependIcon="heroicons-outline:lock-closed"
          :required="!isEdit"
          merged
        />
        <span v-if="errors.password" class="text-sm text-red-500">{{ errors.password[0] }}</span>

        <!-- Papéis -->
        <VueSelect
          label="Atribuições"
          v-model="form.roles"
          :options="roles"
          multiple
          :optionLabel="'label'"
          :track-by="'value'"
          placeholder="Selecione os papéis"
          :error="errors.roles?.[0]"
        />
        <span v-if="errors.roles" class="text-sm text-red-500">{{ errors.roles[0] }}</span>

        <!-- Ações -->
        <FormActions :isEdit="isEdit" :loading="loading" />
        
      </form>
    </Card>
  </div>
</template>

<script setup>
import { reactive, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { debounce } from 'lodash'
import { useToast } from 'vue-toastification'
import { useAdminUserStore } from '@/store/adminUserStore'

import Card from '@/components/Card'
import FormActions from '@/components/Form/FormActions'
import InputGroup from '@/components/InputGroup'
import VueSelect from '@/components/Select/VueSelect.vue'
import api from '@/plugins/axios'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const store = useAdminUserStore()
const isEdit = computed(() => !!route.params.id)

const loading = computed(() => store.loading)
const errors = computed(() => store.errors)
const cities = computed(() => store.cities)
const roles = computed(() => store.roles)

const form = reactive({
  name: '',
  email: '',
  password: '',
  nif: '',
  phone: '',
  address: '',
  city_id: null,
  roles: [],
})

// Buscar papéis do backend e preencher a store
const fetchRoles = async () => {
  try {
    await store.fetchRoles()
  } catch {
    toast.error('Erro ao carregar papéis.')
  }
}

// Preencher formulário com os dados do usuário
const fetchUser = async (id) => {
  try {
    const { data } = await api.get(`/api/v1/admin/users/${id}/edit`)
    const user = data.user

    Object.assign(form, {
      name: user.person?.name || '',
      email: user.email,
      password: '',
      nif: user.person?.nif || '',
      phone: user.person?.phone || '',
      address: user.person?.address || '',
      city_id: user.person?.city
        ? {
            id: user.person.city.id,
            name: `${user.person.city.title} - ${user.person.city.state?.letter || ''}`,
          }
        : null,
      roles: Array.isArray(user.roles)
        ? user.roles.map(role => ({ label: role.name, value: role.id }))
        : [],
    })

    // Adiciona a cidade atual na lista para exibição correta
    if (form.city_id) {
      store.cities = [form.city_id]
    }
  } catch (err) {
    toast.error('Erro ao carregar usuário.')
    console.error(err)
  }
}

// Busca cidades dinamicamente com debounce
const onSearchCity = debounce(async (term) => {
  if (term.length < 2) return (store.cities = [])
  try {
    await store.searchCities(term)
  } catch (err) {
    console.error('Erro ao buscar cidades', err)
  }
}, 300)

// Envia formulário com base no modo (criar ou editar)
const handleSubmit = async () => {
  if (loading.value) return

  const payload = {
    name: form.name,
    email: form.email,
    password: form.password || undefined,
    nif: form.nif,
    phone: form.phone,
    address: form.address,
    city_id: form.city_id?.id || null,
    roles: form.roles.map(role => role.value),
    active: true,
  }

  try {
    if (isEdit.value) {
      await store.updateUser(route.params.id, payload)
      toast.success('Usuário atualizado com sucesso!')
    } else {
      await store.createUser(payload)
      toast.success('Usuário criado com sucesso!')
    }

    router.push({ name: 'admin.users' })
  } catch (err) {
    if (err.response?.status !== 422) {
      toast.error('Erro ao salvar usuário.')
    }
    console.error(err)
  }
}

// Quando o componente monta, buscar roles e (se necessário) os dados do usuário
onMounted(async () => {
  await fetchRoles()
  if (isEdit.value) {
    await fetchUser(route.params.id)
  }
})
</script>
