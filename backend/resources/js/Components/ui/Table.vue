<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th
            v-for="header in headers"
            :key="header.value"
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
          >
            {{ header.text }}
          </th>
          <th v-if="$slots.actions" scope="col" class="relative px-6 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="item in items" :key="item.id">
          <td
            v-for="header in headers"
            :key="header.value"
            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
          >
            {{ item[header.value] }}
          </td>
          <td
            v-if="$slots.actions"
            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
          >
            <slot name="actions" :item="item"></slot>
          </td>
        </tr>
        <tr v-if="items.length === 0">
            <td :colspan="headers.length + ($slots.actions ? 1 : 0)" class="text-center py-4 text-gray-500">
                No data available
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  headers: {
    type: Array,
    required: true,
  },
  items: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
/* Scoped styles for the table if needed */
</style>
