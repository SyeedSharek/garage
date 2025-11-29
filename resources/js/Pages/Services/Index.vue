<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Services" subtitle="Manage your garage services" />
    </template>
    <template #default>
      <div class="p-6 space-y-4">
        <!-- Page Header with Title and Actions -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-foreground">Services</h1>
            <p class="text-sm text-muted-foreground mt-1">Manage and organize your garage services</p>
          </div>
          <div class="flex items-center gap-3">
            <!-- <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="outline" class="gap-2 h-10">
                  <HugeiconsIcon :name="Download01Icon" class="h-4 w-4 text-blue-600" />
                  Import
                  <HugeiconsIcon :name="ArrowDown01Icon" class="h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="handleImport('csv')">Import CSV</DropdownMenuItem>
                <DropdownMenuItem @click="handleImport('excel')">Import Excel</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu> -->
            <Button class="gap-2 h-10" @click="handleCreate">
              <HugeiconsIcon :name="Add01Icon" class="h-4 w-4" />
              Create Service
            </Button>
          </div>
        </div>

        <!-- Search and Filters Card -->
        <Card class="w-full">
          <div class="p-4 space-y-4">
            <div class="flex items-center gap-3">
              <div class="relative flex-1">
                <HugeiconsIcon
                  :name="Search01Icon"
                  class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none"
                />
                <Input
                  v-model="searchQuery"
                  placeholder="Search by code, name, or description..."
                  class="pl-10 h-10"
                  @update:modelValue="handleSearch"
                />
              </div>
              <DropdownMenu>
                <DropdownMenuTrigger as-child>
                  <Button variant="outline" class="gap-2 h-10">
                    <HugeiconsIcon :name="FilterHorizontalIcon" class="h-4 w-4" />
                    Filters
                    <HugeiconsIcon :name="ArrowDown01Icon" class="h-4 w-4" />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuItem @click="handleFilterStatus('all')">
                    All Services
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="handleFilterStatus(true)">
                    Active Only
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="handleFilterStatus(false)">
                    Inactive Only
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
            <div v-if="activeFilter !== 'all'" class="flex items-center gap-2">
              <Badge variant="secondary" class="gap-1">
                <span v-if="activeFilter === true">Active</span>
                <span v-else>Inactive</span>
                <button @click="handleFilterStatus('all')" class="ml-1 hover:text-foreground text-muted-foreground">
                  ×
                </button>
              </Badge>
            </div>
          </div>
        </Card>

        <!-- Table Card -->
        <DataTable
          :columns="columns"
          :data="services.data"
          :current-page="currentPage"
          :total-pages="totalPages"
          :total-items="totalItems"
          :initial-page-size="pageSize"
          :selectable="false"
          :draggable="false"
          :tabs="[]"
          @sort="handleSort"
          @page-change="handlePageChange"
          @page-size-change="handlePageSizeChange"
          @action="handleAction"
        >
          <template #cell-code="{ value }">
            <span class="font-mono text-sm text-muted-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-name="{ value }">
            <div class="flex flex-col">
              <span class="text-foreground font-medium">{{ value?.en || value }}</span>
              <span class="text-sm text-muted-foreground" dir="rtl">{{ value?.ar }}</span>
            </div>
          </template>
          <template #cell-unit_price="{ value }">
            <span class="text-foreground font-medium">QR {{ parseFloat(value || 0).toFixed(2) }}</span>
          </template>
          <template #cell-unit="{ value }">
            <Badge variant="secondary" class="text-xs">
              {{ value || 'pcs' }}
            </Badge>
          </template>
          <template #cell-is_active="{ value, row }">
            <div class="flex items-center gap-2">
              <Switch
                :key="`switch-${row.id}-${optimisticStatus[row.id] ?? value}`"
                :checked="Boolean(optimisticStatus[row.id] ?? value ?? false)"
                @update:checked="(checked) => handleStatusChange(row.id, checked)"
              />
              <span class="text-sm text-muted-foreground">
                {{ Boolean(optimisticStatus[row.id] ?? value ?? false) ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </template>
          <template #actions="{ row }">
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="icon" class="h-8 w-8 hover:bg-gray-100">
                  <HugeiconsIcon :name="Menu01Icon" class="h-4 w-4 text-gray-600" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="handleEdit(row)">Edit</DropdownMenuItem>
                <DropdownMenuItem @click="handleDelete(row)" class="text-red-600">Delete</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </template>
        </DataTable>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { HugeiconsIcon } from '@hugeicons/vue';
import {
  Menu01Icon,
  Add01Icon,
  Download01Icon,
  ArrowDown01Icon,
  Search01Icon,
  FilterHorizontalIcon,
} from '@hugeicons/core-free-icons';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import { Card } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Switch } from '@/Components/ui/switch';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/Components/ui/dropdown-menu';

const props = defineProps({
  services: {
    type: Object,
    default: () => ({
      data: [],
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0,
    }),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const searchQuery = ref(props.filters.search || '');
const activeFilter = ref(props.filters.is_active ?? 'all');

// Track optimistic status updates
const optimisticStatus = ref({});

// Debounced search function
const debouncedSearch = useDebounceFn((value) => {
  router.get(
    route('services.index'),
    {
      ...props.filters,
      search: value,
      page: 1,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
}, 300);

const columns = [
  { key: 'code', label: 'Code', sortable: true },
  { key: 'name', label: 'Service Name', sortable: true },
  { key: 'unit_price', label: 'Unit Price', sortable: true },
  { key: 'unit', label: 'Unit', sortable: true },
  { key: 'is_active', label: 'Status', sortable: true },
];

const currentPage = computed(() => props.services.current_page || 1);
const totalPages = computed(() => props.services.last_page || 1);
const pageSize = computed(() => props.services.per_page || 15);
const totalItems = computed(() => props.services.total || 0);

const handleSearch = (value) => {
  debouncedSearch(value);
};

const handleFilterStatus = (status) => {
  activeFilter.value = status;
  router.get(
    route('services.index'),
    {
      ...props.filters,
      is_active: status === 'all' ? null : status,
      page: 1,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handleSort = ({ column, direction }) => {
  router.get(
    route('services.index'),
    {
      ...props.filters,
      sort: column,
      direction,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handlePageChange = (page) => {
  router.get(
    route('services.index'),
    {
      ...props.filters,
      page,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handlePageSizeChange = (size) => {
  router.get(
    route('services.index'),
    {
      ...props.filters,
      per_page: size,
      page: 1,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handleAction = ({ action, row }) => {
  if (action === 'edit') {
    handleEdit(row);
  } else if (action === 'delete') {
    handleDelete(row);
  }
};

const handleCreate = () => {
  router.visit(route('services.create'));
};

const handleEdit = (row) => {
  router.visit(route('services.edit', row.id));
};

const handleDelete = (row) => {
  if (confirm(`Are you sure you want to delete "${row.name?.en || row.name}"?`)) {
    router.delete(route('services.destroy', row.id), {
      preserveScroll: true,
      onSuccess: () => {
        // Success message will come from the controller
      },
    });
  }
};

const handleImport = (type) => {
  console.log(`Import ${type}`);
  // Handle import logic
};

const handleStatusChange = (serviceId, isActive) => {
  const service = props.services.data.find(s => s.id === serviceId);
  if (!service) return;

  // Ensure boolean value
  const newStatus = Boolean(isActive);

  // Optimistically update the UI
  optimisticStatus.value[serviceId] = newStatus;

  router.patch(
    route('services.update', serviceId),
    {
      is_active: newStatus,
      // Include existing data to avoid validation errors
      name: {
        en: service.name?.en || '',
        ar: service.name?.ar || '',
      },
      unit_price: service.unit_price || 0,
      unit: service.unit || 'pcs',
      sort_order: service.sort_order || 0,
      code: service.code || null,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        // Clear optimistic update
        delete optimisticStatus.value[serviceId];
        // Force reload to ensure data is updated after redirect
        router.reload({ only: ['services'], preserveScroll: true });
      },
      onError: () => {
        // Revert optimistic update on error
        delete optimisticStatus.value[serviceId];
        // Reload to get correct state
        router.reload({ only: ['services'], preserveScroll: true });
      },
    }
  );
};
</script>

<style scoped>
</style>
