// Vehicle store using Pinia
import { defineStore } from 'pinia'

export const useVehicleStore = defineStore('vehicle', {
  state: () => ({
    vehicles: [],
    loading: false
  }),
  actions: {
    async fetchVehicles() {
      this.loading = true
      // Fetch vehicles logic here
      this.loading = false
    },
    async createVehicle(vehicle) {
      // Create vehicle logic
    }
  }
})
