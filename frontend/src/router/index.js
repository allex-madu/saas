import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/store/authStore'; // Importar o store corretamente
import routes from './route';

const router = createRouter({
  history: createWebHistory(import.meta.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    return { top: 0 };
  },
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Aguardar até que a store de autenticação seja completamente carregada
  await authStore.fetchUser(); // Certifique-se de que a store tenha os dados do usuário
  //console.log("Usuário autenticado:", authStore.isAuthenticated);

  // Verificar se a rota exige autenticação
  if (to.meta.requiresAuth) {
    // Se não estiver autenticado, redireciona para a página de login
    if (!authStore.isAuthenticated) {
      return next({ name: 'login' });
    }

    // Verifica se a rota exige papéis específicos
    const userRoles = authStore.user?.roles || [];
    const requiredRoles = to.meta.role || [];
    //console.log("Papéis exigidos:", requiredRoles);
    //console.log("Credencial do usuário:", userRoles);

    // Se o usuário não tiver o papel necessário, redireciona para a home ou outra página
    if (requiredRoles.length > 0 && !requiredRoles.some(role => userRoles.includes(role))) {
      //console.log("Usuário não tem o papel necessário, redirecionando para home...");
      return next({ name: 'home' });
    }
  }

  next();  // Continua com a navegação
});

export default router;
