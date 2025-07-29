<template>
  <div class="xl:col-span-5 col-span-12 lg:col-span-7 relative">
    <Card>
      <!-- Slot de cabeçalho com fallback -->
      <template #header>
        <div class="flex justify-between items-center w-full">
          <h4 class="text-lg font-semibold text-slate-800 dark:text-white">
            {{ title }}
          </h4>
          <Button
            text="Voltar"
            icon="heroicons-outline:arrow-left"
            @click="$router.back()"
            btnClass="bg-primary-500 hover:bg-primary-600 text-white h-[40px] px-4"
            title="Voltar à tela anterior"
          />
        </div>
      </template>

      <!-- Conteúdo: loading, erro, não encontrado ou conteúdo normal -->
      <div v-if="loading" class="text-gray-500 dark:text-slate-300 py-4">Carregando...</div>

      <div v-else-if="error" class="text-red-500 py-4">{{ error }}</div>

      <div v-else-if="notFound">
        <div class="text-gray-500 dark:text-slate-300 py-4">
          {{ notFoundMessage }}
        </div>
      </div>

      <div v-else>
        <slot />
      </div>
    </Card>
  </div>
</template>

<script setup>
import Button from '@/components/Button'
import Card from '@/components/Card'
defineProps({
  title: String,
  loading: Boolean,
  error: String,
  notFound: Boolean,
  notFoundMessage: {
    type: String,
    default: 'Registro não encontrado.',
  },
})
</script>
