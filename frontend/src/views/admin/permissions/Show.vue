<template>
  <EntityShowCard
    title="Permissões"
    :loading="loading"
    :error="error"
    :notFound="!selectedPermission"
    notFoundMessage="Permissão não encontrada."
  >
    <!-- Conteúdo principal da permissão -->
    <div class="text-base font-medium text-slate-800 dark:text-slate-100 mb-3">
      {{ selectedPermission.name }}
    </div>

    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
      <span class="text-slate-800 dark:text-slate-100">Descrição:</span>
      {{ selectedPermission.description || 'Sem descrição' }}
    </p>

    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
      <span class="text-slate-800 dark:text-slate-100">Guard:</span>
      {{ selectedPermission.guard_name }}
    </p>

    <!-- Datas -->
    <div class="bg-slate-100 dark:bg-slate-700 rounded px-4 pt-4 pb-1 flex flex-wrap justify-between mt-6">
      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Dt. Criação
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ selectedPermission?.created_at ? formatDate(selectedPermission.created_at) : '-' }}
        </div>
      </div>

      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Atualizado em
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ selectedPermission?.updated_at ? formatDate(selectedPermission.updated_at) : '-' }}
        </div>
      </div>
    </div>
  </EntityShowCard>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { usePermissionStore } from '@/store/permissionsStore'

// Novo componente reutilizável
import EntityShowCard from '@/components/Card/EntityShowCard.vue'

const route = useRoute()
const store = usePermissionStore()
const { selectedPermission, loading, error } = storeToRefs(store)

onMounted(() => {
  store.fetchPermission(route.params.id)
})

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
</script>
