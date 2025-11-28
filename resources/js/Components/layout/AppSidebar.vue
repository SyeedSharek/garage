<template>
  <aside
    class="flex flex-col bg-white border-r border-gray-200 transition-all duration-300 ease-in-out shadow-sm"
    :class="isCollapsed ? 'w-20' : 'w-64'"
  >
    <!-- Logo/Brand Section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
      <div v-if="!isCollapsed" class="flex items-center space-x-2">
        <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center">
          <span class="text-white font-bold text-sm">G</span>
        </div>
        <span class="font-bold text-lg text-gray-900">Garage</span>
      </div>
      <button
        @click="toggleCollapse"
        class="p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors"
      >
        <HugeiconsIcon :icon="Menu01Icon" :size="20" color="currentColor" />
      </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
      <Link
        v-for="item in navItems"
        :key="item.name"
        :href="item.href"
        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group"
        :class="{
          'justify-center': isCollapsed,
          'bg-primary text-white shadow-sm': item.active,
          'text-gray-700 hover:bg-gray-100 hover:text-primary': !item.active,
        }"
        :title="isCollapsed ? item.name : ''"
      >
        <HugeiconsIcon :icon="item.icon" :size="20" color="currentColor" class="flex-shrink-0" />
        <span v-if="!isCollapsed" class="ml-3">{{ item.name }}</span>
      </Link>
    </nav>

    <!-- Footer/Collapse Indicator -->
    <div v-if="!isCollapsed" class="p-4 border-t border-gray-200">
      <div class="text-xs text-gray-500 text-center">
        <p class="font-medium text-gray-700">Garage Management</p>
        <p class="mt-1">v1.0.0</p>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { HugeiconsIcon } from '@hugeicons/vue';
import {
  Menu01Icon,
  Home02Icon,
  GroupIcon,
  ClipboardIcon,
  ShoppingBagIcon,
  File01Icon,
} from '@hugeicons/core-free-icons';

const isCollapsed = ref(false);
const page = usePage();

const toggleCollapse = () => {
  isCollapsed.value = !isCollapsed.value;
};

const navItems = computed(() => [
  {
    name: 'Dashboard',
    href: route('dashboard'),
    icon: Home02Icon,
    active: page.url === route('dashboard'),
  },
  {
    name: 'Customers',
    href: route('customers.index'),
    icon: GroupIcon,
    active: page.url.startsWith('/customers'),
  },
  {
    name: 'Services',
    href: route('services.index'),
    icon: ClipboardIcon,
    active: page.url.startsWith('/services'),
  },
  // {
  //   name: 'Orders',
  //   href: route('orders.index'),
  //   icon: ShoppingBagIcon,
  //   active: page.url.startsWith('/orders'),
  // },
  {
    name: 'Invoices',
    href: route('invoices.index'),
    icon: File01Icon,
    active: page.url.startsWith('/invoices'),
  },
  {
    name: 'Reports',
    href: route('reports.index'),
    icon: File01Icon,
    active: page.url.startsWith('/reports'),
  },
]);
</script>

<style scoped>
/* Scoped styles for the sidebar if needed */
</style>
