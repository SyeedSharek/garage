<template>
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="px-6 py-4">
      <div class="flex items-center justify-between gap-4">
        <!-- Left: Title and Subtitle -->
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold text-gray-900 truncate">{{ title || 'Dashboard' }}</h1>
          <p class="text-sm text-gray-500 mt-1 truncate">{{ subtitle || "Let's check your Garage today" }}</p>
        </div>

        <!-- Center: Search Bar -->
        <div class="flex-1 max-w-xl mx-8 hidden lg:block">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <HugeiconsIcon :icon="Search01Icon" :size="20" color="rgb(156 163 175)" />
            </div>
            <input
              type="text"
              :placeholder="searchPlaceholder || 'Search...'"
              class="w-full pl-10 pr-20 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-white transition-all"
            />
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
              <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-300 rounded">
                ⌘ K
              </kbd>
            </div>
          </div>
        </div>

        <!-- Right: Icons and Profile -->
        <div class="flex items-center space-x-2 flex-shrink-0">
          <!-- Notifications -->
          <button
            class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
            @click="toggleNotifications"
            title="Notifications"
          >
            <HugeiconsIcon :icon="Notification03Icon" :size="24" color="currentColor" />
            <span
              v-if="notificationCount > 0"
              class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"
            ></span>
          </button>

          <!-- Messages -->
          <button
            class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
            @click="toggleMessages"
            title="Messages"
          >
            <HugeiconsIcon :icon="Message01Icon" :size="24" color="currentColor" />
            <span
              v-if="messageCount > 0"
              class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"
            ></span>
          </button>

          <!-- Settings -->
          <button
            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
            @click="toggleSettings"
            title="Settings"
          >
            <HugeiconsIcon :icon="Settings01Icon" :size="24" color="currentColor" />
          </button>

          <!-- User Profile -->
          <div class="relative ml-2">
            <button
              @click="dropdownOpen = !dropdownOpen"
              class="flex items-center space-x-3 focus:outline-none hover:opacity-80 transition-opacity"
            >
              <div
                class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold shadow-sm"
              >
                {{ userInitials }}
              </div>
              <div class="hidden md:block text-left">
                <p class="text-sm font-medium text-gray-900">{{ userName }}</p>
                <p class="text-xs text-gray-500">{{ userRole }}</p>
              </div>
              <HugeiconsIcon
                :icon="ArrowDown01Icon"
                :size="16"
                color="rgb(156 163 175)"
                class="hidden md:block"
              />
            </button>

            <!-- Dropdown Menu -->
            <Transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="dropdownOpen"
                v-click-outside="() => (dropdownOpen = false)"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200"
              >
                <Link
                  :href="route('profile.edit')"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white transition-colors"
                  @click="dropdownOpen = false"
                >
                  <HugeiconsIcon :icon="User02Icon" :size="16" color="currentColor" class="mr-2" />
                  Profile
                </Link>
                <a
                  href="#"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white transition-colors"
                  @click="dropdownOpen = false"
                >
                  <HugeiconsIcon :icon="Settings01Icon" :size="16" color="currentColor" class="mr-2" />
                  Settings
                </a>
                <hr class="my-1 border-gray-200" />
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white transition-colors"
                  @click="dropdownOpen = false"
                >
                  <HugeiconsIcon :icon="Logout01Icon" :size="16" color="currentColor" class="mr-2" />
                  Logout
                </Link>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { HugeiconsIcon } from '@hugeicons/vue';
import {
  Search01Icon,
  Notification03Icon,
  Message01Icon,
  Settings01Icon,
  ArrowDown01Icon,
  User02Icon,
  Logout01Icon,
} from '@hugeicons/core-free-icons';

const props = defineProps({
  title: {
    type: String,
    default: '',
  },
  subtitle: {
    type: String,
    default: '',
  },
  searchPlaceholder: {
    type: String,
    default: '',
  },
});

const page = usePage();
const dropdownOpen = ref(false);

const notificationCount = ref(3);
const messageCount = ref(2);

const userName = computed(() => page.props.auth?.user?.name || 'User');
const userRole = computed(() => 'Owner');
const userInitials = computed(() => {
  const name = userName.value;
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
});

const toggleNotifications = () => {
  console.log('Toggle notifications');
};

const toggleMessages = () => {
  console.log('Toggle messages');
};

const toggleSettings = () => {
  console.log('Toggle settings');
};

// Click outside directive
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};
</script>

<style scoped>
/* Scoped styles for the header if needed */
</style>
