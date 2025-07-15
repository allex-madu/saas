import { useAuthStore } from '@/store/authStore'

export default async function auth({ next, router }) {
  const auth = useAuthStore()

  // Já está autenticado? (usuário está em memória)
  if (auth.user) {
    return next()
  }

  // Tenta buscar do backend (via fetchUser que você implementou)
  const ok = await auth.fetchUser()

  if (ok) {
    return next()
  }

  // Não autenticado: redireciona para login
  return router.push({ name: 'login' })
}
