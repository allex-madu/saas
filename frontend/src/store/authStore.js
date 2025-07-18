import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/plugins/axios'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const error = ref(null)
  const loading = ref(false)

  // Getters
  const isAuthenticated = computed(() => !!user.value)
  const isSuperAdmin = computed(() => user.value?.is_super_admin === true)

  // Helpers

  /*
    hasRole  
    exemplo de uso | ideal para menus, botões, componentes ou controle de rotas visíveis.
    
    <template>
      <Button v-if="auth.hasRole('admin')">Botão só para admin</Button>
      <Button v-if="auth.hasRole(['admin', 'super-admin'])">Botão para ambos</Button>
    </template>
  */
  const hasRole = (rolesToCheck) => {
  if (!user.value?.roles) return false

  const userRoles = user.value.roles // já são strings como ['admin', 'super-admin']

  if (typeof rolesToCheck === 'string') {
    return userRoles.includes(rolesToCheck)
  }

  if (Array.isArray(rolesToCheck)) {
    return rolesToCheck.some(role => userRoles.includes(role))
  }

  return false
}

  // Actions
  const login = async (email, password) => {
    loading.value = true
    error.value = null
    try {
      await api.get('/sanctum/csrf-cookie')
      const response = await api.post('/api/v1/login', { email, password })
      user.value = response.data.user
      localStorage.setItem('activeUser', JSON.stringify(user.value))

      await fetchUser() // Busca o usuário completo (com relações)
      return true
    } catch (err) {
      error.value = 'Falha ao fazer login'
      console.error('Erro no login:', err)
      return false
    } finally {
      loading.value = false
    }
  }

  const fetchUser = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/api/v1/user')
      user.value = response.data
      localStorage.setItem('activeUser', JSON.stringify(user.value))
      return true
    } catch (err) {
      if (err.response?.status === 401) {
        user.value = null
        localStorage.removeItem('activeUser')
        console.warn('Sessão expirou')
        return false
      }
      error.value = 'Erro ao buscar usuário'
      console.error(err)
      return false
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    loading.value = true
    error.value = null
    try {
      await api.get('/sanctum/csrf-cookie')
      await api.post('/api/v1/logout')
      user.value = null
      localStorage.removeItem('activeUser')
    } catch (err) {
      error.value = 'Erro ao fazer logout'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    user,
    error,
    loading,

    // Getters
    isAuthenticated,
    isSuperAdmin,

    // Helpers
    hasRole,

    // Actions
    login,
    fetchUser,
    logout
  }
})
