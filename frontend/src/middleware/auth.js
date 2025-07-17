import { useAuthStore } from '@/store/authStore'

export default async function auth({ next, router, to }) {
  const auth = useAuthStore()

  // Se o usuário já está autenticado em memória, permite seguir
  if (auth.user) {
    // Importante: se houver roles exigidas na rota, verificar aqui também
    const requiredRoles = to.meta.role || []      // Papéis exigidos na rota
    const userRoles = auth.user?.roles || []      // Papéis do usuário

    // Super-admin tem acesso irrestrito, passa direto
    if (userRoles.includes('super-admin')) {
      return next()
    }

    // Se a rota exige roles específicas, valida se usuário tem pelo menos um dos papéis
    if (requiredRoles.length > 0 && !requiredRoles.some(role => userRoles.includes(role))) {
      // Usuário não tem permissão para essa rota
      return router.push({ name: 'home' })
    }

    // Permite continuar para rotas que não exigem role ou com role permitida
    return next()
  }

  // Caso usuário não esteja em memória, tenta buscar do backend (fetchUser)
  const ok = await auth.fetchUser()

  if (ok) {
    // Após buscar, obtém os papéis atualizados
    const requiredRoles = to.meta.role || []
    const userRoles = auth.user?.roles || []

    // Super-admin tem acesso irrestrito
    if (userRoles.includes('super-admin')) {
      return next()
    }

    // Valida se tem os papéis necessários para a rota
    if (requiredRoles.length > 0 && !requiredRoles.some(role => userRoles.includes(role))) {
      return router.push({ name: 'home' })
    }

    return next()
  }

  // Se não autenticado, redireciona para login
  return router.push({ name: 'login' })
}
