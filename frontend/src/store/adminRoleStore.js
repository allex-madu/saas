import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminRoleStore = defineStore('adminRoleStore', () => {
  // State - Roles
  const roles = ref([])
  const selectedRole = ref(null)
  const pagination = ref({ total: 0, current_page: 1 })
  const perPage = ref(10)
  const currentPage = ref(1)

  // State - UI/Errors
  const loading = ref(false)
  const error = ref(null)

  // State - Permissions
  const groupedPermissions = ref([]) // ← Agora um array



  const fetchRoles = async (page = 1, search = '', per_page = 10) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.get('/api/v1/admin/role-management', {
        params: { page, per_page, search }
      })

      roles.value = response.data.data
      pagination.value = {
        total: response.data.meta.total ?? 0,
        current_page: response.data.meta.current_page ?? 1,
      }
      perPage.value = response.data.meta.per_page ?? 10

    } catch (err) {
      error.value = 'Erro ao carregar papéis'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  const createRole = async (payload) => {
    try {
      const response = await api.post('/api/v1/admin/role-management', payload)
      await fetchRoles(1, '', pagination.value.per_page || 10)
      return response.data.role
    } catch (error) {
      throw error
    }
  }

  const deleteRole = async (id) => {
    try {
      await api.delete(`/api/v1/admin/roles/${id}`)
      await fetchRoles(pagination.value.current_page)
    } catch (err) {
      console.error('Erro ao excluir papel:', err)
      throw err
    }
  }


  const fetchGroupedPermissions = async () => {
    try {
      const response = await api.get('/api/v1/admin/permissions/tree') 
      groupedPermissions.value = response.data 
      console.log('retorno api => ', response.data)
    } catch (error) {
      console.error('Erro ao buscar permissões agrupadas:', error)
    }
  }


  return {
    // roles
    roles,
    selectedRole,
    pagination,
    perPage,
    currentPage,
    fetchRoles,
    createRole,
    deleteRole,

    // ui
    loading,
    error,

    // permissions
    groupedPermissions,
    fetchGroupedPermissions,
  }
})
