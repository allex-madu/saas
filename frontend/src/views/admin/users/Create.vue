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
            <Button btnClass="btn-primary" type="button" :loading="loading" @click="handleSubmit">Salvar</Button>
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
import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'
import VueSelect from '@/components/Select/VueSelect.vue'
import { useAdminUserStore } from '@/store/adminUserStore'

const router = useRouter()
const toast = useToast()

// Store e estados reativos
const store = useAdminUserStore()
const { roles, errors, loading } = toRefs(store)
const { createUser, fetchRoles } = store

// Dados do formulário
const form = ref({
  name: '',
  email: '',
  password: '',
  roles: [],
})

// Carrega os papéis uma vez ao montar
onMounted(async () => {
  if (!roles.value.length) {
    try {
      await fetchRoles()
    } catch (e) {
      toast.error('Erro ao carregar papéis.')
    }
  }
})

// Submete o formulário
const handleSubmit = async () => {
  try {
    const payload = {
      ...form.value,
      roles: form.value.roles
        .filter(role => role?.value !== null && role?.value !== undefined)
        .map(role => Number(role.value)), // transforma para IDs inteiros
    }

    await createUser(payload)
    toast.success('Usuário registrado com sucesso')
    router.push({ name: 'admin.users' })
  } catch (err) {
    if (err.response?.status !== 422) {
      toast.error(err.response?.data?.message || 'Erro ao registrar usuário.')
    }
  }
}
</script>
