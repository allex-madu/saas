<template>
  <div>
    <Card title="Criar Novo Usuário">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
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
            v-model="form.cpf_cnpj"
            type="text"
            placeholder="Digite o CPF ou CNPJ"
            prependIcon="heroicons-outline:identification"
            required
            merged
          />
          <span v-if="errors.cpf_cnpj" class="text-sm text-red-500">{{ errors.cpf_cnpj[0] }}</span>


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
          />
          <span v-if="errors.city_id" class="text-sm text-red-500">{{ errors.city_id[0] }}</span>

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

          <!-- Ativo -->
          <div class="space-y-5">
            <VueSelect
              label="Ativo"
              v-model="form.active"
              :options="[
                { label: 'Sim', value: true },
                { label: 'Não', value: false }
              ]"
              :optionLabel="'label'"
              :track-by="'value'"
              name="active"
              placeholder="Selecione o status"
              :error="errors?.active?.[0]"
            />
            <span v-if="errors.active" class="text-sm text-red-500">{{ errors.active[0] }}</span>
          </div>

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

          <!-- Confirmar Senha -->
          <InputGroup
            label="Confirmar Senha"
            v-model="form.password_confirmation"
            type="password"
            placeholder="Digite novamente a senha"
            prependIcon="heroicons-outline:lock-closed"
            required
            merged
          />
          <span v-if="errors.password_confirmation" class="text-sm text-red-500">{{ errors.password_confirmation[0] }}</span>

          <!-- Papéis -->
          <div class="space-y-5">
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
          </div>
        </div>
       
        
        <!-- Botões -->
        <div class="flex justify-end gap-2">
          <Button btnClass="btn-dark" type="button" variant="outline" @click="$router.back()">Cancelar</Button>
          <Button btnClass="btn-primary" type="button" :loading="loading" @click="handleSubmit">Salvar</Button>
        </div>
      </form>
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
  password_confirmation: '',
  nif: '',
  phone: '',
  city_id: null,
  address: '',
  active: true,
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
