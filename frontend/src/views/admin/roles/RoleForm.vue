<template>
  <Card title="Criar Novo Papel">
    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Nome e Descrição -->
      <InputGroup label="Nome" v-model="form.name" type="text" required placeholder="Ex: admin" />
      <InputGroup label="Descrição" v-model="form.description" type="text" placeholder="Descrição do papel" />


      <!-- Permissões -->
      <div>
        <h6 class="font-semibold mb-2">Permissões</h6>
        <div v-if="groupedPermissions.length === 0">Carregando permissões...</div>
        <div v-else>
          <PermissionTree v-model="form.permissions" :nodes="groupedPermissions" />
        </div>
      </div>






      <!-- Botões -->
      <div class="flex justify-end gap-2">
        <Button type="button" variant="outline" @click="$router.back()">Cancelar</Button>
        <Button type="submit" :loading="loading">Salvar</Button>
      </div>
    </form>
  </Card>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useAdminRoleStore } from '@/store/adminRoleStore'
import PermissionTree from '@/components/Permissions/PermissionTree.vue'


import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'

const router = useRouter()
const toast = useToast()
const roleStore = useAdminRoleStore()

const form = ref({
  name: '',
  description: '',
  permissions: []
})

const loading = ref(false)
const errors = ref({})
const groupedPermissions = ref([]) 


const toggleGroup = (actions, module) => {
  const allSelected = actions.every(p => form.value.permissions.includes(p.name))
  if (allSelected) {
    form.value.permissions = form.value.permissions.filter(p => !actions.some(a => a.name === p))
  } else {
    const toAdd = actions.map(p => p.name)
    form.value.permissions = Array.from(new Set([...form.value.permissions, ...toAdd]))
  }
}


onMounted(async () => {
  await roleStore.fetchGroupedPermissions()
  groupedPermissions.value = roleStore.groupedPermissions
})


const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    const response = await roleStore.createRole(form.value)
    toast.success(`Papel "${response.name}" criado com sucesso!`)
    router.push({ name: 'admin.roles.index' })
  } catch (err) {
    toast.error('Erro ao criar papel.')
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
