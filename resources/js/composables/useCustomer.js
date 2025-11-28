// Customer composable for reusable logic
import { ref, computed } from 'vue'
import { useCustomerStore } from '../store/customer'

export function useCustomer() {
  const customerStore = useCustomerStore()
  const customers = computed(() => customerStore.customers)
  const loading = computed(() => customerStore.loading)

  const fetchCustomers = async () => {
    await customerStore.fetchCustomers()
  }

  const createCustomer = async (customer) => {
    await customerStore.createCustomer(customer)
  }

  return {
    customers,
    loading,
    fetchCustomers,
    createCustomer
  }
}
