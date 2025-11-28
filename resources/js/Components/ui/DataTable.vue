<template>
  <div class="w-full space-y-4">
    <!-- Tabs -->
    <div v-if="tabs && tabs.length > 0" class="flex items-center justify-between border-b border-gray-200">
      <div class="flex items-center gap-1">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          :class="
            cn(
              'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
              activeTab === tab.key
                ? 'border-primary text-primary'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            )
          "
          @click="handleTabChange(tab.key)"
        >
          {{ tab.label }}
          <span v-if="tab.count" class="ml-1 text-gray-400">({{ tab.count }})</span>
        </button>
      </div>
      <div class="flex items-center gap-3">
        <DropdownMenu align="right">
          <template #trigger>
            <Button variant="outline" class="gap-2 h-9">
              Customize Columns
              <HugeiconsIcon :name="ArrowDown01Icon" class="h-4 w-4" />
            </Button>
          </template>
          <DropdownMenuItem @click="handleCustomizeColumns">Column Settings</DropdownMenuItem>
        </DropdownMenu>
        <Button class="gap-2 h-9">
          <HugeiconsIcon :name="Add01Icon" class="h-4 w-4" />
          Add Section
        </Button>
      </div>
    </div>

    <!-- Table -->
    <Card class="w-full p-0">
      <div class="p-4 pb-0">
        <div class="rounded-md border border-gray-200 bg-white overflow-hidden">
          <Table>
            <TableHeaderShadcn>
              <TableRow class="border-b border-gray-200 hover:bg-transparent bg-gray-50/30">
                <TableHead v-if="selectable || draggable" class="w-12 h-12">
                  <div class="flex items-center gap-2">
                    <HugeiconsIcon
                      v-if="draggable"
                      :name="Menu01Icon"
                      class="h-4 w-4 text-gray-400 cursor-move"
                    />
                    <input
                      v-if="selectable"
                      type="checkbox"
                      class="h-4 w-4 cursor-pointer border-gray-300 rounded text-primary focus:ring-primary"
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
                    sortable && (column.sortable !== false) && 'cursor-pointer hover:bg-gray-100/50'
                  )"
                  @click="sortable && (column.sortable !== false) && handleSort(column.key)"
                >
                  <div class="flex items-center gap-2">
                    <span class="font-medium text-gray-900">{{ column.label }}</span>
                    <div v-if="sortable && (column.sortable !== false)" class="flex flex-col -space-y-1">
                      <HugeiconsIcon
                        :name="ArrowUp01Icon"
                        :class="
                          cn(
                            'h-3 w-3',
                            sortColumn === column.key && sortDirection === 'asc'
                              ? 'text-gray-900'
                              : 'text-gray-400'
                          )
                        "
                      />
                      <HugeiconsIcon
                        :name="ArrowDown01Icon"
                        :class="
                          cn(
                            'h-3 w-3',
                            sortColumn === column.key && sortDirection === 'desc'
                              ? 'text-gray-900'
                              : 'text-gray-400'
                          )
                        "
                      />
                    </div>
                  </div>
                </TableHead>
                <TableHead v-if="showActions" class="w-12 h-12"></TableHead>
              </TableRow>
            </TableHeaderShadcn>
            <TableBody>
              <TableRow
                v-for="(row, index) in data"
                :key="getRowKey(row, index)"
                class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
              >
                <TableCell v-if="selectable || draggable" class="h-16">
                  <div class="flex items-center gap-2">
                    <HugeiconsIcon
                      v-if="draggable"
                      :name="Menu01Icon"
                      class="h-4 w-4 text-gray-400 cursor-move"
                    />
                    <input
                      v-if="selectable"
                      type="checkbox"
                      class="h-4 w-4 cursor-pointer border-gray-300 rounded text-primary focus:ring-primary"
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
                    <DropdownMenu align="right">
                      <template #trigger>
                        <Button variant="ghost" size="icon" class="h-8 w-8 hover:bg-gray-100">
                          <HugeiconsIcon :name="Menu01Icon" class="h-4 w-4 text-gray-600" />
                        </Button>
                      </template>
                      <DropdownMenuItem @click="handleAction('edit', row)">Edit</DropdownMenuItem>
                      <DropdownMenuItem @click="handleAction('delete', row)">Delete</DropdownMenuItem>
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

      <!-- Pagination -->
      <div v-if="showPagination" class="flex items-center justify-between pt-4 border-t border-gray-200 px-4 pb-4">
        <div class="text-sm text-gray-700">
          <span>{{ selectedRows.length }} of {{ totalItems }} row(s) selected.</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-700">Rows per page</span>
          <Select
            v-model="localPageSize"
            :options="computedPageSizeOptions"
            class="w-[80px]"
            @update:modelValue="handlePageSizeChange"
          />
        </div>
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
          <Button
            variant="outline"
            size="icon"
            class="h-9 w-9"
            :disabled="currentPage === 1"
            @click="handleFirstPage"
          >
            <HugeiconsIcon :name="ArrowLeftDoubleIcon" class="h-4 w-4" />
          </Button>
          <Button
            variant="outline"
            size="icon"
            class="h-9 w-9"
            :disabled="currentPage === 1"
            @click="handlePreviousPage"
          >
            <HugeiconsIcon :name="ArrowLeft01Icon" class="h-4 w-4" />
          </Button>
          <Button
            variant="outline"
            size="icon"
            class="h-9 w-9"
            :disabled="currentPage === totalPages"
            @click="handleNextPage"
          >
            <HugeiconsIcon :name="ArrowRight01Icon" class="h-4 w-4" />
          </Button>
          <Button
            variant="outline"
            size="icon"
            class="h-9 w-9"
            :disabled="currentPage === totalPages"
            @click="handleLastPage"
          >
            <HugeiconsIcon :name="ArrowRightDoubleIcon" class="h-4 w-4" />
          </Button>
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
import Table from '@/Components/ui/shadcn/Table.vue';
import TableHeaderShadcn from '@/Components/ui/shadcn/TableHeader.vue';
import TableBody from '@/Components/ui/shadcn/TableBody.vue';
import TableRow from '@/Components/ui/shadcn/TableRow.vue';
import TableHead from '@/Components/ui/shadcn/TableHead.vue';
import TableCell from '@/Components/ui/shadcn/TableCell.vue';
import Card from '@/Components/ui/shadcn/Card.vue';
import Button from '@/Components/ui/shadcn/Button.vue';
import Select from '@/Components/ui/shadcn/Select.vue';
import DropdownMenu from '@/Components/ui/shadcn/DropdownMenu.vue';
import DropdownMenuItem from '@/Components/ui/shadcn/DropdownMenuItem.vue';

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
