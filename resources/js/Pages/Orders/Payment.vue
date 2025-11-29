<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Payment Selection" subtitle="Select payment method for your order" />
    </template>
    <template #default>
      <div class="p-4 space-y-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Payment Selection</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Order #{{ order.order_number }}</p>
          </div>
          <Button variant="outline" class="gap-2" @click="handleBack">
            <ChevronLeft class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Order Summary Card -->
        <Card class="w-full">
          <CardHeader>
            <CardTitle>Order Summary</CardTitle>
            <CardDescription>Review your order details</CardDescription>
          </CardHeader>
          <CardContent class="p-6">
            <!-- Customer Info -->
            <div v-if="order.customer" class="mb-4 pb-4 border-b">
              <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Customer</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.customer.name }}</p>
              <p v-if="order.customer.phone" class="text-sm text-gray-600 dark:text-gray-400">
                {{ order.customer.phone }}
              </p>
            </div>

            <!-- Order Items -->
            <div class="mb-4 pb-4 border-b">
              <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Items</h3>
              <div class="space-y-2">
                <div
                  v-for="item in order.items"
                  :key="item.id"
                  class="flex justify-between items-center text-sm"
                >
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-gray-100">
                      {{ typeof item.service_name === 'string' ? item.service_name : item.service_name?.en || 'N/A' }}
                    </p>
                    <p class="text-gray-500 dark:text-gray-400">
                      {{ item.quantity }} {{ item.unit }} × {{ formatCurrency(item.unit_price) }}
                    </p>
                  </div>
                  <span class="font-medium text-gray-900 dark:text-gray-100">
                    {{ formatCurrency(item.amount) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Order Totals -->
            <div class="space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                <span class="font-medium">{{ formatCurrency(order.subtotal) }}</span>
              </div>
              <div v-if="order.discount_amount > 0" class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                <span class="font-medium text-red-600">-{{ formatCurrency(order.discount_amount) }}</span>
              </div>
              <div v-if="order.tax_amount > 0" class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Tax:</span>
                <span class="font-medium">{{ formatCurrency(order.tax_amount) }}</span>
              </div>
              <div class="flex justify-between items-center pt-2 border-t">
                <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total:</span>
                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                  {{ formatCurrency(order.total_amount) }}
                </span>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Payment Selection Card -->
        <Card class="w-full">
          <CardHeader>
            <CardTitle>Select Payment Method</CardTitle>
            <CardDescription>Choose how you want to pay for this order</CardDescription>
          </CardHeader>
          <CardContent class="p-6">
            <div class="space-y-4">
              <div class="space-y-2">
                <Label for="payment_type" class="text-base font-medium">Payment Method</Label>
                <Select v-model="selectedPaymentType" id="payment_type">
                  <SelectTrigger>
                    <SelectValue placeholder="Select payment method" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem
                      v-for="type in paymentTypes"
                      :key="type.value"
                      :value="type.value"
                    >
                      {{ type.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <!-- Cash Payment Info -->
              <div v-if="selectedPaymentType === 'cash'" class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                  Payment will be processed as cash. The invoice will be marked as paid upon confirmation.
                </p>
              </div>

              <!-- Card Payment Info -->
              <div v-if="selectedPaymentType === 'card'" class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <p class="text-sm text-green-800 dark:text-green-200">
                  Card payment will be processed. The invoice will remain pending until payment is confirmed.
                </p>
              </div>

              <!-- Pay Later Payment Info -->
              <div v-if="selectedPaymentType === 'pay_later'" class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                  Invoice will be marked as due. Payment can be made later within the due date period.
                </p>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4 pt-4">
          <Button variant="outline" @click="handleBack">Cancel</Button>
          <Button
            @click="handleProcessPayment"
            :disabled="!selectedPaymentType || processing"
            size="lg"
            class="px-4 py-2"
          >
            <span v-if="processing">Processing...</span>
            <span v-else>Process Payment</span>
            <ChevronRight v-if="!processing" class="h-4 w-4 ml-2" />
          </Button>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';
import { Label } from '@/Components/ui/label';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select';

const props = defineProps({
  order: {
    type: Object,
    required: true,
  },
  paymentTypes: {
    type: Array,
    default: () => [],
  },
  invoiceId: {
    type: Number,
    default: null,
  },
});

const selectedPaymentType = ref('cash'); // Default to cash
const processing = ref(false);

const formatCurrency = (amount) => {
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount || 0);
  return `QR ${formatted}`;
};

const handleBack = () => {
  router.visit(route('orders.show', props.order.id));
};

const handleProcessPayment = () => {
  if (!selectedPaymentType.value) {
    return;
  }

  processing.value = true;

  // Use invoice route if invoiceId is available, otherwise use order route
  const routeName = props.invoiceId
    ? 'invoices.payment.process'
    : 'orders.payment.process';
  const routeParam = props.invoiceId || props.order.id;

  // Process payment - update invoice payment type
  router.post(
    route(routeName, routeParam),
    {
      payment_type: selectedPaymentType.value,
    },
    {
      onSuccess: () => {
        // Redirect to order show page
        router.visit(route('orders.show', props.order.id));
      },
      onError: (errors) => {
        processing.value = false;
        console.error('Payment processing error:', errors);
      },
    }
  );
};

onMounted(() => {
  // Set default payment type if invoice exists
  // Map payment_gateway to payment_type
  if (props.order.invoice?.payment_gateway) {
    selectedPaymentType.value = props.order.invoice.payment_gateway === 'cash' ? 'cash' : 'card';
  }
});
</script>

