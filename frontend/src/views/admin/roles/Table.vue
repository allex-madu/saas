<template>
  <div>
    <Card noborder>
      <!-- Cabeçalho com título e barra de busca -->
      <div class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center">
        <h5 class="text-lg font-semibold">Atribuições</h5>
        <div class="flex items-center gap-2">
           
          <SearchInput
            v-model="searchTerm"
            placeholder="Buscar usuário"
            class="ml-1 h-[40px]"
          />

          <Button
            text="Novo"
            icon="heroicons-outline:plus"
            btnClass="btn-primary h-[40px] px-4"
            @click="$router.push({ name: 'admin.roles.create' })"
          />
        </div>
      </div>

      <!-- Loading visual -->
      <div v-if="loading && roles.length === 0" class="text-center text-gray-500 py-6">
        Carregando papéis...
      </div>

      <!-- Erro ou estado vazio -->
      <div v-if="error" class="text-red-500 p-4">{{ error }}</div>
      <div v-else-if="!loading && roles.length === 0" class="p-4 text-center text-gray-500">
        Nenhum papel encontrado.
      </div>

      <!-- Tabela de papéis -->
      <vue-good-table
        v-if="!error && roles.length > 0"
        :columns="columns"
        :rows="roles"
        :pagination-options="{ enabled: true, perPage }"
        styleClass="vgt-table bordered centered"
        :loading="loading"
        :total-rows="pagination.total"
        :paginate="true"
        :current-page="pagination.current_page"
        @on-page-change="handlePageChange"
        @on-per-page-change="handlePerPageChange"
      >
        <!-- Linhas personalizadas -->
        <template #table-row="props">
          <template v-if="props.column.field === 'name'">
            <span class="font-medium">{{ props.row.name }}</span>
          </template>

          <template v-else-if="props.column.field === 'description'">
            <span>{{ props.row.description || '-' }}</span>
          </template>

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

        <!-- Paginação inferior -->
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
// Imports
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import Swal from 'sweetalert2'
import { storeToRefs } from 'pinia'
import debounce from 'lodash.debounce'
import { useAdminRoleStore } from '@/store/adminRoleStore'
import SearchInput from '@/components/InputGroup/SearchInput'

// Components
import Button from '@/components/Button'
import Card from '@/components/Card'
import InputGroup from '@/components/InputGroup'
import Pagination from '@/components/Pagination'
import Dropdown from '@/components/Dropdown'
import Icon from '@/components/Icon'
import { MenuItem } from '@headlessui/vue'

// Instâncias e reativos
const router = useRouter()
const toast = useToast()
const roleStore = useAdminRoleStore()
const { roles, loading, error, pagination, perPage } = storeToRefs(roleStore)

// Busca com debounce
const searchTerm = ref('')
const debouncedSearch = debounce(() => {
  roleStore.fetchRoles(1, searchTerm.value, perPage.value)
}, 500)
watch(searchTerm, debouncedSearch, { immediate: true })

// Busca inicial
onMounted(() => {
  roleStore.fetchRoles(1, '', perPage.value)
})

// Colunas da tabela
const columns = [
  { label: 'Nome', field: 'name' },
  { label: 'Descrição', field: 'description' },
  { label: 'Ações', field: 'actions' },
]

// Ações disponíveis no dropdown
const actions = [
  { name: 'ver', icon: 'heroicons-outline:eye' },
  { name: 'editar', icon: 'heroicons-outline:pencil' },
  { name: 'delete', icon: 'heroicons-outline:trash' },
]

// Lida com cada ação do dropdown
function handleAction(action, role) {
  switch (action) {
    case 'ver':
      router.push({ name: 'admin.roles.show', params: { id: role.id } })
      break
    case 'editar':
      router.push({ name: 'admin.roles.edit', params: { id: role.id } })
      break
    case 'delete':
      Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja excluir o papel "${role.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            await roleStore.deleteRole(role.id)
            toast.success(`Papel "${role.name}" deletado com sucesso!`)
            await roleStore.fetchRoles(1, searchTerm.value, perPage.value)
          } catch (error) {
            toast.error(error?.response?.data?.message || 'Erro ao deletar o papel.')
          }
        }
      })
      break
  }
}

// Paginação e troca de itens por página
function handlePageChange(page) {
  roleStore.fetchRoles(page, searchTerm.value, perPage.value)
}

function handlePerPageChange({ currentPerPage }) {
  roleStore.fetchRoles(1, searchTerm.value, currentPerPage)
}
</script>
