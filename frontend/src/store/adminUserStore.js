import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminUserStore = defineStore('adminUsers', () => {
  // Estado principal
  const users = ref([])                // Lista de usuários
  const roles = ref([])                // Lista de papéis formatados
  const cities = ref([])               // Lista de cidades (autocomplete)
  const pagination = ref({})           // Dados de paginação da tabela
  const currentPage = ref(1)           // Página atual
  const perPage = ref(10)              // Itens por página
  const loading = ref(false)           // Indicador de carregamento
  const error = ref(null)              // Mensagem de erro genérica
  const errors = ref({})               // Erros de validação (422)

  let lastFetchId = 0                  // Identificador de fetch para cancelar requisições antigas

  // 🔍 Lista usuários com paginação e busca
  async function fetchUsers(page = 1, search = '') {
    loading.value = true
    error.value = null
    currentPage.value = page
    const fetchId = ++lastFetchId

    try {
      const response = await api.get('/api/v1/admin/users', {
        params: { page, search, per_page: perPage.value },
      })

      // Garante que apenas a última requisição atualize os dados
      if (fetchId !== lastFetchId) return

      const res = response.data
      users.value = res.data

      // Atualiza a paginação
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

  // ✅ Criação de novo usuário
  async function createUser(form) {
    loading.value = true
    error.value = null
    errors.value = {}

    try {
      await api.post('/api/v1/admin/users', form)
    } catch (err) {
      // Captura erros de validação
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
      throw err
    } finally {
      loading.value = false
    }
  }

  // ✏️ Atualização de usuário existente
  async function updateUser(id, form) {
    loading.value = true
    error.value = null
    errors.value = {}

    try {
      await api.put(`/api/v1/admin/users/${id}`, form)
    } catch (err) {
      // Captura erros de validação
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
      throw err
    } finally {
      loading.value = false
    }
  }

  // 🗑️ Remove um usuário da base
  async function deleteUser(id) {
    try {
      await api.delete(`/api/v1/admin/users/${id}`)
      // Remove da lista local para não precisar refazer o fetch
      users.value = users.value.filter(user => user.id !== id)
    } catch (err) {
      console.error('Erro ao deletar usuário:', err)
      throw err
    }
  }

  // 📋 Busca papéis (roles) e adapta para VueSelect
  async function fetchRoles() {
    try {
      const response = await api.get('/api/v1/admin/roles')

      // Mapeia para { label, value } que o VueSelect espera
      roles.value = (response.data.roles || []).map(role => ({
        ...role,
        label: role.name,
        value: Number(role.id),
      }))

      return roles.value
    } catch (error) {
      console.error('Erro ao buscar papéis:', error)
      roles.value = []
      throw error
    }
  }

  // 🌍 Autocomplete de cidades
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

  // Exporta estado e ações
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
    updateUser,
    deleteUser,
    fetchRoles,
    searchCities,
  }
})
