<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader />
    </template>
    <template #default>
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard </h1>


      <!-- Metric Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card>
          <template #icon>
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6" />
              </svg>
            </div>
          </template>
          <template #title>Total Vehicles</template>
          <template #default>150</template>
        </Card>
        <Card>
          <template #icon>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 006-6v-1a3 3 0 00-3-3H9a3 3 0 00-3 3v1a6 6 0 006 6z" />
                </svg>
            </div>
          </template>
          <template #title>Total Customers</template>
          <template #default>84</template>
        </Card>
        <Card>
          <template #icon>
             <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
          </template>
          <template #title>Active Services</template>
          <template #default>23</template>
        </Card>
        <Card>
          <template #icon>
            <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
          </template>
          <template #title>Reports Generated</template>
          <template #default>12</template>
        </Card>
      </div>

     <!-- Recent Services Table -->
      <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-gray-800">Recent Services</h2>
          <Button @click="showCreateModal = true">Create Service</Button>
        </div>
        <Table :headers="tableHeaders" :items="tableItems">
          <template #actions="{ item }">
            <a href="#" class="text-blue-500 hover:underline mr-4">View</a>
            <a href="#" class="text-green-500 hover:underline">Edit</a>
          </template>
        </Table>
      </div>

      <!-- Create Service Modal -->
      <Modal :show="showCreateModal" @close="showCreateModal = false">
        <template #header>
            <h3 class="text-lg font-bold">Create New Service</h3>
        </template>

        <div class="space-y-4">
            <FormInput v-model="newService.customerName" label="Customer Name" placeholder="e.g., John Doe" />
            <FormInput v-model="newService.vehicleModel" label="Vehicle Model" placeholder="e.g., Toyota Camry" />
            <FormInput v-model="newService.serviceDescription" label="Service Description" placeholder="e.g., Oil Change" />
        </div>

        <template #footer>
          <Button variant="secondary" @click="showCreateModal = false" class="mr-2">Cancel</Button>
          <Button @click="createService">Save</Button>
        </template>
      </Modal>
    </template>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Table from '@/Components/ui/Table.vue';
import Modal from '@/Components/ui/Modal.vue';
import Button from '@/Components/ui/Button.vue';
import FormInput from '@/Components/ui/FormInput.vue';
import { ref } from 'vue';

const showCreateModal = ref(false);

// Sample data for the table
const tableHeaders = ref([
  { text: 'Customer', value: 'customer' },
  { text: 'Vehicle', value: 'vehicle' },
  { text: 'Service', value: 'service' },
  { text: 'Status', value: 'status' },
]);

const tableItems = ref([
  { id: 1, customer: 'John Doe', vehicle: 'Toyota Camry', service: 'Oil Change', status: 'Completed' },
  { id: 2, customer: 'Jane Smith', vehicle: 'Honda Civic', service: 'Tire Rotation', status: 'In Progress' },
  { id: 3, customer: 'Sam Wilson', vehicle: 'Ford F-150', service: 'Brake Inspection', status: 'Pending' },
]);

const newService = ref({
    customerName: '',
    vehicleModel: '',
    serviceDescription: ''
});

const createService = () => {
    // Logic to save the new service would go here
    console.log('Saving new service:', newService.value);
    showCreateModal.value = false;
    // Optionally, reset form
    newService.value = { customerName: '', vehicleModel: '', serviceDescription: '' };
};
</script>
