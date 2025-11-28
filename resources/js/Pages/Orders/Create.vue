<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Create Order" subtitle="Create a new order" />
    </template>
    <template #default>
      <div class="p-4 md:p-6 space-y-4 md:space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create Order</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create a new order</p>
          </div>
          <Button variant="outline" class="gap-2" @click="handleCancel">
            <HugeiconsIcon :name="ArrowLeft01Icon" class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Main Content: Services and Cart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
          <!-- Left Side: Services List -->
          <div class="w-full">
            <Card class="w-full">
              <CardHeader>
                <CardTitle>Services</CardTitle>
                <CardDescription>Select services to add to your order</CardDescription>
              </CardHeader>
              <CardContent class="p-4 md:p-6">
                <!-- Search Services -->
                <div class="mb-4 relative">
                  <HugeiconsIcon :name="Search01Icon" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 z-10" />
                  <Input
                    v-model="serviceSearch"
                    placeholder="Search services..."
                    class="w-full pl-10"
                  />
                </div>
                <div class="space-y-2 md:space-y-3 max-h-[600px] overflow-y-auto pr-1">
                  <div
                    v-for="service in filteredServices"
                    :key="service.id"
                    class="flex items-center justify-between p-3 md:p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors gap-3"
                  >
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-semibold text-sm md:text-base text-gray-900 dark:text-gray-100">
                          {{ service.name.en }}
                        </h3>
                        <span v-if="service.code" class="text-xs text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">
                          {{ service.code }}
                        </span>
                      </div>
                      <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ service.name.ar }}
                      </p>
                      <p class="text-xs md:text-sm font-medium text-gray-900 dark:text-gray-100 mt-1.5 md:mt-2">
                        {{ service.formatted_price }} / {{ service.unit }}
                      </p>
                    </div>
                    <Button
                      @click="addToCart(service)"
                      class="ml-2 md:ml-4 gap-2 flex-shrink-0 tex-center"
                      size="sm"
                    >
                      <!-- <HugeiconsIcon :name="Add01Icon" class="h-4 w-4" /> -->
                      <span class="hidden md:inline">Add to cart</span>
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Right Side: Cart -->
          <div class="w-full">
            <Card class="w-full sticky top-6">
              <CardHeader class="pb-3">
                <CardTitle class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100 text-left">Cart</CardTitle>
                <CardDescription class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-left">
                  {{ cartItems.length }} {{ cartItems.length === 1 ? 'item' : 'items' }}
                </CardDescription>
              </CardHeader>
              <CardContent class="p-4 md:p-6">
                <div v-if="cartItems.length === 0" class="text-center py-8 md:py-12 text-gray-500">
                  <p class="text-sm md:text-base">Your cart is empty</p>
                  <p class="text-xs md:text-sm mt-2">Add services from the list</p>
                </div>
                <div v-else class="space-y-4">
                  <div
                    v-for="(item, index) in cartItems"
                    :key="index"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 relative"
                  >
                    <!-- Main Content: Justify Between -->
                    <div class="flex items-center justify-between gap-4">
                      <!-- Left Side: Product Info -->
                      <div class="flex-1 min-w-0">
                        <!-- Product Name -->
                        <h4 class="text-base md:text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">
                          {{ item.name.en }}
                        </h4>
                        <!-- Unit Price -->
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                          {{ item.formatted_price }} / {{ item.unit }}
                        </p>
                      </div>

                      <!-- Center: Quantity Selector -->
                      <div class="flex items-center gap-2 flex-shrink-0">
                        <Button
                          @click="decreaseQuantity(index)"
                          variant="outline"
                          size="sm"
                          class="h-9 w-9 p-0 rounded-md border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700"
                          :disabled="item.quantity <= 1"
                          :class="{ 'opacity-50 cursor-not-allowed': item.quantity <= 1 }"
                        >
                          <span class="text-base font-semibold text-gray-700 dark:text-gray-300">−</span>
                        </Button>
                        <div class="w-12 h-9 flex items-center justify-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md">
                          <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ item.quantity }}</span>
                        </div>
                        <Button
                          @click="increaseQuantity(index)"
                          variant="outline"
                          size="sm"
                          class="h-9 w-9 p-0 rounded-md border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                          <span class="text-base font-semibold text-gray-700 dark:text-gray-300">+</span>
                        </Button>
                      </div>

                      <!-- Right Side: Trash Icon -->
                      <Button
                        @click="removeFromCart(index)"
                        variant="ghost"
                        size="sm"
                        class="h-8 w-8 p-0 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md flex-shrink-0"
                        title="Remove from cart"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                      </Button>
                    </div>
                  </div>

                  <!-- Total Section -->
                  <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <div class="flex justify-between items-center mb-4">
                      <span class="font-bold text-base md:text-lg text-gray-900 dark:text-gray-100">Total:</span>
                      <span class="font-bold text-base md:text-lg text-gray-900 dark:text-gray-100">
                        {{ formatAmount(totalAmount) }}
                      </span>
                    </div>
                    <Button
                      @click="handleContinue"
                      :disabled="cartItems.length === 0"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-md"
                    >
                      Continue
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { HugeiconsIcon } from '@hugeicons/vue';
import { ArrowLeft01Icon, Add01Icon, Delete01Icon, ArrowRight01Icon, Search01Icon } from '@hugeicons/core-free-icons';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { useCartDB } from '@/composables/useIndexedDB';

const props = defineProps({
  services: {
    type: Array,
    default: () => [],
  },
});

const {
  cartItems,
  isLoading,
  loadCart,
  addToCart: addToCartDB,
  increaseQuantity: increaseQuantityDB,
  decreaseQuantity: decreaseQuantityDB,
  removeFromCart: removeFromCartDB,
} = useCartDB();

const serviceSearch = ref('');

// Load cart from IndexedDB on mount
onMounted(async () => {
  await loadCart();
});

const filteredServices = computed(() => {
  if (!serviceSearch.value) {
    return props.services;
  }
  const search = serviceSearch.value.toLowerCase();
  return props.services.filter(service =>
    service.name.en?.toLowerCase().includes(search) ||
    service.name.ar?.toLowerCase().includes(search) ||
    service.code?.toLowerCase().includes(search)
  );
});

const addToCart = async (service) => {
  await addToCartDB(service);
};

const removeFromCart = async (index) => {
  await removeFromCartDB(index);
};

const increaseQuantity = async (index) => {
  await increaseQuantityDB(index);
};

const decreaseQuantity = async (index) => {
  await decreaseQuantityDB(index);
};

const totalAmount = computed(() => {
  return cartItems.value.reduce((total, item) => {
    return total + (item.unit_price * item.quantity);
  }, 0);
});

const formatAmount = (amount) => {
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount);
  return `QR ${formatted}`;
};

const handleCancel = () => {
  router.visit(route('orders.index'));
};

const handleContinue = () => {
  if (cartItems.value.length === 0) {
    return;
  }

  // Store cart items in sessionStorage to pass to next step
  sessionStorage.setItem('order_cart', JSON.stringify({
    items: cartItems.value.map(item => ({
      service_id: item.service_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
      code: item.code,
      name: item.name,
      unit: item.unit,
    })),
    subtotal: totalAmount.value,
  }));

  // Navigate to order details/continue page
  // You can create a route like: Route::get('orders/create/details', [OrderController::class, 'createDetails'])
  router.visit('/orders/create/details');
};
</script>

