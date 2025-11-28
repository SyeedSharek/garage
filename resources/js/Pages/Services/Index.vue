<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Services" subtitle="Manage your garage services" />
    </template>
    <template #default>
      <div class="p-6">
        <DataTable
          :columns="columns"
          :data="tableData"
          :tabs="tabs"
          :active-tab="activeTab"
          :current-page="currentPage"
          :total-pages="totalPages"
          :total-items="totalItems"
          :initial-page-size="pageSize"
          @tab-change="handleTabChange"
          @sort="handleSort"
          @page-change="handlePageChange"
          @page-size-change="handlePageSizeChange"
          @action="handleAction"
          @add-section="handleAddSection"
        >
          <template #cell-status="{ value }">
            <div class="flex items-center gap-2">
              <div
                v-if="value === 'Done'"
                class="flex h-5 w-5 items-center justify-center rounded-full bg-green-100"
              >
                <HugeiconsIcon :name="CheckmarkCircle01Icon" class="h-3 w-3 text-green-600" />
              </div>
              <div
                v-else-if="value === 'In Process'"
                class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100"
              >
                <div class="h-3 w-3 animate-spin rounded-full border-2 border-blue-600 border-t-transparent"></div>
              </div>
              <span class="text-sm text-gray-900">{{ value }}</span>
            </div>
          </template>
          <template #cell-reviewer="{ value, row }">
            <Select
              v-if="value === 'Assign reviewer'"
              :model-value="value"
              :options="reviewerOptions"
              class="w-[160px]"
              placeholder="Assign reviewer"
            />
            <span v-else class="text-gray-900">{{ value }}</span>
          </template>
        </DataTable>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { HugeiconsIcon } from '@hugeicons/vue';
import { CheckmarkCircle01Icon } from '@hugeicons/core-free-icons';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import Select from '@/Components/ui/shadcn/Select.vue';

const props = defineProps({
  services: {
    type: Object,
    default: () => ({
      data: [],
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
    }),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const activeTab = ref('outline');

const tabs = [
  { key: 'outline', label: 'Outline' },
  { key: 'past-performance', label: 'Past Performance', count: 3 },
  { key: 'key-personnel', label: 'Key Personnel', count: 2 },
  { key: 'focus-documents', label: 'Focus Documents' },
];

const columns = [
  { key: 'header', label: 'Header', sortable: true },
  { key: 'section_type', label: 'Section Type', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'target', label: 'Target', sortable: true },
  { key: 'limit', label: 'Limit', sortable: true },
  { key: 'reviewer', label: 'Reviewer', sortable: false },
];

const tableData = ref([
  {
    id: 1,
    header: 'Cover page',
    section_type: 'Cover page',
    status: 'In Process',
    target: 18,
    limit: 5,
    reviewer: 'Eddie Lake',
  },
  {
    id: 2,
    header: 'Table of contents',
    section_type: 'Table of contents',
    status: 'Done',
    target: 29,
    limit: 24,
    reviewer: 'Eddie Lake',
  },
  {
    id: 3,
    header: 'Executive summary',
    section_type: 'Narrative',
    status: 'Done',
    target: 10,
    limit: 13,
    reviewer: 'Eddie Lake',
  },
  {
    id: 4,
    header: 'Technical approach',
    section_type: 'Narrative',
    status: 'Done',
    target: 27,
    limit: 23,
    reviewer: 'Jamik Tashpulatov',
  },
  {
    id: 5,
    header: 'Design',
    section_type: 'Narrative',
    status: 'In Process',
    target: 2,
    limit: 16,
    reviewer: 'Jamik Tashpulatov',
  },
  {
    id: 6,
    header: 'Capabilities',
    section_type: 'Narrative',
    status: 'In Process',
    target: 20,
    limit: 8,
    reviewer: 'Jamik Tashpulatov',
  },
  {
    id: 7,
    header: 'Integration with existing systems',
    section_type: 'Narrative',
    status: 'In Process',
    target: 19,
    limit: 21,
    reviewer: 'Jamik Tashpulatov',
  },
  {
    id: 8,
    header: 'Innovation and Advantages',
    section_type: 'Narrative',
    status: 'Done',
    target: 25,
    limit: 26,
    reviewer: 'Assign reviewer',
  },
  {
    id: 9,
    header: "Overview of EMR's Innovative Solutions",
    section_type: 'Technical content',
    status: 'Done',
    target: 7,
    limit: 23,
    reviewer: 'Assign reviewer',
  },
  {
    id: 10,
    header: 'Advanced Algorithms and Machine Learning',
    section_type: 'Narrative',
    status: 'Done',
    target: 30,
    limit: 28,
    reviewer: 'Assign reviewer',
  },
]);

const reviewerOptions = [
  { label: 'Eddie Lake', value: 'Eddie Lake' },
  { label: 'Jamik Tashpulatov', value: 'Jamik Tashpulatov' },
  { label: 'Assign reviewer', value: 'Assign reviewer' },
];

const currentPage = computed(() => props.services.current_page || 1);
const totalPages = computed(() => props.services.last_page || 7);
const pageSize = computed(() => props.services.per_page || 10);
const totalItems = computed(() => props.services.total || 68);

const handleTabChange = (tabKey) => {
  activeTab.value = tabKey;
  // Handle tab change logic
};

const handleSort = ({ column, direction }) => {
  router.get(
    route('services.index'),
    { sort: column, direction },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handlePageChange = (page) => {
  router.get(
    route('services.index'),
    { page },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handlePageSizeChange = (size) => {
  router.get(
    route('services.index'),
    { per_page: size, page: 1 },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handleAction = ({ action, row }) => {
  console.log(action, row);
  // Handle actions (edit, delete, etc.)
};

const handleAddSection = () => {
  console.log('Add section');
  // Handle add section
};
</script>

<style scoped>
</style>
