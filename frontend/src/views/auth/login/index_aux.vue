<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form @submit.prevent="handleLogin" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Email</label>
        <input
          type="email"
          v-model="email"
          required
          class="w-full border px-4 py-2 rounded focus:outline-none focus:ring focus:border-blue-300"
        />
      </div>

      <div class="mb-6">
        <label class="block mb-1 font-semibold">Senha</label>
        <input
          type="password"
          v-model="password"
          required
          class="w-full border px-4 py-2 rounded focus:outline-none focus:ring focus:border-blue-300"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
      >
        Entrar
      </button>

      <p v-if="auth.error" class="text-red-600 mt-4 text-sm text-center">{{ auth.error }}</p>


    </form>
  </div>
</template>

<script setup>
  import { ref } from 'vue'
  import { useAuthStore } from '@/store/authStore'

  const email = ref('pedro@gmail.com')
  const password = ref('123456')

  const auth = useAuthStore()

  const handleLogin = async () => {
    const success = await auth.login(email.value, password.value)
    if (success) {
      console.log('Login bem-sucedido', auth.user)
      // Redirecionar, etc.
    }
  }
</script>
