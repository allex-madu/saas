// src/middleware/auth.js
import { useAuthStore } from '@/store/authStore'

export default async function auth({ next }) {
  const auth = useAuthStore()

  const ok = await auth.fetchUser()

  if (ok) {
    return next()
  }

  return next({ name: 'login' }) // redireciona para login
}
