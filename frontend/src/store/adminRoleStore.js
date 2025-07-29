import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

// Store de papéis (roles) e permissões administrativas
export const useAdminRoleStore = defineStore('adminRoleStore', () => {
  // States principais
  const roles = ref([])                    // Lista de papéis
  const selectedRole = ref(null)          // Papel selecionado (exibição/edição)
  const pagination = ref({ total: 0, current_page: 1 }) // Dados de paginação
  const perPage = ref(10)
  const currentPage = ref(1)

  // States de controle UI
  const loading = ref(false)
  const error = ref(null)

  // Permissões agrupadas (estrutura de árvore)
  const groupedPermissions = ref([])

  // Lista de papéis com busca e paginação
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

  // Criação de papel
  const createRole = async (payload) => {
    try {
      const response = await api.post('/api/v1/admin/role-management', payload)
      await fetchRoles(1, '', pagination.value.per_page || 10) // Recarrega lista após criação
      return response.data.role
    } catch (error) {
      throw error
    }
  }

  // Busca de papel único (exibição ou edição)
  const fetchRole = async (id) => {
    if (!id) {
      console.warn('ID do papel não fornecido.')
      return null
    }

    loading.value = true
    error.value = null

    try {
      const response = await api.get(`/api/v1/admin/role-management/${id}`)
      selectedRole.value = response.data.role
      return response.data.role
    } catch (err) {
      error.value = 'Erro ao carregar papel'
      console.error('Erro ao buscar papel:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  // Atualização de papel
  const updateRole = async (role, payload) => {
    try {
      const response = await api.put(`/api/v1/admin/role-management/${role}`, payload)

      if (pagination?.value?.per_page) {
        await fetchRoles(1, '', pagination.value.per_page)
      }

      return response.data.role
    } catch (error) {
      throw error
    }
  }

  // Exclusão de papel
  const deleteRole = async (id) => {
    try {
      await api.delete(`/api/v1/admin/role-management/${id}`)
      await fetchRoles(pagination.value.current_page)
    } catch (err) {
      console.error('Erro ao excluir papel:', err)
      throw err
    }
  }

  // Busca de permissões agrupadas (formato árvore)
  const fetchGroupedPermissions = async () => {
    try {
      const response = await api.get('/api/v1/admin/permissions/tree')
      groupedPermissions.value = response.data
    } catch (error) {
      console.error('Erro ao buscar permissões agrupadas:', error)
    }
  }

  return {
    // Papéis
    roles,
    selectedRole,
    pagination,
    perPage,
    currentPage,
    fetchRole,
    fetchRoles,
    createRole,
    deleteRole,
    updateRole,

    // Estado UI
    loading,
    error,

    // Permissões
    groupedPermissions,
    fetchGroupedPermissions,
  }
})
