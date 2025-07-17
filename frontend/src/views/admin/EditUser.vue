<template>
  <!-- Garantir que o conteúdo só será renderizado quando os dados forem carregados corretamente -->
  <div>
    <Card title="Editar Usuário">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Nome -->
        <InputGroup
          label="Nome"
          v-model="form.name"
          type="text"
          placeholder="Digite o nome"
          required
        />

        <!-- Email -->
        <InputGroup
          label="Email"
          v-model="form.email"
          type="email"
          placeholder="Digite o e-mail"
          required
        />

        <!-- Senha -->
        <InputGroup
          label="Senha"
          v-model="form.password"
          type="password"
          placeholder="Crie uma senha"
        />

        <!-- Papéis -->
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-white">Papel</label>
          <select
            v-model="form.roles"
            class="w-full rounded border border-gray-300 dark:border-slate-600 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white"
            required
          >
            <option disabled value="">Selecione um papel</option>
            <option v-for="role in roles" :key="role.value" :value="role.value">
              {{ role.label }}
            </option>
          </select>
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
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'
import InputGroup from '@/components/InputGroup'
import Button from '@/components/Button'
import Card from '@/components/Card'

const route = useRoute()
const toast = useToast()

const form = ref({
  name: '',
  email: '',
  roles: [],
  password: ''
})

const roles = ref([])  // Lista de papéis disponíveis
const loading = ref(true)  // Estado de carregamento

// Função para carregar os dados do usuário
const loadUserData = async (id) => {
  try {
    const response = await api.get(`/api/v1/admin/users/${id}`);
    if (response.status !== 200) {
      throw new Error('Falha na requisição');
    }

    if (response.data.user) {
      form.value.name = response.data.user.person.name || ''; 
      form.value.email = response.data.user.email || '';
      form.value.roles = response.data.user.roles || [];
      form.value.password = ''; // Garantir que a senha fique vazia
    }

  } catch (error) {
    toast.error(`Erro ao carregar dados do usuário: ${error.message}`);
    console.error('Erro no carregamento do usuário:', error);
  } finally {
    loading.value = false;
  }
};

// Função para carregar os papéis disponíveis
const loadRoles = async () => {
  try {
    const response = await api.get('/api/v1/admin/roles');
    if (response.status === 200) {
      // Supondo que a resposta tenha uma lista de papéis no formato { value, label }
      roles.value = response.data.roles.map(role => ({
        value: role.id,  // ou o campo que representa o ID do papel
        label: role.name  // ou o campo que representa o nome do papel
      }));
    }
  } catch (error) {
    toast.error(`Erro ao carregar papéis: ${error.message}`);
    console.error('Erro no carregamento dos papéis:', error);
  }
};

// Função para enviar os dados de edição do usuário
const handleSubmit = async () => {
  loading.value = true
  try {
    const userId = route.params.id
    const dataToSend = {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password || undefined, // Envia a senha apenas se for modificada
      roles: form.value.roles,
    }

    await api.put(`/api/v1/admin/users/${userId}`, dataToSend)
    toast.success('Usuário atualizado com sucesso')
    $router.push({ name: 'admin.users.show', params: { id: userId } })  // Redireciona para a página do usuário
  } catch (error) {
    toast.error('Erro ao atualizar usuário')
    console.error(error) // Mostrar o erro no console para debug
  } finally {
    loading.value = false
  }
}

// Carregar os dados do usuário e os papéis quando o componente for montado
onMounted(() => {
  const userId = route.params.id
  loadUserData(userId)
  loadRoles()  // Carregar os papéis disponíveis
})
</script>
