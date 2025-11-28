// Vehicle composable for reusable logic
import { ref, computed } from 'vue'
import { useVehicleStore } from '../store/vehicle'

export function useVehicle() {
  const vehicleStore = useVehicleStore()
  const vehicles = computed(() => vehicleStore.vehicles)
  const loading = computed(() => vehicleStore.loading)

  const fetchVehicles = async () => {
    await vehicleStore.fetchVehicles()
  }

  const createVehicle = async (vehicle) => {
    await vehicleStore.createVehicle(vehicle)
  }

  return {
    vehicles,
    loading,
    fetchVehicles,
    createVehicle
  }
}
