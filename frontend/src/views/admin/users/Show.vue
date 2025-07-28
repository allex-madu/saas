<template>
  <EntityShowCard
    title="Detalhes do Usuário"
    :loading="loading"
    :error="error"
    :notFound="!user"
    notFoundMessage="Usuário não encontrado."
  >
    <!-- Avatar e dados básicos -->
    <div class="flex items-center gap-4 mb-6">
      <div class="w-14 h-14 rounded-full overflow-hidden">
        <img
          class="w-full h-full object-cover"
          :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user.person?.name || 'User')}&background=random`"
          alt="Avatar"
        />
      </div>
      <div>
        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-50">
          {{ user.person?.name || 'Sem Nome' }}
        </h3>
        <p class="text-sm text-slate-500 dark:text-slate-300">{{ user.email }}</p>
      </div>
    </div>

    <!-- Papéis -->
    <div class="mb-4">
      <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Papéis</p>
      <div class="flex flex-wrap gap-2 mt-1">
        <span
          v-if="user.roles?.length"
          v-for="role in user.roles"
          :key="role.id"
          class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded"
        >
          {{ role.name }}
        </span>
        <span v-else class="text-slate-400 text-sm">Nenhum papel atribuído.</span>
      </div>
    </div>

    <!-- Datas -->
    <div class="bg-slate-100 dark:bg-slate-700 rounded px-4 pt-4 pb-1 flex flex-wrap justify-between mt-6">
      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Dt. Criação
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ formatDate(user.created_at) }}
        </div>
      </div>

      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Atualizado em
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ formatDate(user.updated_at) }}
        </div>
      </div>
    </div>
  </EntityShowCard>
</template>

<script setup>
// Imports
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/axios'

// Componente reutilizável
import EntityShowCard from '@/components/Card/EntityShowCard'

// Estado
const route = useRoute()
const user = ref(null)
const loading = ref(false)
const error = ref(null)

// Busca usuário
async function fetchUser() {
  loading.value = true
  error.value = null

  try {
    const { data } = await api.get(`/api/v1/admin/users/${route.params.id}`)
    user.value = data.user || null
  } catch (err) {
    error.value = 'Erro ao carregar dados do usuário.'
  } finally {
    loading.value = false
  }
}

// Formata data
function formatDate(dateStr) {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

// Inicialização
onMounted(fetchUser)
</script>
