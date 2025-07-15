<template>
  <div>
    <Card noborder>
      <div class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center">
        <h5 class="text-lg font-semibold">Usuários</h5>
        <InputGroup
          v-model="searchTerm"
          placeholder="Buscar usuário"
          type="text"
          prependIcon="heroicons-outline:search"
          merged
        />
      </div>

      <!-- Mensagens de erro ou vazio -->
      <div v-if="error" class="text-red-500 p-4">
        {{ error }}
      </div>
      <div v-else-if="!loading && users.length === 0" class="p-4 text-center text-gray-500">
        Nenhum usuário encontrado.
      </div>

      <vue-good-table
        v-if="!error && users.length > 0"
        :columns="columns"
        styleClass="vgt-table bordered centered"
        :rows="users"
        :pagination-options="{
          enabled: true,
          perPage: perPage,
        }"
        :search-options="{
          enabled: true,
          externalQuery: searchTerm,
        }"
        :loading="loading"
        :total-rows="pagination.total"
        :paginate="true"
        :current-page="currentPage"
        @on-page-change="handlePageChange"
        @on-per-page-change="handlePerPageChange"
      >
        <template #table-row="props">
          <span v-if="props.column.field === 'id'">{{ props.row.id }}</span>

          <span v-else-if="props.column.field === 'name'">
            {{ props.row.person?.name || '-' }}
          </span>

          <span v-else-if="props.column.field === 'email'">
            {{ props.row.email }}
          </span>

          <span v-else-if="props.column.field === 'roles'">
            <span
              v-for="role in props.row.roles"
              :key="role.id"
              class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mr-1"
            >
              {{ role.name }}
            </span>
          </span>

          <span v-else-if="props.column.field === 'actions'">
            <Dropdown classMenuItems="w-[140px]">
              <span class="text-xl">
                <Icon icon="heroicons-outline:dots-vertical" />
              </span>
              <template #menus>
                <MenuItem v-for="(item, i) in actions" :key="i">
                  <div
                    @click="handleAction(item.name, props.row)"
                    :class="[
                      'w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm cursor-pointer flex items-center space-x-2',
                      item.name === 'delete'
                        ? 'bg-danger-500 text-danger-500 bg-opacity-30 hover:bg-opacity-100 hover:text-white'
                        : 'hover:bg-slate-900 hover:text-white dark:hover:bg-slate-600 dark:hover:bg-opacity-50'
                    ]"
                  >
                    <span class="text-base">
                      <Icon :icon="item.icon" />
                    </span>
                    <span>{{ item.name }}</span>
                  </div>
                </MenuItem>
              </template>
            </Dropdown>
          </span>
        </template>

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
import Card from '@/components/Card'
import InputGroup from '@/components/InputGroup'
import Dropdown from '@/components/Dropdown'
import Pagination from '@/components/Pagination'
import Icon from '@/components/Icon'
import { MenuItem } from '@headlessui/vue'
import { useAdminUserStore } from '@/store/adminUserStore'
import { useRouter } from 'vue-router'


const store = useAdminUserStore()

const searchTerm = ref('')

const error = computed(() => store.error)

const perPage = computed(() => store.perPage)

const router = useRouter()

// Função debounced para busca
const debouncedSearch = debounce(() => {
  store.fetchUsers(1, searchTerm.value, perPage.value)
}, 500)


watch(searchTerm, debouncedSearch, { immediate: true })


// Primeira chamada
onMounted(() => {
  store.fetchUsers(1, '', perPage.value)
})

const users = computed(() => store.users)
const pagination = computed(() => store.pagination)
const loading = computed(() => store.loading)
const currentPage = computed(() => store.currentPage.value)

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
  if (action === 'ver') {
    router.push({ name: 'admin.users.show', params: { id: user.id } })
  }
  // Aqui você pode implementar as outras ações
  else if (action === 'editar') {
    router.push({ name: 'admin.users.edit', params: { id: user.id } })
  } else if (action === 'delete') {
    // Exemplo: SweetAlert2 para confirmar e deletar
    console.log('Excluir usuário:', user)
  }
}

function handlePageChange(page) {
  store.fetchUsers(page, searchTerm.value, perPage.value)
}

function handlePerPageChange({ currentPerPage }) {
  store.perPage.value = currentPerPage
  store.fetchUsers(1, searchTerm.value, store.perPage.value)
}
</script>
