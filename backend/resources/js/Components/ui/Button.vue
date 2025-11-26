<template>
  <button :class="buttonClass" v-bind="$attrs">
    <slot></slot>
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary', // primary, secondary, danger
  },
});

const buttonClass = computed(() => {
  const base = 'px-4 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2';
  switch (props.variant) {
    case 'danger':
      return `${base} text-white bg-red-600 hover:bg-red-700 focus:ring-red-500`;
    case 'secondary':
      return `${base} text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-gray-500`;
    case 'primary':
    default:
      return `${base} text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500`;
  }
});
</script>