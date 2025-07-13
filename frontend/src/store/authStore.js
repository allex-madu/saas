import { defineStore } from 'pinia'      
import { ref, computed } from 'vue'     
import api from '@/plugins/axios'       

export const useAuthStore = defineStore('auth', () => 
{
  const user = ref(null)
  const error = ref(null)
  const loading = ref(false)

  // Getters computados
  const isAuthenticated = computed(() => !!user.value)
  const isSuperAdmin = computed(() => user.value?.is_super_admin === true)
  const roles = computed(() => user.value?.roles || [])

  // Helpers
  const hasRole = (role) => roles.value.includes(role)


  const login = async (email, password) => 
  {
    loading.value = true
    error.value = null
    try {
      await api.get('/sanctum/csrf-cookie')
      const response = await api.post('/api/v1/login', { email, password })
      user.value = response.data.user
      localStorage.setItem('activeUser', JSON.stringify(user.value))

      await fetchUser() // busca usuário completo com relação person

      return true
    } catch (err) {
      error.value = 'Falha ao fazer login'
      console.error('Erro no login:', err)
      return false
    } finally {
      loading.value = false
    }
  }

  const fetchUser = async () => 
  {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/api/v1/user')
      user.value = response.data 
      //console.log('Resultado fetchUser:', response.data)
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

  // Retorna os estados, getters e ações para uso em componentes
  return {
    // state
    user,
    error,
    
    // getters
    isAuthenticated,
    isSuperAdmin,
    roles,
    
    // helpers
    hasRole,

    // actions
    login,
    fetchUser,
    logout
  }
})
