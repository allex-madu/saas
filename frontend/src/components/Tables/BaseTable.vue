<!-- src/components/users/UsersTable.vue -->
<template>
  <BaseTable
    :columns="columns"
    :rows="users"
    :pagination="pagination"
    :loading="loading"
    :error="error"
    :perPage="perPage"
    :onPageChange="handlePageChange"
    :onPerPageChange="handlePerPageChange"
  >
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
        <span
          v-for="role in props.row.roles"
          :key="role.id"
          class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mr-1"
        >
          {{ role.name }}
        </span>
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
                <Icon :icon="item.icon" />
                <span>{{ item.name }}</span>
              </div>
            </MenuItem>
          </template>
        </Dropdown>
      </template>
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
  </BaseTable>
</template>

<script setup>
import BaseTable from '@/components/Tables/BaseTable.vue'
import Pagination from '@/components/Pagination.vue'
import Dropdown from '@/components/Dropdown.vue'
import Icon from '@/components/Icon.vue'
import { MenuItem } from '@headlessui/vue'
import { useAdminUserStore } from '@/store/adminUserStore'
import { ref, computed, onMounted, watch } from 'vue'
import debounce from 'lodash.debounce'
import { useRouter } from 'vue-router'

const store = useAdminUserStore()
const router = useRouter()

const searchTerm = ref('')
const users = computed(() => store.users)
const pagination = computed(() => store.pagination)
const loading = computed(() => store.loading)
const error = computed(() => store.error)
const perPage = computed(() => store.perPage)
const currentPage = computed(() => store.currentPage)

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

const debouncedSearch = debounce(() => {
  store.fetchUsers(1, searchTerm.value, perPage.value)
}, 500)

watch(searchTerm, debouncedSearch, { immediate: true })

onMounted(() => {
  store.fetchUsers(1, '', perPage.value)
})

function handleAction(action, user) {
  if (action === 'ver') router.push({ name: 'admin.users.show', params: { id: user.id } })
  else if (action === 'editar') router.push({ name: 'admin.users.edit', params: { id: user.id } })
  else if (action === 'delete') console.log('Excluir:', user)
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
