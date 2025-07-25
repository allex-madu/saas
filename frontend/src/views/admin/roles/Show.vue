<template>
  <EntityShowCard
    title="Atribuições"
    :loading="loading"
    :error="error"
    :notFound="!selectedRole"
    notFoundMessage="Papel não encontrado."
  >
    <!-- Nome e descrição -->
    <div class="text-base font-medium text-slate-800 dark:text-slate-100 mb-3">
      <span class="text-sm text-slate-800 dark:text-slate-100">Nome:</span>
      {{ selectedRole.name }}
    </div>

    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
      <span class="text-slate-800 dark:text-slate-100">Descrição:</span>
      {{ selectedRole.description || 'Sem descrição' }}
    </p>

    <p class="text-sm text-slate-500 dark:text-slate-300 mb-4">
      <span class="text-slate-800 dark:text-slate-100">Guard:</span>
      {{ selectedRole.guard_name || '-' }}
    </p>

    <!-- Permissões agrupadas -->
    <div class="mt-6">
      <h6 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Permissões</h6>

      <div v-if="Object.keys(grouped).length > 0" class="space-y-4">
        <div v-for="(actions, module) in grouped" :key="module">
          <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 capitalize mb-1">
            {{ module }}
          </p>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="action in actions"
              :key="action"
              class="bg-primary-100 text-primary-600 dark:bg-slate-800 dark:text-slate-200 px-2 py-1 rounded text-xs"
            >
              {{ action }}
            </span>
          </div>
        </div>
      </div>

      <p v-else class="text-xs text-slate-500 dark:text-slate-300">
        Nenhuma permissão atribuída.
      </p>
    </div>

    <!-- Datas -->
    <div class="bg-slate-100 dark:bg-slate-700 rounded px-4 pt-4 pb-1 flex flex-wrap justify-between mt-6">
      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Dt. Criação
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ formatDate(selectedRole.created_at) }}
        </div>
      </div>

      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Atualizado em
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ formatDate(selectedRole.updated_at) }}
        </div>
      </div>
    </div>
  </EntityShowCard>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAdminRoleStore } from '@/store/adminRoleStore'
import { useGroupedPermissions } from '@/composables/useGroupedPermissions'
import EntityShowCard from '@/components/Card/EntityShowCard'

const route = useRoute()
const store = useAdminRoleStore()
const { selectedRole, loading, error } = storeToRefs(store)

onMounted(() => {
  store.fetchRole(route.params.id)
})

const { grouped } = useGroupedPermissions(
  computed(() => selectedRole.value?.permissions || [])
)

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
