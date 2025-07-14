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

  async function fetchUsers(page = 1, search = '') {
    loading.value = true
    error.value = null
    currentPage.value = page

    try {
      const response = await api.get('/api/v1/admin/users', {
        params: {
          page,
          search,
          per_page: perPage.value,
        },
      })

      const res = response.data

      users.value = res.users.data
      roles.value = res.roles

      pagination.value = {
        current_page: res.users.current_page,
        last_page: res.users.last_page,
        total: res.users.total,
      }
    } catch (err) {
      error.value = 'Erro ao carregar usuários.'
    } finally {
      loading.value = false
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
  }
})
