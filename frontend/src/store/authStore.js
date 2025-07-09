import { defineStore } from 'pinia';
import { ref } from 'vue';
// import axios from '@/axios'; 
import router from '@/router';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);

  const authStore = useAuthStore();
console.log(authStore.user);

  // const login = async (email, password) => {
  //   try {
  //     // 1. Obter o CSRF cookie
  //     await axios.get('/sanctum/csrf-cookie');

  //     // 2. Enviar credenciais para login
  //     await axios.post('/api/login', { email, password });

  //     // 3. Buscar o usuário autenticado
  //     await fetchUser();
      
  //   } catch (error) {
  //     console.error('Erro ao fazer login', error);
      
  //     throw error;
  //   }
  // };

  // const fetchUser = async () => {
  //   try {
  //     const response = await axios.get('/api/user');
  //     user.value = response.data;
  //   } catch (error) {
  //     user.value = null;
  //     console.error('Erro ao buscar usuário', error);
  //   }
  // };

  // const logout = async () => {
  //   try {
  //     await axios.post('/api/logout');
  //     user.value = null;
  //   } catch (error) {
  //     console.error('Erro ao fazer logout', error);
  //   }
  // };

  //return { user, login, fetchUser, logout };
}, {
  persist: true, // <- isso persiste no localStorage
});
