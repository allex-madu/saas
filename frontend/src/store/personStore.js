import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/plugins/axios'

export const usePersonStore = defineStore('people', () => {
  const people = ref([])
  const loading = ref(false)

  const fetchPeople = async (search = '') => {
    loading.value = true
    try {
      const res = await api.get('/api/v1/admin/people', { params: { search } })
      people.value = res.data.data
    } catch (e) {
      console.error('Erro ao buscar pessoas', e)
    } finally {
      loading.value = false
    }
  }

  return {
    people,
    loading,
    fetchPeople,
  }
})
