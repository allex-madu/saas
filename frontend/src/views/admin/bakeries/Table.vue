<template>
  <div>
    <Card noborder>
      <!-- Cabeçalho -->
      <div class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center">
        <h5 class="text-lg font-semibold">Padarias</h5>
        <div class="flex items-center gap-2">
          <SearchInput
            v-model="searchTerm"
            placeholder="Buscar padaria"
            class="ml-1 h-[40px]"
          />
          <Button
            text="Novo"
            icon="heroicons-outline:plus"
            btnClass="btn-primary h-[40px] px-4"
            @click="$router.push({ name: 'admin.bakeries.create' })"
          />
        </div>
      </div>

      <!-- Estado vazio ou erro -->
      <div v-if="error" class="text-red-500 p-4">{{ error }}</div>
      <div v-else-if="!loading && bakeries.length === 0" class="p-4 text-center text-gray-500">
        Nenhuma padaria encontrada.
      </div>

      <!-- Tabela -->
      <vue-good-table
        v-if="!error && bakeries.length > 0"
        :columns="columns"
        :rows="bakeries"
        :pagination-options="{ enabled: true, perPage }"
        styleClass="vgt-table bordered centered"
        :loading="loading"
        :total-rows="pagination.total"
        :paginate="true"
        :current-page="pagination.current_page"
        @on-page-change="handlePageChange"
        @on-per-page-change="handlePerPageChange"
      >
        <template #table-row="props">
          <template v-if="props.column.field === 'id'">
            <span>{{ props.row.id }}</span>
          </template>

          <template v-else-if="props.column.field === 'name'">
            <span class="font-medium">{{ props.row.name }}</span>
          </template>

          <template v-else-if="props.column.field === 'slug'">
            <span class="text-sm text-gray-600 dark:text-gray-300">{{ props.row.slug }}</span>
          </template>

          <template v-else-if="props.column.field === 'nif'">
            <span>{{ props.row.nif || '-' }}</span>
          </template>

          <!-- Ações -->
          <template v-else-if="props.column.field === 'actions'">
            <Dropdown classMenuItems="w-[140px]">
              <span class="text-xl">
                <Icon icon="heroicons-outline:dots-vertical" />
              </span>
              <template #menus>
                <MenuItem v-for="(item, i) in actions" :key="i">
                  <div
                    @click="handleAction(item.name, props.row)"
                    :class="[
                      'w-full border-b px-4 py-2 text-sm cursor-pointer flex items-center space-x-2',
                      item.name === 'delete'
                        ? 'bg-danger-500 text-danger-500 bg-opacity-30 hover:bg-opacity-100 hover:text-white'
                        : 'hover:bg-slate-900 hover:text-white dark:hover:bg-slate-600 dark:hover:bg-opacity-50'
                    ]"
                  >
                    <span class="text-base"><Icon :icon="item.icon" /></span>
                    <span>{{ item.name }}</span>
                  </div>
                </MenuItem>
              </template>
            </Dropdown>
          </template>
        </template>

        <!-- Paginação -->
        <template #pagination-bottom>
          <div class="py-4 px-3">
            <Pagination
              :total="pagination.total"
              :current="pagination.current_page"
              :perPage="perPage"
              @page-changed="handlePageChange"
              @per-page-change="handlePerPageChange"
            />
          </div>
        </template>
      </vue-good-table>
    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import Swal from 'sweetalert2'
import { storeToRefs } from 'pinia'
import debounce from 'lodash.debounce'
import { useAdminBakeryStore } from '@/store/adminBakeryStore'

// Components
import Button from '@/components/Button'
import Card from '@/components/Card'
import SearchInput from '@/components/InputGroup/SearchInput'
import Pagination from '@/components/Pagination'
import Dropdown from '@/components/Dropdown'
import Icon from '@/components/Icon'
import { MenuItem } from '@headlessui/vue'

// Instâncias
const router = useRouter()
const toast = useToast()
const bakeryStore = useAdminBakeryStore()
const { bakeries, loading, error, pagination, perPage } = storeToRefs(bakeryStore)

// Busca com debounce
const searchTerm = ref('')
const debouncedSearch = debounce(() => {
  bakeryStore.fetchBakeries(1, searchTerm.value, perPage.value)
}, 500)
watch(searchTerm, debouncedSearch, { immediate: true })

onMounted(() => {
  bakeryStore.fetchBakeries(1, '', perPage.value)
})

// Colunas
const columns = [
  { label: 'ID', field: 'id' },
  { label: 'Nome', field: 'name' },
  { label: 'Slug', field: 'slug' },
  { label: 'NIF', field: 'nif' },
  { label: 'Ações', field: 'actions' },
]

// Ações do dropdown
const actions = [
  { name: 'ver', icon: 'heroicons-outline:eye' },
  { name: 'editar', icon: 'heroicons-outline:pencil' },
  { name: 'delete', icon: 'heroicons-outline:trash' },
]

// Manipulador de ações
function handleAction(action, bakery) {
  switch (action) {
    case 'ver':
      router.push({ name: 'admin.bakeries.show', params: { id: bakery.id } })
      break
    case 'editar':
      router.push({ name: 'admin.bakeries.edit', params: { id: bakery.id } })
      break
    case 'delete':
      Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja excluir a padaria "${bakery.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            await bakeryStore.deleteBakery(bakery.id)
            toast.success(`Padaria "${bakery.name}" deletada com sucesso!`)
            await bakeryStore.fetchBakeries(1, searchTerm.value, perPage.value)
          } catch (error) {
            toast.error('Erro ao deletar a padaria.')
          }
        }
      })
      break
  }
}

// Paginação
function handlePageChange(page) {
  bakeryStore.fetchBakeries(page, searchTerm.value, perPage.value)
}

function handlePerPageChange({ currentPerPage }) {
  bakeryStore.fetchBakeries(1, searchTerm.value, currentPerPage)
}
</script>
