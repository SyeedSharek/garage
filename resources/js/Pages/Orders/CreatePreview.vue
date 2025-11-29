<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Order Preview" subtitle="Review all order details before finalizing" />
    </template>
    <template #default>
      <div class="p-4 space-y-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Order Preview</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Review all details before finalizing your order</p>
          </div>
          <Button variant="outline" class="gap-2" @click="handleBack">
            <ChevronLeft class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Customer Information -->
        <Card v-if="customer" class="w-full">
          <CardHeader>
            <CardTitle>Customer Information</CardTitle>
          </CardHeader>
          <CardContent class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label class="text-sm text-gray-500">Name</Label>
                <p class="text-base font-medium">{{ customer.name }}</p>
              </div>
              <div v-if="customer.phone">
                <Label class="text-sm text-gray-500">Phone</Label>
                <p class="text-base font-medium">{{ customer.phone }}</p>
              </div>
              <div v-if="customer.email">
                <Label class="text-sm text-gray-500">Email</Label>
                <p class="text-base font-medium">{{ customer.email }}</p>
              </div>
              <div v-if="customer.company_name">
                <Label class="text-sm text-gray-500">Company</Label>
                <p class="text-base font-medium">{{ customer.company_name }}</p>
              </div>
              <div v-if="customer.address">
                <Label class="text-sm text-gray-500">Address</Label>
                <p class="text-base font-medium">{{ customer.address }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Order Items -->
        <Card class="w-full">
          <CardHeader>
            <CardTitle>Order Items</CardTitle>
            <CardDescription>Services and items included in this order</CardDescription>
          </CardHeader>
          <CardContent class="p-6">
            <div v-if="cartItems.length === 0" class="text-center py-8 text-gray-500">
              <p>No items in order</p>
            </div>
            <div v-else class="space-y-4">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b">
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Service</th>
                      <th class="text-center py-3 px-4 font-semibold text-gray-700">Quantity</th>
                      <th class="text-right py-3 px-4 font-semibold text-gray-700">Unit Price</th>
                      <th class="text-right py-3 px-4 font-semibold text-gray-700">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(item, index) in cartItems"
                      :key="item.service_id || index"
                      class="border-b hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                      <td class="py-4 px-4">
                        <div>
                          <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ getServiceName(item) }}
                          </p>
                          <p v-if="item.code" class="text-sm text-gray-500">Code: {{ item.code }}</p>
                          <p v-if="item.unit" class="text-sm text-gray-500">Unit: {{ item.unit }}</p>
                        </div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <span class="text-gray-900 dark:text-gray-100">{{ item.quantity }}</span>
                      </td>
                      <td class="py-4 px-4 text-right">
                        <span class="text-gray-900 dark:text-gray-100">{{ formatCurrency(item.unit_price) }}</span>
                      </td>
                      <td class="py-4 px-4 text-right">
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ formatCurrency(item.amount) }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Order Summary -->
        <Card class="w-full">
          <CardHeader>
            <CardTitle>Order Summary</CardTitle>
          </CardHeader>
          <CardContent class="p-6">
            <div class="flex justify-end">
              <div class="w-full md:w-1/3 space-y-3">
                <div class="flex justify-between">
                  <Label class="text-base text-gray-600">Subtotal:</Label>
                  <span class="text-base font-medium">{{ formatCurrency(subtotal) }}</span>
                </div>
                <div v-if="discount > 0" class="flex justify-between">
                  <Label class="text-base text-gray-600">Discount:</Label>
                  <span class="text-base font-medium text-red-600">-{{ formatCurrency(discount) }}</span>
                </div>
                <div v-if="tax > 0" class="flex justify-between">
                  <Label class="text-base text-gray-600">Tax:</Label>
                  <span class="text-base font-medium">{{ formatCurrency(tax) }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t">
                  <Label class="text-lg font-semibold">Total:</Label>
                  <span class="text-lg font-bold">{{ formatCurrency(total) }}</span>
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
            @click="handleCreateOrder"
            :disabled="cartItems.length === 0 || processing"
            size="lg"
            class="px-4 py-2"
          >
            <span v-if="processing">Processing...</span>
            <span v-else>Process to Payment</span>
            <ChevronRight v-if="!processing" class="h-4 w-4" />
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
import { Label } from '@/Components/ui/label';
import { useCartDB } from '@/composables/useIndexedDB';

const { cartItems: dbCartItems, getCartItems } = useCartDB();
const cartItems = ref([]);
const customer = ref(null);
const discount = ref(0);
const tax = ref(0);
const processing = ref(false);

onMounted(async () => {
  // Check if customer data exists, if not redirect to details page
  const customerData = sessionStorage.getItem('order_customer');
  if (!customerData) {
    router.visit(route('orders.create-details'));
    return;
  }

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

  // Load cart items from sessionStorage
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
      discount.value = parseFloat(parsed.discount) || 0;
      tax.value = parseFloat(parsed.tax) || 0;
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

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 2,
  }).format(amount || 0);
};

const handleBack = () => {
  router.visit(route('orders.create-prices'));
};

const handleCreateOrder = () => {
  if (cartItems.value.length === 0 || !customer.value) {
    return;
  }

  processing.value = true;

  // Create order with items
  router.post(route('orders.store'), {
    customer_id: customer.value.id,
    order_date: new Date().toISOString().split('T')[0],
    items: cartItems.value.map(item => ({
      service_id: item.service_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
    })),
    subtotal: subtotal.value,
    discount: discount.value || 0,
    tax: tax.value || 0,
    total: total.value,
  }, {
    onSuccess: () => {
      // Clear cart from IndexedDB and sessionStorage
      sessionStorage.removeItem('order_cart');
      sessionStorage.removeItem('order_customer');
      // Redirect will be handled by the backend
    },
    onError: () => {
      processing.value = false;
    },
  });
};
</script>

