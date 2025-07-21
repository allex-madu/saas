<template>
  <div>
    <h2 class="text-xl font-bold mb-4">{{ isEdit ? 'Editar Papel' : 'Novo Papel' }}</h2>

    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label class="block font-medium">Nome</label>
        <input
          v-model="form.name"
          type="text"
          class="form-input w-full"
          required
        />
      </div>

      <div>
        <label class="block font-medium mb-2">Permissões</label>
        <div v-if="groupedPermissions">
          <div v-for="(perms, group) in groupedPermissions" :key="group" class="mb-4 border rounded p-3">
            <h4 class="font-semibold mb-2 capitalize">{{ group }}</h4>
            <div class="flex flex-wrap gap-4">
              <label
                v-for="perm in perms"
                :key="perm.name"
                class="inline-flex items-center gap-2"
              >
                <input
                  type="checkbox"
                  :value="perm.name"
                  v-model="form.permissions"
                />
                {{ perm.description ?? perm.name }}
              </label>
            </div>
          </div>
        </div>
      </div>

      <div>
        <button type="submit" class="btn btn-primary">
          {{ isEdit ? 'Atualizar' : 'Criar' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/plugins/axios'
import { toast } from 'vue3-toastify'

const route = useRoute()
const router = useRouter()

const isEdit = computed(() => !!route.params.id)
const form = ref({
  name: '',
  permissions: [],
})
const groupedPermissions = ref(null)

const fetchGroupedPermissions = async () => {
  try {
    const { data } = await api.get('/api/v1/admin/permissions/grouped')
    groupedPermissions.value = data
  } catch (error) {
    toast.error('Erro ao carregar permissões')
    console.error(error)
  }
}

const fetchRole = async (id) => {
  try {
    const { data } = await api.get(`/api/v1/admin/roles/${id}`)
    form.value.name = data.role.name
    form.value.permissions = data.role.permissions.map(p => p.name)
  } catch (error) {
    toast.error('Erro ao carregar papel')
    console.error(error)
  }
}

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await api.put(`/api/v1/admin/roles/${route.params.id}`, form.value)
      toast.success('Papel atualizado com sucesso!')
    } else {
      await api.post('/api/v1/admin/roles', form.value)
      toast.success('Papel criado com sucesso!')
    }
    router.push({ name: 'roles.index' })
  } catch (error) {
    toast.error('Erro ao salvar papel')
    console.error(error)
  }
}

onMounted(async () => {
  await fetchGroupedPermissions()
  if (isEdit.value) {
    await fetchRole(route.params.id)
  }
})
</script>
