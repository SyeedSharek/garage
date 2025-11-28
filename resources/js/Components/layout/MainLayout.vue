<template>
  <div class="flex h-screen bg-gray-50 font-sans antialiased overflow-hidden">
    <!-- Sidebar -->
    <slot name="sidebar"></slot>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <!-- Header -->
      <slot name="header"></slot>

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
        <div class="container mx-auto px-6 py-8 max-w-7xl">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
// Main layout component - provides structure for sidebar, header, and content
</script>

<style scoped>
/* Ensure smooth scrolling */
:deep(*) {
  scrollbar-width: thin;
  scrollbar-color: rgb(203 213 225) transparent;
}

:deep(*)::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

:deep(*)::-webkit-scrollbar-track {
  background: transparent;
}

:deep(*)::-webkit-scrollbar-thumb {
  background-color: rgb(203 213 225);
  border-radius: 3px;
}

:deep(*)::-webkit-scrollbar-thumb:hover {
  background-color: rgb(148 163 184);
}
</style>
