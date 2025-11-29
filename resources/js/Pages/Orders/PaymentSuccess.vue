<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Payment Successful" subtitle="Your payment has been processed successfully" />
    </template>
    <template #default>
      <div class="p-4">
        <div class="max-w-2xl mx-auto">
          <!-- Success Card -->
          <Card class="w-full">
            <CardContent class="p-8 text-center">
              <!-- Success Icon -->
              <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/20 mb-6">
                <CheckCircle class="h-10 w-10 text-green-600 dark:text-green-400" />
              </div>

              <!-- Success Message -->
              <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                Payment Processed Successfully!
              </h1>
              <p class="text-gray-600 dark:text-gray-400 mb-8">
                Your payment has been recorded and the invoice has been updated.
              </p>

              <!-- Invoice Details -->
              <div v-if="invoice" class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Invoice Details</h2>
                <div class="space-y-3 text-left">
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Invoice Number:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ invoice.invoice_number }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100 capitalize">{{ paymentMethod }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Amount:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ formatCurrency(invoice.total_amount) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Paid Amount:</span>
                    <span class="font-medium text-green-600 dark:text-green-400">{{ formatCurrency(invoice.paid_amount) }}</span>
                  </div>
                  <div v-if="invoice.status === 'paid'" class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                      Paid
                    </span>
                  </div>
                </div>
              </div>

              <!-- Order Details -->
              <div v-if="order" class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Order Details</h2>
                <div class="space-y-3 text-left">
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Order Number:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ order.order_number }}</span>
                  </div>
                  <div v-if="order.customer" class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Customer:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ order.customer.name }}</span>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Button
                  v-if="order"
                  @click="router.visit(route('orders.show', order.id))"
                  variant="outline"
                  class="w-full sm:w-auto"
                >
                  View Order
                </Button>
                <Button
                  v-if="invoice"
                  @click="router.visit(route('invoices.show', invoice.id))"
                  variant="outline"
                  class="w-full sm:w-auto"
                >
                  View Invoice
                </Button>
                <Button
                  @click="router.visit(route('orders.index'))"
                  class="w-full sm:w-auto"
                >
                  Back to Orders
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { CheckCircle } from 'lucide-vue-next';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';

const props = defineProps({
  invoice: {
    type: Object,
    default: null,
  },
  order: {
    type: Object,
    default: null,
  },
  paymentType: {
    type: String,
    default: 'cash',
  },
});

const paymentMethod = computed(() => {
  const methods = {
    cash: 'Cash',
    card: 'Card',
    pay_later: 'Pay Later',
  };
  return methods[props.paymentType] || 'Cash';
});

const formatCurrency = (amount) => {
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount || 0);
  return `QR ${formatted}`;
};
</script>

