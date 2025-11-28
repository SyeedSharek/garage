<template>
  <header class="bg-white shadow-md p-4 flex justify-between items-center">
    <!-- Logo and Search -->
    <div class="flex items-center space-x-4">
      <div class="text-2xl font-bold text-gray-800">
        GARAGE
      </div>
      <div class="relative hidden md:block">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 text-sm border-gray-200 rounded-md focus:border-blue-500 focus:ring-blue-500">
      </div>
    </div>

    <!-- Right side icons and user menu -->
    <div class="flex items-center space-x-5">
      <!-- Notifications Icon -->
      <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 01-3.46 0" />
        </svg>
      </button>

      <!-- User Profile Dropdown -->
      <div class="relative">
        <button @click="dropdownOpen = !dropdownOpen" class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
          <img class="h-full w-full object-cover" src="https://via.placeholder.com/150" alt="Your avatar">
        </button>

        <div v-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"></div>

        <transition
          enter-active-class="transition ease-out duration-100 transform"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="transition ease-in duration-75 transform"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div v-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-20">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">Profile</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">Settings</a>
            <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">Logout</Link>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const dropdownOpen = ref(false);
</script>

<style scoped>
/* Scoped styles for the header if needed */
</style>
