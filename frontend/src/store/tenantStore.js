import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

export const useTenantStore = defineStore('tenant', {
  state: () => ({
    bakeries: [],
    activeBakery: null,
    loading: false,
  }),

  persist: true, // Para manter activeBakery após recarregar

  actions: {
    async fetchMyBakeries() {
      this.loading = true
      try {
        const response = await axios.get('/api/v1/my-bakeries')
        this.bakeries = response.data.data

        // Se não houver padaria ativa, define a primeira
        if (!this.activeBakery && this.bakeries.length > 0) {
          this.activeBakery = this.bakeries[0]
        }
      } catch (error) {
        console.error('Erro ao buscar padarias:', error)
      } finally {
        this.loading = false
      }
    },

    setActiveBakery(bakery) {
      this.activeBakery = bakery
    },

    clearActiveBakery() {
      this.activeBakery = null
    },
  },
})
