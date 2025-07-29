import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminUserStore = defineStore('adminUsers', () => {
  const users = ref([])
  const roles = ref([])
  const pagination = ref({})
  const currentPage = ref(1)
  const perPage = ref(10)
  const loading = ref(false)
  const error = ref(null)
  const errors = ref({})
  const cities = ref([])

  let lastFetchId = 0

  // Lista usuários com paginação e busca
  async function fetchUsers(page = 1, search = '') {
    loading.value = true
    error.value = null
    currentPage.value = page
    const fetchId = ++lastFetchId

    try {
      const response = await api.get('/api/v1/admin/users', {
        params: { page, search, per_page: perPage.value },
      })

      if (fetchId !== lastFetchId) return

      const res = response.data
      users.value = res.data

      pagination.value = {
        current_page: res.meta?.current_page ?? 1,
        last_page: res.meta?.last_page ?? 1,
        total: res.meta?.total ?? 0,
        per_page: res.meta?.per_page ?? 10,
      }
    } catch (err) {
      if (fetchId !== lastFetchId) return
      error.value = 'Erro ao carregar usuários.'
      console.error(err)
    } finally {
      if (fetchId === lastFetchId) loading.value = false
    }
  }

  // Cria novo usuário
  async function createUser(form) {
    loading.value = true
    error.value = null
    errors.value = {}

    try {
      await api.post('/api/v1/admin/users', form)
    } catch (err) {
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
      throw err
    } finally {
      loading.value = false
    }
  }

  // Remove usuário da base e atualiza a lista
  async function deleteUser(id) {
    try {
      await api.delete(`/api/v1/admin/users/${id}`)
      users.value = users.value.filter(user => user.id !== id)
    } catch (err) {
      console.error('Erro ao deletar usuário:', err)
      throw err
    }
  }

  // Busca papéis e adapta para VueSelect
  async function fetchRoles() {
    try {
      const response = await api.get('/api/v1/admin/roles')

      roles.value = (response.data.roles || []).map(role => ({
        ...role,
        label: role.name,
        value: Number(role.id), // garante inteiro
      }))

      return roles.value
    } catch (error) {
      console.error('Erro ao buscar papéis:', error)
      roles.value = []
      throw error
    }
  }

  // Busca cidades via termo
  async function searchCities(term = '') {
    try {
      const response = await api.get('/api/v1/admin/cities', {
        params: { search: term }
      })
      cities.value = response.data.data || []
    } catch (err) {
      cities.value = []
      throw err
    }
  }

  return {
    users,
    roles,
    pagination,
    currentPage,
    loading,
    error,
    errors,
    perPage,
    cities,
    fetchUsers,
    createUser,
    deleteUser,
    fetchRoles,
    searchCities,
  }
})
