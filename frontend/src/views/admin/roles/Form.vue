<template>
  <div>
    <Card :title="isEdit ? 'Editar Papel' : 'Criar Novo Papel'">
      <!-- Formulário principal -->
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Campos básicos -->
        <InputGroup label="Nome" v-model="form.name" type="text" required placeholder="Ex: admin" />
        <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>

        <InputGroup label="Descrição" v-model="form.description" type="text" placeholder="Descrição do papel" />
        <span v-if="errors.description" class="text-sm text-red-500">{{ errors.description[0] }}</span>

        <!-- Árvore de permissões -->
        <div>
          <h6 class="font-semibold mb-2">Permissões</h6>
          <div v-if="groupedPermissions.length === 0">Carregando permissões...</div>
          <div v-else>
            <PermissionTree v-model="form.permissions" :nodes="groupedPermissions" />
          </div>
        </div>

        <!-- Ações -->
        <FormActions :isEdit="isEdit" :loading="loading" />
      </form>
    </Card>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useAdminRoleStore } from '@/store/adminRoleStore'

import PermissionTree from '@/components/Permissions/PermissionTree.vue'
import InputGroup from '@/components/InputGroup'
import Card from '@/components/Card'
import FormActions from '@/components/Form/FormActions'

// Roteamento e store
const route = useRoute()
const router = useRouter()
const toast = useToast()
const roleStore = useAdminRoleStore()

// Verifica se está em modo edição
const isEdit = computed(() => !!route.params?.id)

// Dados do formulário
const form = reactive({
  name: '',
  description: '',
  permissions: [],
})

const loading = ref(false)
const errors = ref({})
const groupedPermissions = ref([])

onMounted(async () => {
  // Carrega permissões agrupadas
  await roleStore.fetchGroupedPermissions()
  groupedPermissions.value = roleStore.groupedPermissions

  // Se for edição, busca dados do papel
  if (isEdit.value) {
    try {
      await roleStore.fetchRole(route.params.id)
      const role = roleStore.selectedRole
      form.name = role.name
      form.description = role.description
      form.permissions = role.permissions.map(p => p.name)
    } catch (err) {
      if (err.response?.status === 404) {
        toast.error('Papel não encontrado.')
        router.push({ name: 'admin.roles.index' })
      } else {
        toast.error('Erro ao carregar papel.')
        console.error(err)
      }
    }
  }
})

// Submissão do formulário
const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  // Validação simples
  if (!form.name.trim()) {
    toast.error('O nome do papel é obrigatório.')
    loading.value = false
    return
  }

  try {
    form.name = form.name.trim()

    let response
    if (isEdit.value) {
      response = await roleStore.updateRole(route.params.id, { ...form })
      toast.success(`Papel "${form.name}" atualizado com sucesso!`)
    } else {
      response = await roleStore.createRole({ ...form })
      toast.success(`Papel "${response.name}" criado com sucesso!`)
    }

    router.push({ name: 'admin.roles.index' })
  } catch (err) {
    toast.error(isEdit.value ? 'Erro ao atualizar papel.' : 'Erro ao criar papel.')

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
