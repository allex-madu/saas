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

  let lastFetchId = 0 // contador incremental

  async function fetchUsers(page = 1, search = '') {
    loading.value = true
    error.value = null
    currentPage.value = page

    const fetchId = ++lastFetchId // incrementa o id da requisição

    try {
      const response = await api.get('/api/v1/admin/users', {
        params: {
          page,
          search,
          per_page: perPage.value,
        },
      })

      if (fetchId !== lastFetchId) {
        // Checa se essa resposta é a última; caso contrário, ignora
        return
      }

      const res = response.data

      users.value = res.users.data
      roles.value = res.roles

      pagination.value = {
      current_page: res.users.current_page,
      last_page: res.users.last_page,
      total: res.users.total,
      }
    } catch (err) {
      if (fetchId !== lastFetchId) return
      error.value = 'Erro ao carregar usuários.'
    } finally {
      if (fetchId === lastFetchId) loading.value = false
    }
  }

  async function deleteUser(id) {
    try {
      await api.delete(`/api/v1/admin/users/${id}`) 
      users.value = users.value.filter(user => user.id !== id)
      //toast.success('Usuário deletado com sucesso!')
    } catch (error) {
      console.error('Erro ao deletar:', error)
      //toast.error('Erro ao deletar usuário.')
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
