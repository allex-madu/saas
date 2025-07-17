<template>
  <div>
    <Card title="Criar Novo Usuário">
      <form @submit.prevent="handleSubmit" class="space-y-6">

        <!-- Pessoa -->
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-white">
            Pessoa
          </label>
          <Combobox v-model="selectedPerson">
            <div class="relative">
              <ComboboxInput
                :modelValue="query"
                @update:modelValue="onInput"
                :displayValue="() => query"
                class="w-full rounded border border-gray-300 dark:border-slate-600 px-3 py-2 dark:bg-slate-800 dark:text-white focus:ring-1 focus:ring-primary-500"
                placeholder="Digite nome ou email"
                autocomplete="off"
              />


              <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded bg-white dark:bg-slate-800 shadow-lg">
                <template v-if="loading">
                  <div class="px-4 py-2 text-gray-500 dark:text-gray-400">Carregando...</div>
                </template>
                <template v-else-if="filteredPeople.length === 0">
                  <div class="px-4 py-2 text-gray-500 dark:text-gray-400">Nenhuma pessoa encontrada.</div>
                </template>
                <template v-else>
                  <ComboboxOption
                    v-for="person in filteredPeople"
                    :key="person.id"
                    :value="person"
                    class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700"
                  >
                    {{ person.name }} - {{ person.email }}
                  </ComboboxOption>
                </template>
              </ComboboxOptions>
            </div>
          </Combobox>
          <span v-if="errors.person_id" class="text-sm text-red-500">{{ errors.person_id[0] }}</span>
        </div>

        <!-- Nome -->
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Digite o nome"
          prependIcon="heroicons-outline:user"
          required
          isReadonly
        />
        <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>

        <!-- Email -->
        <InputGroup
          label="Email"
          v-model="form.email"
          type="email"
          placeholder="Digite o e-mail"
          prependIcon="heroicons-outline:mail"
          required
          isReadonly
        />
        <span v-if="errors.email" class="text-sm text-red-500">{{ errors.email[0] }}</span>

        <!-- Senha -->
        <InputGroup
          label="Senha"
          v-model="form.password"
          type="password"
          placeholder="Crie uma senha"
          prependIcon="heroicons-outline:lock-closed"
          required
        />
        <span v-if="errors.password" class="text-sm text-red-500">{{ errors.password[0] }}</span>

        <!-- Papel -->
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-white">Papel</label>
          <select
            v-model="form.role"
            class="w-full rounded border border-gray-300 dark:border-slate-600 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white"
            required
          >
            <option disabled value="">Selecione um papel</option>
            <option v-for="role in roles" :key="role.value" :value="role.value">
              {{ role.label }}
            </option>
          </select>
          <span v-if="errors.role" class="text-sm text-red-500">{{ errors.role[0] }}</span>
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-2">
          <Button variant="outline" @click="$router.back()">Cancelar</Button>
          <Button type="submit" :loading="loading">Salvar</Button>
        </div>

      </form>
    </Card>
  </div>
</template>

<script setup>
import {
  ref, watch, computed, onMounted
} from 'vue'
import { useRouter } from 'vue-router'
import api from '@/plugins/axios'
import debounce from 'lodash.debounce'
import { useToast } from "vue-toastification";
import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'
import {
  Combobox, ComboboxInput, ComboboxOptions, ComboboxOption
} from '@headlessui/vue'

const router = useRouter()
const loading = ref(false)
const people = ref([])
const query = ref('')
const selectedPerson = ref(null)
const errors = ref({})
const toast = useToast();

const form = ref({
  person_id: '',
  name: '',
  email: '',
  password: '',
  role: ''
})

const roles = [
  { label: 'Super admin', value: 'super-admin' },
  { label: 'Admin', value: 'admin' },
  { label: 'Usuário', value: 'usuario' },
  { label: 'Gerente', value: 'gerente' },
  { label: 'Caixa', value: 'caixa' }
]

const loadPeople = async (search = '') => {
  loading.value = true
  try {
    const response = await api.get('/api/v1/admin/people', {
      params: { search }
    })
    people.value = response.data.data
    console.log('Busca por pessoas com:', search, response.data.data)

  } catch (error) {
    console.error('Erro ao buscar pessoas', error)
  } finally {
    loading.value = false
  }
}

const filteredPeople = computed(() => {
  if (!query.value) return people.value
  const lowerQuery = query.value.toLowerCase()
  return people.value.filter(person =>
    person.name.toLowerCase().includes(lowerQuery) ||
    person.email.toLowerCase().includes(lowerQuery)
  )
})

const debouncedLoadPeople = debounce(loadPeople, 300)

function onInput(event) {
  query.value = event.target.value
  debouncedLoadPeople(query.value)
}


watch(selectedPerson, (newPerson) => {
  if (newPerson) {
    form.value.person_id = newPerson.id
    form.value.name = newPerson.name
    form.value.email = newPerson.email
    query.value = `${newPerson.name} - ${newPerson.email}`
  } else {
    form.value.person_id = ''
    form.value.name = ''
    form.value.email = ''
    query.value = ''
  }
})

onMounted(() => {
  loadPeople()
})

const handleSubmit = async () => {
  errors.value = {}
  if (!form.value.person_id) {
    errors.value.person_id = ['Selecione uma pessoa para vincular ao usuário.']
    return
  }

  loading.value = true
  try {
    await api.post('/api/v1/admin/users', form.value)
    //console.log('Form a ser enviado:', form.value)
    toast.success('Usuário registrado com sucesso', { timeout: 2000 });
    router.push({ name: 'admin.users' })
  } catch (error) {
     toast.error(error.response?.data?.message || 'Erro ao registrar usuário.', { timeout: 2000 });
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      console.error(error)
    }
  } finally {
    loading.value = false
  }
}
</script>
