<template>
  <div>
    <!-- Botão de Voltar -->
    <div class="mb-5 -mt-3">
      <Button
        text="Voltar"
        icon="heroicons-outline:arrow-left"
        @click="$router.back()"
        btnClass="bg-primary-500 hover:bg-primary-600 text-white"
      />
    </div>

    <!-- Card de Detalhes -->
    <Card title="Detalhes da Permissão">
      <div class="w-full p-8 bg-[#F5F7FC] dark:bg-slate-700 rounded-lg">
        <div v-if="loading" class="text-gray-500 dark:text-slate-300 py-4">
          Carregando...
        </div>

        <div v-else-if="error" class="text-red-500 py-4">
          {{ error }}
        </div>

        <div v-else-if="selectedPermission" class="space-y-5 text-slate-700 dark:text-slate-300">
          <!-- Nome -->
          <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-50">
            {{ selectedPermission.name }}
          </h3>

          <!-- Descrição -->
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Descrição</p>
            <p class="text-base text-slate-900 dark:text-white">
              {{ selectedPermission.description || 'Sem descrição' }}
            </p>
          </div>

          <!-- Guard -->
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Guard</p>
            <p class="text-base text-slate-900 dark:text-white">
              {{ selectedPermission.guard_name }}
            </p>
          </div>

          <!-- Datas -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Criado em</p>
              <p class="text-base text-slate-900 dark:text-white">
                {{ formatDate(selectedPermission.created_at) }}
              </p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Atualizado em</p>
              <p class="text-base text-slate-900 dark:text-white">
                {{ formatDate(selectedPermission.updated_at) }}
              </p>
            </div>
          </div>
        </div>

        <div v-else class="text-gray-500 dark:text-slate-300 py-4">
          Permissão não encontrada.
        </div>
      </div>
    </Card>
  </div>
</template>


<script setup>
  import { onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import { storeToRefs } from 'pinia'
  import { usePermissionStore } from '@/store/permissionsStore'
  import Card from '@/components/Card'
  import Button from '@/components/Button'

  const route = useRoute()
  const store = usePermissionStore()

  // Ref de estados vindos da store
  const { selectedPermission, loading, error } = storeToRefs(store)

  onMounted(() => {
    store.fetchPermission(route.params.id)
  })

  function formatDate(dateStr) {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleString('pt-BR')
  }
</script>
