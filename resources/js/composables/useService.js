// Service composable for reusable logic
import { ref, computed } from 'vue'
import { useServiceStore } from '../store/service'

export function useService() {
  const serviceStore = useServiceStore()
  const services = computed(() => serviceStore.services)
  const loading = computed(() => serviceStore.loading)

  const fetchServices = async () => {
    await serviceStore.fetchServices()
  }

  const createService = async (service) => {
    await serviceStore.createService(service)
  }

  return {
    services,
    loading,
    fetchServices,
    createService
  }
}
