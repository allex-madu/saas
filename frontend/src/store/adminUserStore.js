import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminUserStore = defineStore('adminUsers', () => {
  const users = ref([])
  const pagination = ref({})
  const roles = ref([])
  const currentPage = ref(1)
  const loading = ref(false)
  const error = ref(null)
  const perPage = ref(10)

  let lastFetchId = 0

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

      if (fetchId !== lastFetchId) return

      const res = response.data

      users.value = res.data
      roles.value = res.extra?.roles ?? []

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

  async function deleteUser(id) {
    try {
      await api.delete(`/api/v1/admin/users/${id}`)
      users.value = users.value.filter(user => user.id !== id)
    } catch (error) {
      console.error('Erro ao deletar:', error)
    }
  }

  return {
    users,
    roles,
    pagination,
    currentPage,
    loading,
    error,
    perPage,
    fetchUsers,
    deleteUser,
  }
})
