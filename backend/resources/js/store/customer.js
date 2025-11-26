// Customer store using Pinia
import { defineStore } from 'pinia'

export const useCustomerStore = defineStore('customer', {
  state: () => ({
    customers: [],
    loading: false
  }),
  actions: {
    async fetchCustomers() {
      this.loading = true
      // Fetch customers logic here
      this.loading = false
    },
    async createCustomer(customer) {
      // Create customer logic
    }
  }
})
