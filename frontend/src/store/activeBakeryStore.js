import { defineStore } from 'pinia'
import api from '@/plugins/axios'
import { ref } from 'vue'
import { ContentContainer } from '@fullcalendar/core/internal'

export const useActiveBakeryStore = defineStore('activeBakery', () => {
  const myBakeries = ref([])
  const activeBakery = ref(null)
  const loading = ref(false)

  function loadActiveBakeryFromStorage() {
  const saved = localStorage.getItem('activeBakery')
    if (saved) {
      try {
        activeBakery.value = JSON.parse(saved)
      } catch (e) {
        console.error('Erro ao restaurar padaria ativa do localStorage', e)
      }
    }
  }


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
    localStorage.setItem('activeBakery', JSON.stringify(bakery))
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
