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

        <!-- Admin Name -->
        <InputGroup
          label="Nome do Administrador"
          v-model="form.admin_name"
          prependIcon="heroicons-outline:user"
          required
        />
        <span v-if="errors.admin_name" class="text-sm text-red-500">{{ errors.admin_name[0] }}</span>

        <!-- Email Admin -->
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

        <!-- Confirmar senha -->
        <InputGroup
          label="Confirmar Senha"
          v-model="form.admin_password_confirmation"
          type="password"
          prependIcon="heroicons-outline:lock-closed"
          :required="!isEdit"
        />
        <span v-if="errors.admin_password_confirmation" class="text-sm text-red-500">{{ errors.admin_password_confirmation[0] }}</span>

        <!-- Botões -->
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

import InputGroup from '@/components/InputGroup'
import Card from '@/components/Card'
import FormActions from '@/components/Form/FormActions'

const route = useRoute()
const router = useRouter()
const toast = useToast()

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

// Opcional: função para resetar o form após criação
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
}

// Carrega padaria para edição
onMounted(async () => {
  if (isEdit.value) {
    try {
      const { data } = await api.get(`/api/v1/admin/bakeries/${route.params.id}`)
      form.value.name = data.name || ''
      form.value.slug = data.slug || ''
      form.value.nif = data.nif || ''
      form.value.phone = data.phone || ''
      form.value.admin_name = data.admin_name || ''
      form.value.admin_email = data.admin_email || ''
      form.value.admin_password = ''
      form.value.admin_password_confirmation = ''
    } catch (error) {
      console.error('Erro ao carregar padaria:', error)
    }
  }
})

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    // Se for edição e a senha estiver em branco, remove antes de enviar
    if (isEdit.value && !form.value.admin_password) {
      delete form.value.admin_password
      delete form.value.admin_password_confirmation
    }

    // Se a senha for informada, replica no campo de confirmação
    if (form.value.admin_password) {
      form.value.admin_password_confirmation = form.value.admin_password
    }

    let response
    if (isEdit.value) {
      response = await api.put(`/api/v1/admin/bakeries/${route.params.id}`, form.value)
      toast.success('Padaria atualizada com sucesso!')
    } else {
      response = await api.post('/api/v1/admin/bakeries', form.value)
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
