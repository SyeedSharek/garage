<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center">
      <div v-if="$slots.icon" class="mr-4">
        <slot name="icon"></slot>
      </div>
      <div>
        <h3 v-if="$slots.title" class="text-gray-500 text-sm font-medium">
          <slot name="title"></slot>
        </h3>
        <div class="text-3xl font-bold text-gray-800">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// No script needed for this simple component
</script>

<style scoped>
/* Scoped styles for the card if needed */
</style>
