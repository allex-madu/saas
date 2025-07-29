import { useAuthStore } from '@/store/authStore'

export default async function auth({ next, to }) {
  const auth = useAuthStore()
  const requiredRoles = to.meta.role || []

  if (!auth.user) {
    const ok = await auth.fetchUser()
    if (!ok) return next({ name: 'Login' })
  }

  const userRoles = (auth.user?.roles || []).map(role => role.name)

  // Se modo debug de permissões estiver ativado, ignora checagem de role
  if (auth.debugPermissions) {
    console.warn('[DEBUG] Ignorando verificação de roles para rota:', to.name)
    return next()
  }

  // Permite tudo para super-admin
  if (userRoles.includes('super-admin')) return next()

  // Bloqueia se não tiver pelo menos um dos roles exigidos
  if (requiredRoles.length && !requiredRoles.some(role => userRoles.includes(role))) {
    return next({ name: 'home' })
  }

  return next()
}
