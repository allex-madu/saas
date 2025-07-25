<template>
  <div>
    <Card title="Criar Novo Papel">
      <!-- Formulário principal -->
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Campos básicos -->
        <InputGroup label="Nome" v-model="form.name" type="text" required placeholder="Ex: admin" />
        <InputGroup label="Descrição" v-model="form.description" type="text" placeholder="Descrição do papel" />

        <!-- Árvore de permissões -->
        <div>
          <h6 class="font-semibold mb-2">Permissões</h6>
          <div v-if="groupedPermissions.length === 0">Carregando permissões...</div>
          <div v-else>
            <PermissionTree v-model="form.permissions" :nodes="groupedPermissions" />
          </div>
        </div>

        <!-- Botões de ação -->
        <div class="flex justify-end gap-2">
          <Button type="button" variant="outline" @click="$router.back()">Cancelar</Button>
          <Button type="submit" :loading="loading">Salvar</Button>
        </div>
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
import Button from '@/components/Button'
import Card from '@/components/Card'

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
  // Busca as permissões agrupadas
  await roleStore.fetchGroupedPermissions()
  groupedPermissions.value = roleStore.groupedPermissions

  // Se for edição, busca os dados do papel
  if (isEdit.value) {
    try {
      await roleStore.fetchRole(route.params.id)
      const role = roleStore.selectedRole
      form.name = role.name
      form.description = role.description
      form.permissions = role.permissions.map(p => p.name)
    } catch (err) {
      toast.error('Erro ao carregar papel.')
      console.error(err)
    }
  }
})

// Envia o formulário para criação ou atualização
const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

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
