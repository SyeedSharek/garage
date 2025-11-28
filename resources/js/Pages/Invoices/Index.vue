<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Invoices" subtitle="Manage your garage invoices" />
    </template>
    <template #default>
      <div class="p-6 space-y-4">
        <!-- Page Header with Title and Actions -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-foreground">Invoices</h1>
            <p class="text-sm text-muted-foreground mt-1">Manage and organize your garage invoices</p>
          </div>
          <div class="flex items-center gap-3">
            <!-- <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="outline" class="gap-2 h-10">
                  <HugeiconsIcon :name="Download01Icon" class="h-4 w-4 text-blue-600" />
                  Export
                  <HugeiconsIcon :name="ArrowDown01Icon" class="h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="handleExport('csv')">Export CSV</DropdownMenuItem>
                <DropdownMenuItem @click="handleExport('excel')">Export Excel</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu> -->
            <Button class="gap-2 h-10" @click="handleCreate">
              <HugeiconsIcon :name="Add01Icon" class="h-4 w-4" />
              Create Invoice
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
                  placeholder="Search by invoice number, order number, customer name, phone, or email..."
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
                    All Statuses
                  </DropdownMenuItem>
                  <DropdownMenuItem
                    v-for="status in statusOptions"
                    :key="status.value"
                    @click="handleFilterStatus(status.value)"
                  >
                    {{ status.label }}
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem @click="handleFilterGateway('all')">
                    All Payment Methods
                  </DropdownMenuItem>
                  <DropdownMenuItem
                    v-for="gateway in paymentGatewayOptions"
                    :key="gateway.value"
                    @click="handleFilterGateway(gateway.value)"
                  >
                    {{ gateway.label }}
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
            <div v-if="activeStatusFilter !== 'all' || activeGatewayFilter !== 'all'" class="flex items-center gap-2 flex-wrap">
              <Badge v-if="activeStatusFilter !== 'all'" variant="secondary" class="gap-1">
                <span>{{ getStatusLabel(activeStatusFilter) }}</span>
                <button @click="handleFilterStatus('all')" class="ml-1 hover:text-foreground text-muted-foreground">
                  ×
                </button>
              </Badge>
              <Badge v-if="activeGatewayFilter !== 'all'" variant="secondary" class="gap-1">
                <span>{{ getGatewayLabel(activeGatewayFilter) }}</span>
                <button @click="handleFilterGateway('all')" class="ml-1 hover:text-foreground text-muted-foreground">
                  ×
                </button>
              </Badge>
            </div>
          </div>
        </Card>

        <!-- Table Card -->
        <DataTable
          :columns="columns"
          :data="invoices.data"
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
          <template #cell-invoice_number="{ value }">
            <span class="font-mono text-sm font-medium text-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-order_number="{ value }">
            <span class="text-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-customer_name="{ value }">
            <span class="text-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-customer_phone="{ value }">
            <span class="text-foreground">{{ value || '-' }}</span>
          </template>
          <template #cell-payment_gateway="{ value, row }">
            <Badge variant="secondary" class="text-xs">
              {{ row.payment_gateway_label }}
            </Badge>
          </template>
          <template #cell-status="{ value, row }">
            <Badge
              :variant="getStatusVariant(value)"
              class="text-xs font-medium"
            >
              {{ row.status_label }}
            </Badge>
          </template>
          <template #cell-total_amount="{ value, row }">
            <span class="text-foreground font-medium">{{ row.formatted_total_amount || `QR ${parseFloat(value || 0).toFixed(2)}` }}</span>
          </template>
          <template #cell-paid_amount="{ value, row }">
            <span class="text-foreground">{{ row.formatted_paid_amount || `QR ${parseFloat(value || 0).toFixed(2)}` }}</span>
          </template>
          <template #cell-remaining_amount="{ value, row }">
            <span class="text-foreground font-medium" :class="parseFloat(value || 0) > 0 ? 'text-orange-600' : 'text-green-600'">
              {{ row.formatted_remaining_amount || `QR ${parseFloat(value || 0).toFixed(2)}` }}
            </span>
          </template>
          <template #cell-invoice_date="{ value }">
            <span class="text-foreground">{{ value ? formatDate(value) : '-' }}</span>
          </template>
          <template #cell-due_date="{ value }">
            <span class="text-foreground" :class="isOverdue(value) ? 'text-red-600 font-medium' : ''">
              {{ value ? formatDate(value) : '-' }}
            </span>
          </template>
          <template #actions="{ row }">
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="icon" class="h-8 w-8 hover:bg-gray-100">
                  <HugeiconsIcon :name="Menu01Icon" class="h-4 w-4 text-gray-600" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="handleView(row)">View</DropdownMenuItem>
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
  invoices: {
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
  statusOptions: {
    type: Array,
    default: () => [],
  },
  paymentGatewayOptions: {
    type: Array,
    default: () => [],
  },
});

const searchQuery = ref(props.filters.search || '');
const activeStatusFilter = ref(props.filters.status ?? 'all');
const activeGatewayFilter = ref(props.filters.payment_gateway ?? 'all');

// Debounced search function
const debouncedSearch = useDebounceFn((value) => {
  router.get(
    route('invoices.index'),
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
  { key: 'invoice_number', label: 'Invoice Number', sortable: true },
  { key: 'order_number', label: 'Order Number', sortable: true },
  { key: 'customer_name', label: 'Customer', sortable: true },
  { key: 'customer_phone', label: 'Phone', sortable: false },
  { key: 'payment_gateway', label: 'Payment Method', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'total_amount', label: 'Total', sortable: true },
  { key: 'paid_amount', label: 'Paid', sortable: true },
  { key: 'remaining_amount', label: 'Remaining', sortable: true },
  { key: 'invoice_date', label: 'Invoice Date', sortable: true },
  { key: 'due_date', label: 'Due Date', sortable: true },
];

const currentPage = computed(() => props.invoices.current_page || 1);
const totalPages = computed(() => props.invoices.last_page || 1);
const pageSize = computed(() => props.invoices.per_page || 15);
const totalItems = computed(() => props.invoices.total || 0);

const handleSearch = (value) => {
  debouncedSearch(value);
};

const handleFilterStatus = (status) => {
  activeStatusFilter.value = status;
  router.get(
    route('invoices.index'),
    {
      ...props.filters,
      status: status === 'all' ? null : status,
      page: 1,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handleFilterGateway = (gateway) => {
  activeGatewayFilter.value = gateway;
  router.get(
    route('invoices.index'),
    {
      ...props.filters,
      payment_gateway: gateway === 'all' ? null : gateway,
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
    route('invoices.index'),
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
    route('invoices.index'),
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
    route('invoices.index'),
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
  router.visit(route('invoices.create'));
};

const handleView = (row) => {
  router.visit(route('invoices.show', row.id));
};

const handleEdit = (row) => {
  router.visit(route('invoices.edit', row.id));
};

const handleDelete = (row) => {
  if (confirm(`Are you sure you want to delete invoice "${row.invoice_number}"?`)) {
    router.delete(route('invoices.destroy', row.id), {
      preserveScroll: true,
      onSuccess: () => {
        // Success message will come from the controller
      },
    });
  }
};

const handleExport = (type) => {
  console.log(`Export ${type}`);
  // Handle export logic
};

const getStatusLabel = (status) => {
  const statusOption = props.statusOptions.find(s => s.value === status);
  return statusOption?.label || status;
};

const getGatewayLabel = (gateway) => {
  const gatewayOption = props.paymentGatewayOptions.find(g => g.value === gateway);
  return gatewayOption?.label || gateway;
};

const getStatusVariant = (status) => {
  const variants = {
    'draft': 'secondary',
    'pending': 'secondary',
    'due': 'secondary',
    'paid': 'default',
    'partial': 'secondary',
    'overdue': 'secondary',
    'cancelled': 'secondary',
  };
  return variants[status] || 'secondary';
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const isOverdue = (dueDateString) => {
  if (!dueDateString) return false;
  const dueDate = new Date(dueDateString);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  dueDate.setHours(0, 0, 0, 0);
  return dueDate < today;
};
</script>

<style scoped>
</style>

