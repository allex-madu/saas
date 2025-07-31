<template>
  <div>
    <Card :title="isEdit ? 'Editar Padaria' : 'Criar Nova Padaria'">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Nome -->
        <InputGroup
          label="Nome"
          v-model="form.name"
          placeholder="Ex: Padaria Central"
          prependIcon="heroicons-outline:office-building"
          required
        />
        <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>

        <!-- Slug -->
        <InputGroup
          label="Slug"
          v-model="form.slug"
          placeholder="Ex: padaria-central"
          prependIcon="heroicons-outline:hashtag"
          required
        />
        <span v-if="errors.slug" class="text-sm text-red-500">{{ errors.slug[0] }}</span>

        <!-- NIF -->
        <InputGroup
          label="NIF/CNPJ"
          v-model="form.nif"
          placeholder="00.000.000/0000-00"
          prependIcon="heroicons-outline:identification"
        />
        <span v-if="errors.nif" class="text-sm text-red-500">{{ errors.nif[0] }}</span>

        <!-- Telefone -->
        <InputGroup
          label="Telefone"
          v-model="form.phone"
          placeholder="(99) 99999-9999"
          prependIcon="heroicons-outline:phone"
        />
        <span v-if="errors.phone" class="text-sm text-red-500">{{ errors.phone[0] }}</span>

        <hr class="border-gray-300 dark:border-slate-600 my-4" />

        <!-- Informações do Administrador -->
        <div v-if="isEdit" class="text-sm text-slate-500 dark:text-slate-300 mb-2">
          <span class="text-slate-800 dark:text-slate-100">Administrador:</span>
          <span v-if="adminInfo.name || adminInfo.email">
            {{ adminInfo.name }}
            <span v-if="adminInfo.email">({{ adminInfo.email }})</span>
          </span>
          <span v-else>—</span>
        </div>

        <!-- Nome do Admin -->
        <InputGroup
          v-else
          label="Nome do Admin"
          v-model="form.admin_name"
          prependIcon="heroicons-outline:user"
          required
        />
        <span v-if="errors.admin_name" class="text-sm text-red-500">{{ errors.admin_name[0] }}</span>

        <!-- Email do Admin -->
        <InputGroup
          label="Email do Admin"
          v-model="form.admin_email"
          type="email"
          prependIcon="heroicons-outline:mail"
          required
        />
        <span v-if="errors.admin_email" class="text-sm text-red-500">{{ errors.admin_email[0] }}</span>

        <!-- Senha -->
        <InputGroup
          label="Senha"
          v-model="form.admin_password"
          type="password"
          prependIcon="heroicons-outline:lock-closed"
          :required="!isEdit"
        />
        <span v-if="errors.admin_password" class="text-sm text-red-500">{{ errors.admin_password[0] }}</span>

        <!-- Confirmação de Senha -->
        <InputGroup
          label="Confirmar Senha"
          v-model="form.admin_password_confirmation"
          type="password"
          prependIcon="heroicons-outline:lock-closed"
          :required="!isEdit"
        />
        <span v-if="errors.admin_password_confirmation" class="text-sm text-red-500">
          {{ errors.admin_password_confirmation[0] }}
        </span>

        <!-- Ações do formulário -->
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

// Componentes
import InputGroup from '@/components/InputGroup'
import Card from '@/components/Card'
import FormActions from '@/components/Form/FormActions'

// Instâncias
const route = useRoute()
const router = useRouter()
const toast = useToast()

// Estados
const loading = ref(false)
const errors = ref({})
const isEdit = computed(() => !!route.params?.id)

const form = ref({
  name: '',
  slug: '',
  nif: '',
  phone: '',
  admin_name: '',
  admin_email: '',
  admin_password: '',
  admin_password_confirmation: '',
})

const adminInfo = ref({ name: '', email: '' })

// Resetar formulário
const resetForm = () => {
  form.value = {
    name: '',
    slug: '',
    nif: '',
    phone: '',
    admin_name: '',
    admin_email: '',
    admin_password: '',
    admin_password_confirmation: '',
  }
  adminInfo.value = { name: '', email: '' }
}

// Buscar dados para edição
onMounted(async () => {
  if (isEdit.value) {
    try {
      const { data } = await api.get(`/api/v1/admin/bakeries/${route.params.id}`)

      form.value.name = data.name || ''
      form.value.slug = data.slug || ''
      form.value.nif = data.nif || ''
      form.value.phone = data.phone || ''
      form.value.admin_email = data.admin_email || ''

      adminInfo.value.name = data.admin_name || ''
      adminInfo.value.email = data.admin_email || ''
    } catch (error) {
      console.error('Erro ao carregar padaria:', error)
    }
  }
})

// Submeter formulário
const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    // Se estiver editando e não houver senha, remover os campos
    if (isEdit.value && !form.value.admin_password) {
      delete form.value.admin_password
      delete form.value.admin_password_confirmation
    }

    // Confirmação automática da senha
    if (form.value.admin_password) {
      form.value.admin_password_confirmation = form.value.admin_password
    }

    if (isEdit.value) {
      await api.put(`/api/v1/admin/bakeries/${route.params.id}`, form.value)
      toast.success('Padaria atualizada com sucesso!')
    } else {
      await api.post('/api/v1/admin/bakeries', form.value)
      toast.success('Padaria criada com sucesso!')
      resetForm()
    }

    router.push({ name: 'admin.bakeries.index' })
  } catch (err) {
    toast.error('Erro ao salvar padaria.')
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
