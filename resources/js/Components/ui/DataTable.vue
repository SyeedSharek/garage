<template>
  <div class="w-full space-y-4">
    <!-- Tabs -->
    <div v-if="tabs && tabs.length > 0" class="flex items-center justify-between border-b border-border">
      <div class="flex items-center gap-1">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          :class="
            cn(
              'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
              activeTab === tab.key
                ? 'border-primary text-primary'
                : 'border-transparent text-muted-foreground hover:text-foreground hover:border-border'
            )
          "
          @click="handleTabChange(tab.key)"
        >
          {{ tab.label }}
          <span v-if="tab.count" class="ml-1 text-muted-foreground">({{ tab.count }})</span>
        </button>
      </div>
      <div class="flex items-center gap-3">
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="outline" class="gap-2 h-9">
              Customize Columns
              <HugeiconsIcon :name="ArrowDown01Icon" class="h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem @click="handleCustomizeColumns">Column Settings</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
        <Button class="gap-2 h-9">
          <HugeiconsIcon :name="Add01Icon" class="h-4 w-4" />
          Add Section
        </Button>
      </div>
    </div>

    <!-- Table -->
    <Card class="w-full p-0">
      <div class="p-4 pb-0 sm:p-6 sm:pb-0">
        <div class="rounded-md border border-border bg-background overflow-hidden">
          <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
              <Table class="min-w-full">
            <TableHeader>
              <TableRow class="border-b border-border hover:bg-transparent bg-muted/30">
                <TableHead v-if="selectable || draggable" class="w-12 h-12">
                  <div class="flex items-center gap-2">
                    <HugeiconsIcon
                      v-if="draggable"
                      :name="Menu01Icon"
                      class="h-4 w-4 text-muted-foreground cursor-move"
                    />
                    <input
                      v-if="selectable"
                      type="checkbox"
                      class="h-4 w-4 cursor-pointer border-input rounded text-primary focus:ring-primary"
                      :checked="allSelected"
                      @change="handleSelectAll"
                    />
                  </div>
                </TableHead>
                <TableHead
                  v-for="column in columns"
                  :key="column.key"
                  :class="cn(
                    'h-12',
                    sortable && (column.sortable !== false) && 'cursor-pointer hover:bg-muted/50'
                  )"
                  @click="sortable && (column.sortable !== false) && handleSort(column.key)"
                >
                  <div class="flex items-center gap-2">
                    <span class="font-medium text-foreground">{{ column.label }}</span>
                    <div v-if="sortable && (column.sortable !== false)" class="flex flex-col -space-y-1">
                      <HugeiconsIcon
                        :name="ArrowUp01Icon"
                        :class="
                          cn(
                            'h-3 w-3',
                            sortColumn === column.key && sortDirection === 'asc'
                              ? 'text-foreground'
                              : 'text-muted-foreground'
                          )
                        "
                      />
                      <HugeiconsIcon
                        :name="ArrowDown01Icon"
                        :class="
                          cn(
                            'h-3 w-3',
                            sortColumn === column.key && sortDirection === 'desc'
                              ? 'text-foreground'
                              : 'text-muted-foreground'
                          )
                        "
                      />
                    </div>
                  </div>
                </TableHead>
                <TableHead v-if="showActions" class="w-12 h-12"></TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="(row, index) in data"
                :key="getRowKey(row, index)"
                class="border-b border-border hover:bg-muted/50 transition-colors"
              >
                <TableCell v-if="selectable || draggable" class="h-16">
                  <div class="flex items-center gap-2">
                    <HugeiconsIcon
                      v-if="draggable"
                      :name="Menu01Icon"
                      class="h-4 w-4 text-muted-foreground cursor-move"
                    />
                    <input
                      v-if="selectable"
                      type="checkbox"
                      class="h-4 w-4 cursor-pointer border-input rounded text-primary focus:ring-primary"
                      :checked="isRowSelected(getRowKey(row, index))"
                      @change="handleRowSelect(getRowKey(row, index))"
                    />
                  </div>
                </TableCell>
                <TableCell
                  v-for="column in columns"
                  :key="column.key"
                  class="h-16"
                >
                  <slot
                    :name="`cell-${column.key}`"
                    :row="row"
                    :value="row[column.key]"
                    :column="column"
                    :index="index"
                  >
                    <span class="text-gray-900">{{ row[column.key] }}</span>
                  </slot>
                </TableCell>
                <TableCell v-if="showActions" class="h-16">
                  <slot name="actions" :row="row" :index="index">
                    <DropdownMenu>
                      <DropdownMenuTrigger as-child>
                        <Button variant="ghost" size="icon" class="h-8 w-8 hover:bg-gray-100">
                          <HugeiconsIcon :name="Menu01Icon" class="h-4 w-4 text-gray-600" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem @click="handleAction('edit', row)">Edit</DropdownMenuItem>
                        <DropdownMenuItem @click="handleAction('delete', row)">Delete</DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </slot>
                </TableCell>
              </TableRow>
              <TableRow v-if="data.length === 0">
                <TableCell :colspan="getColspan()" class="text-center py-8 text-gray-500">
                  <slot name="empty">
                    {{ emptyMessage }}
                  </slot>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="showPagination" class="flex items-center justify-between px-6 py-4 border-t border-border bg-background">
        <div class="flex-1 text-sm text-gray-600 dark:text-gray-400">
          {{ selectedRows.length }} of {{ totalItems }} row(s) selected.
        </div>
        <div class="flex items-center space-x-6 lg:space-x-8">
          <div class="flex items-center space-x-2">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Rows per page</p>
            <Select
              v-model="localPageSize"
              @update:modelValue="handlePageSizeChange"
            >
              <SelectTrigger class="h-8 w-[70px]">
                <SelectValue :placeholder="localPageSize.toString()" />
              </SelectTrigger>
              <SelectContent side="top">
                <SelectItem
                  v-for="option in computedPageSizeOptions"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>
          <div class="flex items-center space-x-2">
            <Button
              variant="outline"
              size="icon"
              class="h-8 w-8 border-border text-foreground hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent"
              :disabled="currentPage === 1"
              @click="handleFirstPage"
            >
              <span class="sr-only">Go to first page</span>
              <HugeiconsIcon :name="ArrowLeftDoubleIcon" class="h-4 w-4" />
            </Button>
            <Button
              variant="outline"
              size="icon"
              class="h-8 w-8 border-border text-foreground hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent"
              :disabled="currentPage === 1"
              @click="handlePreviousPage"
            >
              <span class="sr-only">Go to previous page</span>
              <HugeiconsIcon :name="ArrowLeft01Icon" class="h-4 w-4" />
            </Button>
            <div class="flex items-center space-x-1">
              <template v-for="page in getVisiblePages()" :key="page">
                <Button
                  v-if="page !== '...'"
                  :variant="page === currentPage ? 'default' : 'outline'"
                  size="sm"
                  class="h-8 w-8"
                  :class="page === currentPage ? 'bg-primary text-primary-foreground hover:bg-primary/90 border-primary' : 'border-border text-foreground hover:bg-accent hover:text-accent-foreground'"
                  @click="handlePageChange(page)"
                >
                  {{ page }}
                </Button>
                <span v-else class="px-2 text-muted-foreground">...</span>
              </template>
            </div>
            <Button
              variant="outline"
              size="icon"
              class="h-8 w-8 border-border text-foreground hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent"
              :disabled="currentPage === totalPages"
              @click="handleNextPage"
            >
              <span class="sr-only">Go to next page</span>
              <HugeiconsIcon :name="ArrowRight01Icon" class="h-4 w-4" />
            </Button>
            <Button
              variant="outline"
              size="icon"
              class="h-8 w-8 border-border text-foreground hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent"
              :disabled="currentPage === totalPages"
              @click="handleLastPage"
            >
              <span class="sr-only">Go to last page</span>
              <HugeiconsIcon :name="ArrowRightDoubleIcon" class="h-4 w-4" />
            </Button>
          </div>
        </div>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { cn } from '@/lib/utils';
import { HugeiconsIcon } from '@hugeicons/vue';
import {
  ArrowDown01Icon,
  ArrowUp01Icon,
  ArrowLeft01Icon,
  ArrowRight01Icon,
  ArrowLeftDoubleIcon,
  ArrowRightDoubleIcon,
  Menu01Icon,
  Add01Icon,
} from '@hugeicons/core-free-icons';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/Components/ui/dropdown-menu';

const props = defineProps({
  columns: {
    type: Array,
    required: true,
  },
  data: {
    type: Array,
    default: () => [],
  },
  tabs: {
    type: Array,
    default: () => [],
  },
  activeTab: {
    type: String,
    default: null,
  },
  // Selection options
  selectable: {
    type: Boolean,
    default: true,
  },
  draggable: {
    type: Boolean,
    default: true,
  },
  // Sorting options
  sortable: {
    type: Boolean,
    default: true,
  },
  // Action column options
  showActions: {
    type: Boolean,
    default: true,
  },
  // Pagination options
  showPagination: {
    type: Boolean,
    default: true,
  },
  initialPageSize: {
    type: Number,
    default: 10,
  },
  currentPage: {
    type: Number,
    default: 1,
  },
  totalPages: {
    type: Number,
    default: 1,
  },
  totalItems: {
    type: Number,
    default: 0,
  },
  pageSizeOptions: {
    type: Array,
    default: () => [10, 20, 50, 100],
  },
  // Empty state
  emptyMessage: {
    type: String,
    default: 'No data available',
  },
  // Row key for v-for
  rowKey: {
    type: String,
    default: 'id',
  },
});

const emit = defineEmits([
  'tab-change',
  'sort',
  'page-change',
  'page-size-change',
  'row-select',
  'select-all',
  'action',
  'customize-columns',
  'add-section',
]);

const sortColumn = ref(null);
const sortDirection = ref('asc');
const localPageSize = ref(props.initialPageSize);
const selectedRows = ref([]);

const allSelected = computed(() => {
  return props.data.length > 0 && selectedRows.value.length === props.data.length;
});

const computedPageSizeOptions = computed(() => {
  return props.pageSizeOptions.map((size) => ({
    label: `${size}`,
    value: size,
  }));
});

const getVisiblePages = () => {
  const pages = [];
  const total = props.totalPages;
  const current = props.currentPage;

  if (total <= 7) {
    // Show all pages if 7 or fewer
    for (let i = 1; i <= total; i++) {
      pages.push(i);
    }
  } else {
    // Show first page, last page, current page, and pages around current
    if (current <= 3) {
      // Near the start
      for (let i = 1; i <= 5; i++) {
        pages.push(i);
      }
      pages.push('...');
      pages.push(total);
    } else if (current >= total - 2) {
      // Near the end
      pages.push(1);
      pages.push('...');
      for (let i = total - 4; i <= total; i++) {
        pages.push(i);
      }
    } else {
      // In the middle
      pages.push(1);
      pages.push('...');
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i);
      }
      pages.push('...');
      pages.push(total);
    }
  }

  return pages;
};

const getRowKey = (row, index) => {
  return row[props.rowKey] ?? index;
};

const getColspan = () => {
  let colspan = props.columns.length;
  if (props.selectable || props.draggable) colspan += 1;
  if (props.showActions) colspan += 1;
  return colspan;
};

const isRowSelected = (key) => {
  return selectedRows.value.includes(key);
};

const handleTabChange = (tabKey) => {
  emit('tab-change', tabKey);
};

const handleSort = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
  emit('sort', { column: sortColumn.value, direction: sortDirection.value });
};

const handlePageChange = (page) => {
  emit('page-change', page);
};

const handleFirstPage = () => {
  emit('page-change', 1);
};

const handlePreviousPage = () => {
  if (props.currentPage > 1) {
    emit('page-change', props.currentPage - 1);
  }
};

const handleNextPage = () => {
  if (props.currentPage < props.totalPages) {
    emit('page-change', props.currentPage + 1);
  }
};

const handleLastPage = () => {
  emit('page-change', props.totalPages);
};

const handlePageSizeChange = (value) => {
  localPageSize.value = value;
  emit('page-size-change', value);
};

const handleRowSelect = (key) => {
  if (selectedRows.value.includes(key)) {
    selectedRows.value = selectedRows.value.filter((k) => k !== key);
  } else {
    selectedRows.value.push(key);
  }
  emit('row-select', selectedRows.value);
};

const handleSelectAll = (event) => {
  if (event.target.checked) {
    selectedRows.value = props.data.map((row, index) => getRowKey(row, index));
  } else {
    selectedRows.value = [];
  }
  emit('select-all', selectedRows.value);
};

const handleAction = (action, row) => {
  emit('action', { action, row });
};

const handleCustomizeColumns = () => {
  emit('customize-columns');
};
</script>
