<template>
  <Card class="w-full">
    <div class="space-y-4">
      <!-- Header Actions -->
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <Select
            v-model="localSelectedFilter"
            class="w-[180px]"
            @update:modelValue="handleFilterChange"
          >
            <SelectTrigger>
              <SelectValue placeholder="All Orders" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="option in filterOptions"
                :key="option.value"
                :value="option.value"
              >
                {{ option.label }}
              </SelectItem>
            </SelectContent>
          </Select>
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
              <DropdownMenuItem @click="handleImport">Import CSV</DropdownMenuItem>
              <DropdownMenuItem @click="handleImport">Import Excel</DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
          <Button variant="outline" class="gap-2 h-10">
            <HugeiconsIcon :name="AiMail01Icon" class="h-4 w-4" />
            Email segment
          </Button>
        </div>
      </div>

      <!-- Search and Filter Bar -->
      <div class="flex items-center gap-3">
        <div class="relative flex-1">
          <HugeiconsIcon
            :name="Search01Icon"
            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none"
          />
          <Input
            :model-value="searchQuery"
            :placeholder="searchPlaceholder"
            class="pl-10 h-10 bg-gray-50 border-gray-200"
            @input="handleSearchInput"
          />
        </div>
        <Button variant="outline" class="gap-2 h-10">
          <HugeiconsIcon :name="FilterHorizontalIcon" class="h-4 w-4" />
          Filters
        </Button>
      </div>
    </div>
  </Card>
</template>

<script setup>
import { ref, watch } from 'vue';
import { HugeiconsIcon } from '@hugeicons/vue';
import {
  ArrowDown01Icon,
  Search01Icon,
  FilterHorizontalIcon,
  Download01Icon,
  AiMail01Icon,
} from '@hugeicons/core-free-icons';
import { Card } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/Components/ui/dropdown-menu';

const props = defineProps({
  searchQuery: {
    type: String,
    default: '',
  },
  searchPlaceholder: {
    type: String,
    default: 'Search by name, email, or others...',
  },
  filterOptions: {
    type: Array,
    default: () => [
      { label: 'All Orders', value: 'all' },
      { label: 'Pending Orders', value: 'pending' },
      { label: 'Completed Orders', value: 'completed' },
    ],
  },
  selectedFilter: {
    type: [String, Number],
    default: null,
  },
});

const emit = defineEmits([
  'update:searchQuery',
  'update:selectedFilter',
  'search',
  'filter',
  'import',
]);

const localSelectedFilter = ref(props.selectedFilter || props.filterOptions[0]?.value || 'all');
const localSearchQuery = ref(props.searchQuery);

// Watch for external changes
watch(() => props.selectedFilter, (newValue) => {
  if (newValue !== null && newValue !== undefined) {
    localSelectedFilter.value = newValue;
  }
});

watch(() => props.searchQuery, (newValue) => {
  localSearchQuery.value = newValue;
});

const handleSearchInput = (event) => {
  const value = event.target.value;
  localSearchQuery.value = value;
  emit('update:searchQuery', value);
  emit('search', value);
};

const handleFilterChange = (value) => {
  localSelectedFilter.value = value;
  emit('update:selectedFilter', value);
  emit('filter', value);
};

const handleImport = () => {
  emit('import');
};
</script>

