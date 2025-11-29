<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Set Prices" subtitle="Review and adjust prices for order items" />
    </template>
    <template #default>
      <div class="p-4 space-y-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Set Prices</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Review and adjust prices for your order items</p>
          </div>
          <Button variant="outline" class="gap-2" @click="handleBack">
            <ChevronLeft class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Price Form -->
        <Card class="w-full">
          <CardHeader>
            <CardTitle>Order Pricing</CardTitle>
            <CardDescription>Set discount and tax for your order</CardDescription>
          </CardHeader>
          <CardContent class="p-6">
            <div v-if="cartItems.length === 0" class="text-center py-8 text-gray-500">
              <p>No items in cart</p>
            </div>
            <div v-else>
              <!-- Order Summary -->
              <div class="flex justify-end">
                <div class="w-full md:w-1/3 space-y-4">
                  <!-- Subtotal (Readonly) -->
                  <div class="flex justify-between items-center">
                    <Label class="text-base">Subtotal:</Label>
                    <span class="text-base font-medium">{{ formatCurrency(subtotal) }}</span>
                  </div>

                  <!-- Discount Field -->
                  <div class="flex justify-between items-center gap-4">
                    <Label for="discount" class="text-base">Discount:</Label>
                    <div class="flex items-center gap-2 w-48">
                      <Input
                        id="discount"
                        v-model.number="discount"
                        type="number"
                        step="0.01"
                        min="0"
                        :max="subtotal"
                        class="text-right"
                        placeholder="0.00"
                        @input="calculateTotal"
                      />
                      <span class="text-sm text-gray-500 whitespace-nowrap">QR</span>
                    </div>
                  </div>

                  <!-- Tax Field -->
                  <div class="flex justify-between items-center gap-4">
                    <Label for="tax" class="text-base">Tax:</Label>
                    <div class="flex items-center gap-2 w-48">
                      <Input
                        id="tax"
                        v-model.number="tax"
                        type="number"
                        step="0.01"
                        min="0"
                        class="text-right"
                        placeholder="0.00"
                        @input="calculateTotal"
                      />
                      <span class="text-sm text-gray-500 whitespace-nowrap">QR</span>
                    </div>
                  </div>

                  <!-- Total -->
                  <div class="flex justify-between items-center pt-2 border-t">
                    <Label class="text-lg font-semibold">Total:</Label>
                    <span class="text-lg font-bold">{{ formatCurrency(total) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4 pt-6">
          <Button variant="outline" @click="handleBack">
            Cancel
          </Button>
          <Button
            @click="handleContinue"
            :disabled="cartItems.length === 0"
            size="lg"
            class="px-4 py-2"
          >
            Continue
            <ChevronRight class="h-4 w-4" />
          </Button>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { useCartDB } from '@/composables/useIndexedDB';

const { cartItems: dbCartItems, getCartItems, isLoading } = useCartDB();
const cartItems = ref([]);
const customer = ref(null);
const discount = ref(0);
const tax = ref(0);

onMounted(async () => {
  // Redirect to create page since pricing is now merged there
  router.visit(route('orders.create'));
  return;

  // Check if cart data exists, if not redirect to create page
  const cartData = sessionStorage.getItem('order_cart');
  if (!cartData) {
    router.visit(route('orders.create'));
    return;
  }

  try {
    const parsed = JSON.parse(cartData);
    if (!parsed.items || parsed.items.length === 0) {
      router.visit(route('orders.create'));
      return;
    }
  } catch (e) {
    router.visit(route('orders.create'));
    return;
  }

  // First, try to load from sessionStorage (most recent)
  if (cartData) {
    try {
      const parsed = JSON.parse(cartData);
      if (parsed.items && parsed.items.length > 0) {
        cartItems.value = parsed.items.map(item => ({
          ...item,
          unit_price: parseFloat(item.unit_price) || 0,
          quantity: parseInt(item.quantity) || 1,
          amount: (parseInt(item.quantity) || 1) * (parseFloat(item.unit_price) || 0),
        }));
      }
    } catch (e) {
      console.error('Error parsing cart data from sessionStorage:', e);
    }
  }

  // If no items from sessionStorage, try IndexedDB
  if (cartItems.value.length === 0) {
    try {
      await getCartItems();
      if (dbCartItems.value && dbCartItems.value.length > 0) {
        cartItems.value = dbCartItems.value.map(item => ({
          ...item,
          unit_price: parseFloat(item.unit_price) || 0,
          quantity: parseInt(item.quantity) || 1,
          amount: (parseInt(item.quantity) || 1) * (parseFloat(item.unit_price) || 0),
        }));
      }
    } catch (e) {
      console.error('Error loading cart from IndexedDB:', e);
    }
  }

  // Load customer from sessionStorage
  if (customerData) {
    try {
      const parsed = JSON.parse(customerData);
      customer.value = parsed.customer || { id: parsed.customer_id };
    } catch (e) {
      console.error('Error parsing customer data:', e);
    }
  }
});

const getServiceName = (item) => {
  if (!item.name) return 'Service';
  if (typeof item.name === 'string') return item.name;
  if (item.name.en) return item.name.en;
  if (item.name.ar) return item.name.ar;
  return 'Service';
};

const updateItemTotal = (item) => {
  item.amount = (item.quantity || 1) * (item.unit_price || 0);
};

const subtotal = computed(() => {
  return cartItems.value.reduce((sum, item) => {
    return sum + (item.amount || 0);
  }, 0);
});

const total = computed(() => {
  const sub = subtotal.value;
  const disc = discount.value || 0;
  const taxAmount = tax.value || 0;
  return Math.max(0, sub - disc + taxAmount);
});

const calculateTotal = () => {
  // This is called when discount or tax changes
  // The computed property will automatically update
};

const formatCurrency = (amount) => {
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount || 0);
  return `QR ${formatted}`;
};

const handleBack = () => {
  router.visit(route('orders.create'));
};

const handleContinue = () => {
  if (cartItems.value.length === 0) {
    return;
  }

  // Update cart in sessionStorage with new prices
  const cartData = {
    items: cartItems.value.map(item => ({
      service_id: item.service_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
      amount: item.amount,
      name: item.name,
      unit: item.unit,
      code: item.code,
    })),
    subtotal: subtotal.value,
    discount: discount.value || 0,
    tax: tax.value || 0,
    total: total.value,
  };

  sessionStorage.setItem('order_cart', JSON.stringify(cartData));

  // Navigate to preview page
  router.visit(route('orders.create-preview'));
};
</script>

