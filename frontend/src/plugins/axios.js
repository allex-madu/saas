// plugins/axios.js
import axios from 'axios'

// Cria a instância
const api = axios.create({
  baseURL: 'http://localhost:8080',
  timeout: 5000,
  withCredentials: true,
})

// Interceptor para CSRF
api.interceptors.request.use(config => {
  const token = getCookie('XSRF-TOKEN')
  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
  }
  return config
})

// Interceptor global de resposta para capturar 401
api.interceptors.response.use(
  response => response,
  async error => {
    const originalRequest = error.config

    // Evita loop se já tentou ou se a URL for /logout
    if (
      error.response?.status === 401 &&
      !originalRequest._retry &&
      !originalRequest.url.includes('/logout')
    ) {
      originalRequest._retry = true

      console.warn('Interceptador Axios: sessão expirada (401)')

      // Limpa o estado local (sem chamar logout)
      const { useAuthStore } = await import('@/store/authStore')
      const authStore = useAuthStore()
      authStore.user = null

      // Redireciona para login
      const router = (await import('@/router')).default
      router.push({ name: 'login' })
    }

    return Promise.reject(error)
  }
)

// Função auxiliar para obter cookie CSRF
function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'))
  return match ? match[3] : null
}

export default api
