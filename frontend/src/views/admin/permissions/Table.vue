<template>
  <div>
    <Card noborder>
      <!-- Cabeçalho -->
      <div class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center">
        <h5 class="text-lg font-semibold">Permissões</h5>
        <div class="flex items-center gap-2">
          <InputGroup
            v-model="searchTerm"
            placeholder="Buscar"
            type="text"
            prependIcon="heroicons-outline:search"
            merged
            class="ml-1"
          />
          <Button
            text="Novo"
            icon="heroicons-outline:plus"
            btnClass="btn-primary h-[40px] px-4"
            @click="$router.push({ name: 'admin.permissions.create' })"
          />
        </div>
      </div>

      <!-- Estado vazio ou erro -->
      <div v-if="error" class="text-red-500 p-4">{{ error }}</div>
      <div v-else-if="!loading && permissions.length === 0" class="p-4 text-center text-gray-500">
        Nenhuma permissão encontrada.
      </div>

      <!-- Tabela -->
      <vue-good-table
        v-if="!error && permissions.length > 0"
        :columns="columns"
        :rows="permissions"
        :pagination-options="{ enabled: true, perPage }"
        styleClass="vgt-table bordered centered"
        :loading="loading"
        :total-rows="pagination.total"
        :paginate="true"
        :current-page="pagination.current_page"
        @on-page-change="handlePageChange"
        @on-per-page-change="handlePerPageChange"
      >
        <!-- Linhas -->
        <template #table-row="props">
          <template v-if="props.column.field === 'id'">
            <span>{{ props.row.id }}</span>
          </template>

          <template v-else-if="props.column.field === 'name'">
            <span class="font-medium">{{ props.row.name }}</span>
          </template>

          <template v-else-if="props.column.field === 'description'">
            <span>{{ props.row.description || '-' }}</span>
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
import { usePermissionStore } from '@/store/permissionsStore'

// Components
import Button from '@/components/Button'
import Card from '@/components/Card'
import InputGroup from '@/components/InputGroup'
import Pagination from '@/components/Pagination'
import Dropdown from '@/components/Dropdown'
import Icon from '@/components/Icon'
import { MenuItem } from '@headlessui/vue'

// Instâncias
const router = useRouter()
const toast = useToast()
const permissionStore = usePermissionStore()
const { permissions, loading, error, pagination, perPage } = storeToRefs(permissionStore)

// Busca com debounce
const searchTerm = ref('')
const debouncedSearch = debounce(() => {
  permissionStore.fetchPermissions(1, searchTerm.value, perPage.value)
}, 500)
watch(searchTerm, debouncedSearch, { immediate: true })

onMounted(() => {
  permissionStore.fetchPermissions(1, '', perPage.value)
})

// Colunas
const columns = [
  { label: 'ID', field: 'id' },
  { label: 'Nome', field: 'name' },
  { label: 'Descrição', field: 'description' },
  { label: 'Ações', field: 'actions' },
]

// Ações do dropdown
const actions = [
  { name: 'ver', icon: 'heroicons-outline:eye' },
  { name: 'editar', icon: 'heroicons-outline:pencil' },
  { name: 'delete', icon: 'heroicons-outline:trash' },
]

// Manipulador de ações
function handleAction(action, permission) {
  switch (action) {
    case 'ver':
      router.push({ name: 'admin.permissions.show', params: { id: permission.id } })
      break
    case 'editar':
      router.push({ name: 'admin.permissions.edit', params: { id: permission.id } })
      break
    case 'delete':
      Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja excluir a permissão "${permission.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            await permissionStore.deletePermission(permission.id)
            toast.success(`Permissão "${permission.name}" deletada com sucesso!`)
            await permissionStore.fetchPermissions(1, searchTerm.value, perPage.value)
          } catch (error) {
            toast.error('Erro ao deletar a permissão.')
          }
        }
      })
      break
  }
}

// Paginação
function handlePageChange(page) {
  permissionStore.fetchPermissions(page, searchTerm.value, perPage.value)
}

function handlePerPageChange({ currentPerPage }) {
  permissionStore.fetchPermissions(1, searchTerm.value, currentPerPage)
}
</script>
