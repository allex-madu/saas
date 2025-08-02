import { useAuthStore } from '@/store/authStore'

export default async function auth({ next, to }) {
  const auth = useAuthStore()
  const requiredRoles = to.meta.role || []

  // Garante que o usuário está autenticado
  if (!auth.user) {
    const ok = await auth.fetchUser()
    if (!ok) return next({ name: 'Login' })
  }

  const userRoles = (auth.user?.roles || []).map(role => role.name)

  // Ignora validações se estiver em modo debug
  if (auth.debugPermissions) return next()

  // Super-admin tem acesso total
  if (userRoles.includes('super-admin')) return next()

  // Bloqueia se não possuir nenhuma das roles exigidas
  if (requiredRoles.length && !requiredRoles.some(role => userRoles.includes(role))) {
    return next({ name: 'home' })
  }

  return next()
}
