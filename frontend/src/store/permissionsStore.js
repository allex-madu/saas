// src/store/permissionsStore.js
import { defineStore } from 'pinia'
import api from '@/plugins/axios'
import { ref } from 'vue'

// Store de permissões
export const usePermissionStore = defineStore('permissionStore', () => {
  // Estado principal
  const permissions = ref([])
  const selectedPermission = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Paginação
  const pagination = ref({ total: 0, current_page: 1 })
  const perPage = ref(10)
  const currentPage = ref(1)

  // Buscar lista de permissões com paginação e busca
  const fetchPermissions = async (page = 1, search = '', per_page = 10) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/api/v1/admin/permissions', {
        params: { page, per_page, search }
      })

      permissions.value = response.data.data
      pagination.value = {
        total: response.data.meta.total ?? 0,
        current_page: response.data.meta.current_page ?? 1,
      }
      perPage.value = response.data.meta.per_page ?? 10
    } catch (err) {
      error.value = 'Erro ao carregar permissões'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  // Buscar uma permissão específica por ID
  const fetchPermission = async (id) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/api/v1/admin/permissions/${id}`)
      selectedPermission.value = response.data.permission
    } catch (err) {
      selectedPermission.value = null
      error.value = 'Erro ao carregar a permissão'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  // Excluir permissão por ID
  const deletePermission = async (id) => {
    try {
      await api.delete(`/api/v1/admin/permissions/${id}`)
      fetchPermissions(pagination.value.current_page)
    } catch (err) {
      console.error('Erro ao excluir permissão:', err)
      throw err // Permite tratamento externo (ex: toast na view)
    }
  }

  // Exporta os estados e ações
  return {
    permissions,
    selectedPermission,
    loading,
    error,
    pagination,
    perPage,
    currentPage,
    fetchPermissions,
    fetchPermission,
    deletePermission,
  }
})
