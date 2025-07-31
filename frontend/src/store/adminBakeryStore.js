
import { defineStore } from 'pinia'
import api from '@/plugins/axios'
import { ref } from 'vue'

export const useAdminBakeryStore = defineStore('adminBakeryStore', () => {
  // Lista de padarias
  const bakeries = ref([])

  // Padaria selecionada (para edição/visualização)
  const selectedBakery = ref(null)

  // Estado de carregamento e erro
  const loading = ref(false)
  const error = ref(null)

  // Paginação
  const pagination = ref({ total: 0, current_page: 1 })
  const perPage = ref(10)
  const currentPage = ref(1)

  // Buscar padarias (com busca e paginação)
  const fetchBakeries = async (page = 1, search = '', per_page = 10) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/api/v1/admin/bakeries', {
        params: { page, per_page, search }
      })

      bakeries.value = response.data.data
      pagination.value = {
        total: response.data.meta?.total ?? 0,
        current_page: response.data.meta?.current_page ?? 1,
      }
      perPage.value = response.data.meta?.per_page ?? 10
    } catch (err) {
      error.value = 'Erro ao carregar padarias'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  // Buscar padaria por ID (para show/edit)
  const fetchBakery = async (id) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/api/v1/admin/bakeries/${id}`)
      selectedBakery.value = response.data
    } catch (err) {
      selectedBakery.value = null
      error.value = 'Erro ao carregar padaria'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  // Criar padaria
  const createBakery = async (payload) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/api/v1/admin/bakeries', payload)
      return response.data
    } catch (err) {
      error.value = 'Erro ao criar padaria'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Atualizar padaria
  const updateBakery = async (id, payload) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/api/v1/admin/bakeries/${id}`, payload)
      return response.data
    } catch (err) {
      error.value = 'Erro ao atualizar padaria'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Deletar padaria
  const deleteBakery = async (id, page = currentPage.value) => {
    try {
      await api.delete(`/api/v1/admin/bakeries/${id}`)
      await fetchBakeries(page) // Atualiza a lista
    } catch (err) {
      console.error('Erro ao excluir padaria:', err)
      throw err
    }
  }

  return {
    bakeries,
    selectedBakery,
    loading,
    error,
    pagination,
    perPage,
    currentPage,
    fetchBakeries,
    fetchBakery,
    createBakery,
    updateBakery,
    deleteBakery,
  }
})
