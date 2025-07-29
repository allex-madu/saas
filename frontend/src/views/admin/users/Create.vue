<template>
  <div>
    <Card title="Criar Novo Usuário">
      <div class="space-y-4">
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
          <span v-if="errors.cpf_cnpj" class="text-sm text-red-500">{{ errors.cpf_cnpj[0] }}</span>

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
            :error="errors?.city_id?.[0]"
            @search="onSearchCity"
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
            label="Senha"
            v-model="form.password"
            type="password"
            placeholder="mínimo 8 caracteres"
            prependIcon="heroicons-outline:lock-closed"
            required
            merged
          />
          <span v-if="errors.password" class="text-sm text-red-500">{{ errors.password[0] }}</span>

          <!-- Papéis -->
          <VueSelect
            label="Atribuições"
            :optionLabel="'label'"
            :track-by="'value'"
            :options="roles"
            v-model="form.roles"
            multiple
            name="roles"
            placeholder="Selecione os papéis"
            :error="errors?.roles?.[0]"
          />
          <span v-if="errors.roles" class="text-sm text-red-500">{{ errors.roles[0] }}</span>

          <!-- Botões -->
          <div class="flex justify-end gap-2">
            <Button btnClass="btn-dark" type="button" variant="outline" @click="$router.back()">Cancelar</Button>
            <Button btnClass="btn-primary" type="submit" :loading="loading">Salvar</Button>
          </div>

        </form>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted, toRefs } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { debounce } from 'lodash'
import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'
import VueSelect from '@/components/Select/VueSelect.vue'
import { useAdminUserStore } from '@/store/adminUserStore'

const router = useRouter()
const toast = useToast()

// Store e estados reativos
const store = useAdminUserStore()
const { roles, errors, loading, cities } = toRefs(store)
const { createUser, fetchRoles, searchCities } = store

// Formulário inicial
const form = ref({
  name: '',
  email: '',
  password: '',
  nif: '',
  phone: '',
  city_id: null,
  address: '',
  active: { label: 'Sim', value: true },
  roles: [],
})

// Carrega os papéis na montagem
onMounted(async () => {
  if (!roles.value.length) {
    try {
      await fetchRoles()
    } catch {
      toast.error('Erro ao carregar papéis.')
    }
  }
})

// Busca cidades ao digitar no select
const onSearchCity = debounce(async (term) => {
  if (term.length >= 2) {
    await searchCities(term)
  } else {
    cities.value = []
  }
}, 300)

// Envia os dados para a store
const handleSubmit = async () => {
  if (loading.value) return // ← evita múltiplos envios

  try {
    const payload = {
      ...form.value,
      active: form.value.active?.value ?? true,
      city_id: form.value.city_id?.id ?? null,
      roles: form.value.roles
        .filter(role => role?.value !== null && role?.value !== undefined)
        .map(role => Number(role.value)),
    }

    await createUser(payload)
    toast.success('Usuário registrado com sucesso', { id: 'user-create' })
    router.push({ name: 'admin.users' })
  } catch (err) {
    if (err.response?.status !== 422) {
      toast.error(err.response?.data?.message || 'Erro ao registrar usuário.')
    }
  }
}
</script>
