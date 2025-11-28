<template>
  <div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <slot name="sidebar"></slot>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <slot name="header"></slot>

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
/* Scoped styles for the main layout if needed */
</style>
