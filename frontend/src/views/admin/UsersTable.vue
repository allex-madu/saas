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

      <vue-good-table
        :columns="columns"
        styleClass="vgt-table bordered centered"
        :rows="filteredUsers"
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
        :current-page="pagination.current_page"
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
import { ref, computed, onMounted } from 'vue'
import Card from '@/components/Card'
import InputGroup from '@/components/InputGroup'
import Dropdown from '@/components/Dropdown'
import Pagination from '@/components/Pagination'
import Icon from '@/components/Icon'
import { MenuItem } from '@headlessui/vue'
import { useAdminUserStore } from '@/store/adminUserStore'

const store = useAdminUserStore()

const searchTerm = ref('')
const perPage = ref(10)

onMounted(() => {
  store.fetchUsers()
})

const users = computed(() => store.users)
const pagination = computed(() => store.pagination)
const loading = computed(() => store.loading)

const filteredUsers = computed(() => {
  const term = searchTerm.value.toLowerCase()
  return users.value.filter(user =>
    user.person?.name?.toLowerCase().includes(term) ||
    user.email?.toLowerCase().includes(term)
  )
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

function handlePageChange({ currentPage }) {
  store.fetchUsers(currentPage)
}

function handlePerPageChange({ currentPerPage }) {
  perPage.value = currentPerPage
  store.fetchUsers(1)
}
</script>
