import { useAuthStore } from '@/store/authStore'

export default async function auth({ next, to }) {
  const auth = useAuthStore()
  const requiredRoles = to.meta.role || []

  if (!auth.user) {
    const ok = await auth.fetchUser()
    if (!ok) return next({ name: 'Login' })
  }

  // Agora os roles são objetos { id, name }
  const userRoles = (auth.user?.roles || []).map(role => role.name)

  // verifica como estão sendo retornados usuarios logados
  //console.log('Usuário logado:', auth.user)
  //console.log('Roles detectadas:', userRoles)

  if (userRoles.includes('super-admin')) return next()

  if (requiredRoles.length && !requiredRoles.some(role => userRoles.includes(role))) {
    return next({ name: 'home' }) // Redireciona se não tiver permissão
  }

  return next()
}
