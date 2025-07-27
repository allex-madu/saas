import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminUserStore = defineStore('adminUsers', () => {
  // Estado reativo principal
  const users = ref([])
  const roles = ref([])
  const pagination = ref({})
  const currentPage = ref(1)
  const perPage = ref(10)
  const loading = ref(false)
  const error = ref(null)
  const errors = ref({})

  let lastFetchId = 0 // Controle de concorrência para evitar sobrescrita de resposta antiga

  // Busca lista de usuários com paginação e busca
  async function fetchUsers(page = 1, search = '') {
    loading.value = true
    error.value = null
    currentPage.value = page
    const fetchId = ++lastFetchId

    try {
      const response = await api.get('/api/v1/admin/users', {
        params: {
          page,
          search,
          per_page: perPage.value,
        },
      })

      // Ignora se a resposta for ultrapassada por uma mais recente
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

  // Remove usuário da base e atualiza lista local
  async function deleteUser(id) {
    try {
      await api.delete(`/api/v1/admin/users/${id}`)
      users.value = users.value.filter(user => user.id !== id)
    } catch (err) {
      console.error('Erro ao deletar usuário:', err)
      throw err
    }
  }

  // Envia requisição de criação de usuário
  async function createUser(form) {
    loading.value = true
    error.value = null
    errors.value = {}

    try {
      await api.post('/api/v1/admin/users', form)
    } catch (err) {
      // Trata erros de validação (422)
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
      throw err
    } finally {
      loading.value = false
    }
  }

  // Busca lista de papéis e adapta para uso no <VueSelect>
  async function fetchRoles() {
    try {
      const response = await api.get('/api/v1/admin/roles')

      roles.value = (response.data.roles || []).map(role => ({
        ...role,
        label: role.name, // usado pelo VueSelect
        value: role.id,   // usado pelo VueSelect
      }))

      return roles.value
    } catch (error) {
      console.error('Erro ao buscar papéis:', error)
      roles.value = []
      throw error
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
    fetchUsers,
    createUser,
    deleteUser,
    fetchRoles,
  }
})
