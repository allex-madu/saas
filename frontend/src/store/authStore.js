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
  const debugPermissions = ref(false)

  // Helpers
  const hasRole = (rolesToCheck) => {
    try {
      if (debugPermissions.value) return true

      const userRoles = (user.value?.roles || []).map(role => role.name)

      if (typeof rolesToCheck === 'string') {
        return userRoles.includes(rolesToCheck)
      }

      if (Array.isArray(rolesToCheck)) {
        return rolesToCheck.some(role => userRoles.includes(role))
      }

      return false
    } catch (e) {
      console.warn('Erro em hasRole:', e)
      return false
    }
  }

  // Centraliza o pós-login
  const onLoginSuccess = async () => {
    try {
      await fetchUser()

      // Importação dinâmica para evitar referência circular
      const { useActiveBakeryStore } = await import('@/store/activeBakeryStore')
      const activeBakeryStore = useActiveBakeryStore()

      await activeBakeryStore.fetchMyBakeries()
      activeBakeryStore.loadActiveBakeryFromStorage()
    } catch (err) {
      console.error('Erro em onLoginSuccess:', err)
    }
  }

  // Actions
  const login = async (email, password) => {
    loading.value = true
    error.value = null
    try {
      await api.get('/sanctum/csrf-cookie')
      await api.post('/api/v1/login', { email, password })

      await onLoginSuccess()
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
      return true
    } catch (err) {
      if (err.response?.status === 401) {
        // Já tratado pelo interceptor, então silencia o erro aqui
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
    debugPermissions,

    // Getters
    isAuthenticated,
    isSuperAdmin,

    // Helpers
    hasRole,

    // Actions
    login,
    fetchUser,
    logout,
    onLoginSuccess,
  }
})
