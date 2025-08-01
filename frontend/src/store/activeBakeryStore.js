import { defineStore } from 'pinia'
import api from '@/plugins/axios'
import { ref } from 'vue'
import { ContentContainer } from '@fullcalendar/core/internal'

export const useActiveBakeryStore = defineStore('activeBakery', () => {
  const myBakeries = ref([])
  const activeBakery = ref(null)
  const loading = ref(false)

  const fetchMyBakeries = async () => {
    try {
      loading.value = true
      const { data } = await api.get('/api/v1/my-bakeries')
      myBakeries.value = data


      // Se não houver uma padaria ativa, definir a primeira
      if (!activeBakery.value && data.length > 0) {
        setActiveBakery(data[0])
      }
    } catch (error) {
      console.error('Erro ao buscar padarias:', error)
    } finally {
      loading.value = false
    }
  }

  const setActiveBakery = (bakery) => {
    activeBakery.value = bakery
    localStorage.setItem('activeBakeryId', bakery.id)
  }

  const loadActiveBakeryFromStorage = () => {
    const id = localStorage.getItem('activeBakeryId')
    if (id && myBakeries.value.length > 0) {
      const found = myBakeries.value.find(b => b.id == id)
      if (found) activeBakery.value = found
    }
  }

  return {
    myBakeries,
    activeBakery,
    loading,
    fetchMyBakeries,
    setActiveBakery,
    loadActiveBakeryFromStorage,
  }
})
