<template>
  <div class="p-4">
    <h2 class="text-xl font-bold mb-4">Gestão de Papéis de Usuários</h2>

    <!-- Loading -->
    <div v-if="store.isLoading" class="text-gray-500">Carregando usuários e papéis...</div>

    <!-- Lista de usuários -->
    <div v-else v-for="user in store.users" :key="user.id" class="p-4 border mb-4 rounded-lg shadow">
      <div class="font-semibold">
        {{ user.email }}
        <span v-if="user.person">({{ user.person.name }})</span>
      </div>

      <div class="mt-2">
        <label
          v-for="role in store.roles"
          :key="role.name"
          class="inline-flex items-center mr-3"
        >
          <input
            type="checkbox"
            :value="role.name"
            v-model="selectedRoles[user.id]"
            class="mr-1"
          />
          {{ role.name }}
        </label>
      </div>

      <button
        @click="saveRoles(user.id)"
        class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
        :disabled="store.isLoading"
      >
        Salvar
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, watch } from 'vue'
import { useAdminUserStore } from '@/store/adminUserStore'

const store = useAdminUserStore()
const selectedRoles = reactive({})

// Sempre que os usuários forem carregados, preenche os papéis selecionados
watch(
  () => store.users,
  (users) => {
    if (Array.isArray(users)) {
      users.forEach((user) => {
        selectedRoles[user.id] = user.roles?.map((role) => role.name) ?? []
      })
    }
  },
  { immediate: true }
)

// Ao montar o componente, busca usuários e papéis
onMounted(async () => {
  try {
    await store.fetchUsers()
  } catch (error) {
    console.error('Erro ao carregar usuários e papéis:', error)
  }
})

// Salva os papéis para um usuário
const saveRoles = async (userId) => {
  try {
    await store.syncRoles(userId, selectedRoles[userId])
    alert('Papéis atualizados com sucesso!')
  } catch (error) {
    console.error('Erro ao salvar papéis:', error)
    alert('Erro ao atualizar papéis. Tente novamente.')
  }
}
</script>
