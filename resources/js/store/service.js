// Service store using Pinia
import { defineStore } from 'pinia'

export const useServiceStore = defineStore('service', {
  state: () => ({
    services: [],
    loading: false
  }),
  actions: {
    async fetchServices() {
      this.loading = true
      // Fetch services logic here
      this.loading = false
    },
    async createService(service) {
      // Create service logic
    }
  }
})
