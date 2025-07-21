<template>
  <div>
    <div class="mb-4 flex justify-between items-center">
      <h1 class="text-xl font-bold">Papéis</h1>
      <router-link :to="{ name: 'roles.create' }" class="btn btn-primary">
        Criar Papel
      </router-link>
    </div>

    <vue-good-table
      :columns="columns"
      :rows="roles"
      :pagination-options="{
        enabled: true,
        perPage: perPage,
        perPageDropdownEnabled: true,
        dropdownAllowAll: false,
        setCurrentPage: currentPage,
        nextLabel: 'Próximo',
        prevLabel: 'Anterior'
      }"
      :totalRows="pagination.total"
      :search-options="{ enabled: true, externalQuery: searchQuery }"
      :line-numbers="true"
      @on-page-change="onPageChange"
      @on-per-page-change="onPerPageChange"
    >
      <template v-slot:table-actions>
        <input
          v-model="searchQuery"
          @input="handleSearch"
          placeholder="Buscar papel..."
          class="form-input"
        />
      </template>

      <template #table-row="props">
        <span v-if="props.column.field === 'actions'">
          <Dropdown classMenuItems="w-[140px]">
            <span class="text-xl">
              <Icon icon="heroicons-outline:dots-vertical" />
            </span>
            <template #menus>
              <MenuItem @click="router.push({ name: 'roles.show', params: { id: props.row.id } })">
                Ver
              </MenuItem>
              <MenuItem @click="router.push({ name: 'roles.edit', params: { id: props.row.id } })">
                Editar
              </MenuItem>
              <MenuItem @click="confirmDelete(props.row.id)">
                <span class="text-danger-500">Excluir</span>
              </MenuItem>
            </template>
          </Dropdown>
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoleStore } from '@/stores/adminRoleStore'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()
const roleStore = useRoleStore()

const searchQuery = ref('')
const perPage = ref(10)
const currentPage = ref(1)

const { roles, pagination, fetchRoles, deleteRole } = roleStore

const columns = [
  { label: 'Nome', field: 'name' },
  { label: 'Ações', field: 'actions' },
]

const handleSearch = () => {
  fetchRoles(currentPage.value, searchQuery.value, perPage.value)
}

const onPageChange = (page) => {
  currentPage.value = page
  fetchRoles(page, searchQuery.value, perPage.value)
}

const onPerPageChange = (perPageValue) => {
  perPage.value = perPageValue
  fetchRoles(currentPage.value, searchQuery.value, perPageValue)
}

const confirmDelete = (id) => {
  Swal.fire({
    title: 'Excluir papel?',
    text: 'Essa ação não poderá ser desfeita!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sim, excluir',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      await deleteRole(id)
    }
  })
}

onMounted(() => {
  fetchRoles()
})
</script>
