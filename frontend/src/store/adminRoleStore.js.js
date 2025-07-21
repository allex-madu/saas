// src/stores/adminRoleStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminRoleStore = defineStore('adminRoleStore', () => {
  const roles = ref([])
  const selectedRole = ref(null)
  const loading = ref(false)
  const error = ref(null)

  const pagination = ref({ total: 0, current_page: 1 })
  const perPage = ref(10)
  const currentPage = ref(1)

  const fetchRoles = async (page = 1, search = '', per_page = 10) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/api/v1/admin/roles', {
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

  const fetchRole = async (id) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/api/v1/admin/roles/${id}`)
      selectedRole.value = response.data.role
    } catch (err) {
      selectedRole.value = null
      error.value = 'Erro ao carregar papel'
      console.error(err)
    } finally {
      loading.value = false
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

  return {
    roles,
    selectedRole,
    loading,
    error,
    pagination,
    perPage,
    currentPage,
    fetchRoles,
    fetchRole,
    deleteRole,
  }
})
