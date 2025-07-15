<template>
  <div>
    <Card title="Detalhes do Usuário">
      <div class="w-full p-8 bg-[#F5F7FC] dark:bg-slate-700 rounded-lg">
        <div v-if="loading" class="text-gray-500 dark:text-slate-300 py-4">
          Carregando...
        </div>

        <div v-else-if="error" class="text-red-500 py-4">
          {{ error }}
        </div>

        <div v-else-if="user" class="space-y-5 text-slate-700 dark:text-slate-300">
          <!-- Header com nome e data fictícia -->
          <div class="flex items-start justify-between">
            <div class="w-12 h-12">
              <img
                class="rounded-full w-full h-full object-cover"
                :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user.person?.name || 'User')}&background=random`"
                alt="user avatar"
              />
            </div>
          </div>

          <!-- Nome -->
          <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-50">
            {{ user.person?.name || 'Sem Nome' }}
          </h3>

          <!-- Email -->
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Email</p>
            <p class="text-base text-slate-900 dark:text-white">{{ user.email }}</p>
          </div>

          <!-- Papéis -->
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-300 font-medium">Tipo</p>
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
        </div>

        <div v-else class="text-gray-500 dark:text-slate-300 py-4">
          Usuário não encontrado.
        </div>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/axios'
import Card from '@/components/Card'

const route = useRoute()
const user = ref(null)
const loading = ref(false)
const error = ref(null)

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

onMounted(fetchUser)
</script>
