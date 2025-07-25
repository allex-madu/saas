import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useRolesStore = defineStore('rolesStore', () => {
  const roles = ref([])
  const selectedRole = ref(null)
  const permissionsTree = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchRoles = async () => {
    loading.value = true
    try {
      const response = await api.get('/api/v1/admin/role-management')
      roles.value = response.data.data
    } catch (err) {
      error.value = 'Erro ao carregar papéis.'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  const fetchPermissionsTree = async () => {
    try {
      const response = await api.get('/api/v1/admin/permissions/tree')
      permissionsTree.value = response.data // formato agrupado em árvore
    } catch (err) {
      console.error('Erro ao carregar permissões em árvore:', err)
    }
  }

  const createRole = async (data) => {
    await api.post('/api/v1/admin/role-management', data)
    await fetchRoles()
  }

  const updateRole = async (id, data) => {
    await api.put(`/api/v1/admin/role-management/${id}`, data)
    await fetchRoles()
  }

  const deleteRole = async (id) => {
    await api.delete(`/api/v1/admin/role-management/${id}`)
    await fetchRoles()
  }

  return {
    roles,
    selectedRole,
    permissionsTree,
    loading,
    error,
    fetchRoles,
    fetchPermissionsTree,
    createRole,
    updateRole,
    deleteRole
  }
})
