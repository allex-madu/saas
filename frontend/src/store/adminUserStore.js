
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const useAdminUserStore = defineStore('adminUsers', () => {
  const users = ref([])
  const roles = ref([])
  const isLoading = ref(false) // útil para loading spinners ou desabilitar botões

  // Buscar usuários e papéis
  async function fetchUsers() {
    isLoading.value = true
    try {
      const res = await api.get('/api/v1/admin/users')
      users.value = res.data.users ?? []
      roles.value = res.data.roles ?? []
    } catch (error) {
      console.error('Erro ao buscar usuários e papéis:', error)
    } finally {
      isLoading.value = false
    }
  }

  // Sincronizar papéis de um usuário específico
  async function syncRoles(userId, selectedRoles) {
    isLoading.value = true
    try {
      await api.post(`/api/v1/admin/users/${userId}/roles`, { roles: selectedRoles })
      await fetchUsers()
    } catch (error) {
      console.error(`Erro ao sincronizar papéis do usuário ${userId}:`, error)
    } finally {
      isLoading.value = false
    }
  }

  return {
    users,
    roles,
    isLoading,
    fetchUsers,
    syncRoles,
  }
})
