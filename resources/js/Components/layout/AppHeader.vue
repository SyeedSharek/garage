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
              <Search :size="20" class="text-gray-400" />
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
            <Bell :size="24" class="text-gray-500" />
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
            <MessageSquare :size="24" class="text-gray-500" />
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
            <Settings :size="24" class="text-gray-500" />
          </button>

          <!-- User Profile -->
          <div class="relative ml-2" ref="dropdownContainer">
            <button
              ref="dropdownButton"
              @click.stop="dropdownOpen = !dropdownOpen"
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
              <ChevronDown
                :size="16"
                class="hidden md:block text-gray-400"
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
                ref="dropdownMenu"
                @click.stop
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200"
              >
                <Link
                  :href="route('profile.edit')"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white transition-colors"
                  @click="dropdownOpen = false"
                >
                  <User :size="16" class="mr-2" />
                  Profile
                </Link>
                <a
                  href="#"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white transition-colors"
                  @click="dropdownOpen = false"
                >
                  <Settings :size="16" class="mr-2" />
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
                  <LogOut :size="16" class="mr-2" />
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Search, Bell, MessageSquare, Settings, ChevronDown, User, LogOut } from 'lucide-vue-next';

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
const dropdownContainer = ref(null);
const dropdownButton = ref(null);
const dropdownMenu = ref(null);
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

// Click outside handler
const handleClickOutside = (event) => {
  if (!dropdownOpen.value) return;

  if (
    dropdownContainer.value &&
    !dropdownContainer.value.contains(event.target)
  ) {
    dropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Scoped styles for the header if needed */
</style>
