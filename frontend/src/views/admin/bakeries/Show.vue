<template>
  <EntityShowCard
    title="Padaria"
    :loading="loading"
    :error="error"
    :notFound="!selectedBakery"
    notFoundMessage="Padaria não encontrada."
  >
    <!-- Nome da padaria -->
    <div class="text-base font-medium text-slate-800 dark:text-slate-100 mb-3">
      {{ selectedBakery.name }}
    </div>

    <!-- Slug -->
    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
      <span class="text-slate-800 dark:text-slate-100">Slug:</span>
      {{ selectedBakery.slug }}
    </p>

    <!-- NIF -->
    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
      <span class="text-slate-800 dark:text-slate-100">NIF:</span>
      {{ selectedBakery.nif || '—' }}
    </p>

    <!-- Telefone -->
    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
      <span class="text-slate-800 dark:text-slate-100">Telefone:</span>
      {{ selectedBakery.phone || '—' }}
    </p>

    <!-- Administrador -->
    <p class="text-sm text-slate-500 dark:text-slate-300 mb-2">
      <span class="text-slate-800 dark:text-slate-100">Administrador:</span>
      {{ selectedBakery.admin?.name || '—' }}
      <span v-if="selectedBakery.admin?.email">
        ({{ selectedBakery.admin.email }})
      </span>
    </p>

    <!-- Datas -->
    <div class="bg-slate-100 dark:bg-slate-700 rounded px-4 pt-4 pb-1 flex flex-wrap justify-between mt-6">
      <!-- Data de criação -->
      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Dt. Criação
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ formatDate(selectedBakery.created_at) }}
        </div>
      </div>

      <!-- Data de atualização -->
      <div class="mr-3 mb-3 space-y-2">
        <div class="text-xs font-medium text-slate-600 dark:text-slate-300">
          Atualizado em
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-300">
          {{ formatDate(selectedBakery.updated_at) }}
        </div>
      </div>
    </div>
  </EntityShowCard>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAdminBakeryStore } from '@/store/adminBakeryStore'
import EntityShowCard from '@/components/Card/EntityShowCard.vue'

// Roteamento e store
const route = useRoute()
const store = useAdminBakeryStore()
const { selectedBakery, loading, error } = storeToRefs(store)

// Carrega dados da padaria ao montar
onMounted(() => {
  store.fetchBakery(route.params.id)
})

// Formata data para exibição
function formatDate(dateStr) {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>
