<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Customers" subtitle="Manage your garage customers" />
    </template>
    <template #default>
      <div class="p-6 space-y-4">
        <!-- Page Header with Title and Actions -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-foreground">Customers</h1>
            <p class="text-sm text-muted-foreground mt-1">Manage and organize your garage customers</p>
          </div>
          <div class="flex items-center gap-3">
            <DropdownMenu>
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
            </DropdownMenu>
            <Button class="gap-2 h-10" @click="handleCreate">
              <HugeiconsIcon :name="Add01Icon" class="h-4 w-4" />
              Create Customer
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
                  placeholder="Search by name, phone, email, or company..."
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
                    All Customers
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="handleFilterStatus(true)">
                    Active Only
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="handleFilterStatus(false)">
                    Inactive Only
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem @click="handleFilterType('all')">
                    All Types
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="handleFilterType('individual')">
                    Individual
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="handleFilterType('company')">
                    Company
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
            <div v-if="activeFilter !== 'all' || activeTypeFilter !== 'all'" class="flex items-center gap-2 flex-wrap">
              <Badge v-if="activeFilter !== 'all'" variant="secondary" class="gap-1">
                <span v-if="activeFilter === true">Active</span>
                <span v-else>Inactive</span>
                <button @click="handleFilterStatus('all')" class="ml-1 hover:text-foreground text-muted-foreground">
                  ×
                </button>
              </Badge>
              <Badge v-if="activeTypeFilter !== 'all'" variant="secondary" class="gap-1">
                <span>{{ activeTypeFilter === 'individual' ? 'Individual' : 'Company' }}</span>
                <button @click="handleFilterType('all')" class="ml-1 hover:text-foreground text-muted-foreground">
                  ×
                </button>
              </Badge>
            </div>
          </div>
        </Card>

        <!-- Table Card -->
        <DataTable
          :columns="columns"
          :data="customers.data"
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
          <template #cell-name="{ value }">
            <span class="text-foreground font-medium">{{ value || '-' }}</span>
          </template>
          <template #cell-phone="{ value }">
            <span class="text-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-email="{ value }">
            <span class="text-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-type="{ value }">
            <Badge variant="secondary" class="text-xs">
              {{ value === 'company' ? 'Company' : 'Individual' }}
            </Badge>
          </template>
          <template #cell-company_name="{ value, row }">
            <span v-if="row.type === 'company'" class="text-foreground">{{ value || '-' }}</span>
            <span v-else class="text-muted-foreground">-</span>
          </template>
          <template #cell-is_active="{ value }">
            <Badge
              :variant="value ? 'default' : 'secondary'"
              class="text-xs font-medium"
            >
              {{ value ? 'Active' : 'Inactive' }}
            </Badge>
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
import { ref, computed } from 'vue';
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
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from '@/Components/ui/dropdown-menu';

const props = defineProps({
  customers: {
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
const activeTypeFilter = ref(props.filters.type ?? 'all');

// Debounced search function
const debouncedSearch = useDebounceFn((value) => {
  router.get(
    route('customers.index'),
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
  { key: 'name', label: 'Name', sortable: true },
  { key: 'phone', label: 'Phone', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'type', label: 'Type', sortable: true },
  { key: 'company_name', label: 'Company Name', sortable: true },
  { key: 'city', label: 'City', sortable: true },
  { key: 'is_active', label: 'Status', sortable: true },
];

const currentPage = computed(() => props.customers.current_page || 1);
const totalPages = computed(() => props.customers.last_page || 1);
const pageSize = computed(() => props.customers.per_page || 15);
const totalItems = computed(() => props.customers.total || 0);

const handleSearch = (value) => {
  debouncedSearch(value);
};

const handleFilterStatus = (status) => {
  activeFilter.value = status;
  router.get(
    route('customers.index'),
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

const handleFilterType = (type) => {
  activeTypeFilter.value = type;
  router.get(
    route('customers.index'),
    {
      ...props.filters,
      type: type === 'all' ? null : type,
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
    route('customers.index'),
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
    route('customers.index'),
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
    route('customers.index'),
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
  router.visit(route('customers.create'));
};

const handleEdit = (row) => {
  router.visit(route('customers.edit', row.id));
};

const handleDelete = (row) => {
  if (confirm(`Are you sure you want to delete "${row.name}"?`)) {
    router.delete(route('customers.destroy', row.id), {
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
</script>

<style scoped>
</style>

