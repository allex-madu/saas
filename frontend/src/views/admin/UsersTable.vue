<template>
  <div>
    <Card noborder>
      <!-- Cabeçalho -->
      <div class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center">
        <div class="flex items-center justify-between w-full">
          <h5 class="text-lg font-semibold">Usuários</h5>
          <router-link :to="{ name: 'admin.users.create' }">
            <button
              type="button"
              class="btn btn-primary h-[40px] text-sm font-medium flex items-center space-x-2"
            >
              <Icon icon="heroicons-outline:plus" />
              <span>Novo Usuário</span>
            </button>
          </router-link>
        </div>

        <InputGroup
          v-model="searchTerm"
          placeholder="Buscar usuário"
          type="text"
          prependIcon="heroicons-outline:search"
          merged
          class="ml-1"
        />
      </div>

      <!-- Estado: Erro ou sem resultados -->
      <div v-if="error" class="text-red-500 p-4">{{ error }}</div>
      <div v-else-if="!loading && users.length === 0" class="p-4 text-center text-gray-500">
        Nenhum usuário encontrado.
      </div>

      <!-- Tabela -->
      <vue-good-table
        v-if="!error && users.length > 0"
        :columns="columns"
        styleClass="vgt-table bordered centered"
        :rows="users"
        :pagination-options="{ enabled: true, perPage }"
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
            <span>{{ props.row.person?.name || '-' }}</span>
          </template>

          <template v-else-if="props.column.field === 'email'">
            <span>{{ props.row.email }}</span>
          </template>

          <template v-else-if="props.column.field === 'roles'">
            <div>
              <span
                v-for="role in props.row.roles"
                :key="role.id"
                class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mr-1"
              >
                {{ role.name }}
              </span>
            </div>
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

        <!-- Paginação personalizada -->
        <template #pagination-bottom="props">
          <div class="py-4 px-3">
            <Pagination
              :total="pagination.total"
              :current="pagination.current_page"
              :per-page="perPage"
              :pageRange="5"
              @page-changed="handlePageChange"
              :pageChanged="props.pageChanged"
              :perPageChanged="props.perPageChanged"
            />
          </div>
        </template>
      </vue-good-table>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import debounce from 'lodash.debounce'
import { useRouter } from 'vue-router'
import Card from '@/components/Card'
import InputGroup from '@/components/InputGroup'
import Dropdown from '@/components/Dropdown'
import Pagination from '@/components/Pagination'
import Icon from '@/components/Icon'
import { MenuItem } from '@headlessui/vue'
import { useAdminUserStore } from '@/store/adminUserStore'
import Swal from 'sweetalert2'
import { useToast } from "vue-toastification";

const adminUserStore = useAdminUserStore()
const router = useRouter()
const store = useAdminUserStore()
const searchTerm = ref('')
const users = computed(() => store.users)
const pagination = computed(() => store.pagination)
const loading = computed(() => store.loading)
const error = computed(() => store.error)
const perPage = computed(() => store.perPage)
//const currentPage = computed(() => store.currentPage)
const toast = useToast()

// Busca com debounce
const debouncedSearch = debounce(() => {
  store.fetchUsers(1, searchTerm.value, perPage.value)
}, 500)

watch(searchTerm, debouncedSearch, { immediate: true })

onMounted(() => {
  store.fetchUsers(1, '', perPage.value)
})

const columns = [
  { label: 'ID', field: 'id' },
  { label: 'Nome', field: 'name' },
  { label: 'Email', field: 'email' },
  { label: 'Papéis', field: 'roles' },
  { label: 'Ações', field: 'actions' },
]

const actions = [
  { name: 'ver', icon: 'heroicons-outline:eye' },
  { name: 'editar', icon: 'heroicons:pencil-square' },
  { name: 'delete', icon: 'heroicons-outline:trash' },
]

function handleAction(action, user) {
  switch (action) {
    case 'ver':
      router.push({ name: 'admin.users.show', params: { id: user.id } })
      break
    case 'editar':
      router.push({ name: 'admin.users.edit', params: { id: user.id } })
      break
    case 'delete':
      Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja excluir o usuário ${user.person?.name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            await adminUserStore.deleteUser(user.id)
            toast.success(`Usuário ${user.person?.name ?? user.name ?? 'desconhecido'} deletado com sucesso!`)

          } catch (error) {
            toast.error('Erro ao deletar o usuário.')
          }
        }
      })
      break
  }
} 

function handlePageChange(page) {
  store.currentPage = page
  store.fetchUsers(page, searchTerm.value, perPage.value)
}

function handlePerPageChange({ currentPerPage }) {
  store.perPage = currentPerPage
  store.fetchUsers(1, searchTerm.value, currentPerPage)
}
</script>


