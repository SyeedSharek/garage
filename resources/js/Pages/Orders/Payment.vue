<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Payment Selection" subtitle="Select payment method for your order" />
    </template>
    <template #default>
      <div class="p-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Payment Selection</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Order #{{ order.order_number }}</p>
          </div>
          <Button variant="outline" class="gap-2" @click.prevent="handleBack">
            <ChevronLeft class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Side: Order Summary -->
          <div class="space-y-6">
            <Card class="w-full">
              <CardHeader>
                <CardTitle>Order Summary</CardTitle>
                <CardDescription>Review your order details</CardDescription>
              </CardHeader>
              <CardContent class="p-6">
                <!-- Customer Info -->
                <div v-if="order.customer" class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                  <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100 mb-2">Customer</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.customer.name }}</p>
                  <p v-if="order.customer.phone" class="text-sm text-gray-600 dark:text-gray-400">
                    {{ order.customer.phone }}
                  </p>
                </div>

                <!-- Order Items -->
                <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                  <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100 mb-3">Items</h3>
                  <div class="space-y-2">
                    <div
                      v-for="item in order.items"
                      :key="item.id"
                      class="flex justify-between items-center text-sm"
                    >
                      <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                          {{ typeof item.service_name === 'string' ? item.service_name : item.service_name?.en || 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                          {{ item.quantity }} {{ item.unit }} × {{ formatCurrency(item.unit_price) }}
                        </p>
                      </div>
                      <span class="font-medium text-sm text-gray-900 dark:text-gray-100 ml-2">
                        {{ formatCurrency(item.amount) }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Order Totals -->
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ formatCurrency(order.subtotal) }}</span>
                  </div>
                  <div v-if="order.discount_amount > 0" class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                    <span class="font-medium text-red-600 dark:text-red-400">-{{ formatCurrency(order.discount_amount) }}</span>
                  </div>
                  <div v-if="order.tax_amount > 0" class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Tax:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ formatCurrency(order.tax_amount) }}</span>
                  </div>
                  <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-base font-semibold text-gray-900 dark:text-gray-100">Total:</span>
                    <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                      {{ formatCurrency(order.total_amount) }}
                    </span>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Right Side: Payment Method Selection and Amount -->
          <div class="space-y-6">
            <Card class="w-full">
              <CardHeader>
                <CardTitle>Payment Details</CardTitle>
                <CardDescription>Select payment method and enter payment amount</CardDescription>
              </CardHeader>
              <CardContent class="p-6">
                <div class="space-y-6">
                  <!-- Payment Method Options -->
                  <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Select Payment Method</h3>
                    <div class="space-y-3">
                      <div
                        v-for="type in paymentTypes"
                        :key="type.value"
                        @click="selectedPaymentType = type.value"
                        :class="[
                          'relative px-4 py-3 rounded-lg border-2 cursor-pointer transition-all duration-200',
                          selectedPaymentType === type.value
                            ? 'border-primary bg-primary/5 dark:bg-primary/10'
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800/50'
                        ]"
                      >
                        <div class="flex items-center gap-3">
                          <!-- Icon -->
                          <div
                            :class="[
                              'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center',
                              selectedPaymentType === type.value
                                ? 'bg-primary text-primary-foreground'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'
                            ]"
                          >
                            <CreditCard v-if="type.value === 'card'" class="h-5 w-5" />
                            <Banknote v-else-if="type.value === 'cash'" class="h-5 w-5" />
                            <Clock v-else-if="type.value === 'pay_later'" class="h-5 w-5" />
                            <CreditCard v-else class="h-5 w-5" />
                          </div>

                          <!-- Label -->
                          <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ type.label }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">
                              <span v-if="type.value === 'cash'">
                                Payment will be processed as cash. The invoice will be marked as paid upon confirmation.
                              </span>
                              <span v-else-if="type.value === 'card'">
                                Card payment will be processed. The invoice will remain pending until payment is confirmed.
                              </span>
                              <span v-else-if="type.value === 'pay_later'">
                                Invoice will be marked as due. Payment can be made later within the due date period.
                              </span>
                            </p>
                          </div>

                          <!-- Selection Indicator -->
                          <div
                            v-if="selectedPaymentType === type.value"
                            class="flex-shrink-0 w-5 h-5 rounded-full bg-primary border-2 border-primary flex items-center justify-center"
                          >
                            <Check class="h-3 w-3 text-primary-foreground" />
                          </div>
                          <div
                            v-else
                            class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600"
                          ></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Paid Amount Input (for all payment methods) -->
                  <div v-if="order.invoice" class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Payment Amount</h3>
                    <div class="space-y-4">
                      <!-- Invoice Summary -->
                      <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                          <span class="text-gray-600 dark:text-gray-400">Total Amount:</span>
                          <span class="font-medium text-gray-900 dark:text-gray-100">
                            {{ formatCurrency(order.invoice.total_amount) }}
                          </span>
                        </div>
                        <div class="flex justify-between text-sm">
                          <span class="text-gray-600 dark:text-gray-400">Already Paid:</span>
                          <span class="font-medium text-gray-900 dark:text-gray-100">
                            {{ formatCurrency(order.invoice.paid_amount || 0) }}
                          </span>
                        </div>
                        <div class="flex justify-between text-sm pt-2 border-t border-gray-200 dark:border-gray-700">
                          <span class="font-semibold text-gray-900 dark:text-gray-100">Remaining:</span>
                          <span class="font-bold text-gray-900 dark:text-gray-100">
                            {{ formatCurrency(remainingAmount) }}
                          </span>
                        </div>
                      </div>

                      <!-- Paid Amount Input -->
                      <div>
                        <label for="paid_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                          Paid Amount (QR)
                          <span v-if="selectedPaymentType === 'pay_later'" class="text-xs text-gray-500 dark:text-gray-400 ml-1">(Optional)</span>
                        </label>
                        <input
                          id="paid_amount"
                          v-model.number="paidAmount"
                          type="number"
                          step="0.01"
                          min="0"
                          :max="remainingAmount"
                          :disabled="selectedPaymentType === 'pay_later'"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-gray-100 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed"
                          placeholder="0.00"
                          @blur="validatePaidAmount"
                          @input="clearPaidAmountError"
                          @focus="handleInputFocus"
                        />
                        <p v-if="paidAmountError" class="mt-1 text-sm text-red-600 dark:text-red-400">
                          {{ paidAmountError }}
                        </p>
                        <div v-if="selectedPaymentType !== 'pay_later'" class="mt-2 flex gap-2">
                          <Button
                            variant="outline"
                            size="sm"
                            @click="handlePayFullAmount"
                          >
                            Pay Full Amount
                          </Button>
                          <Button
                            variant="outline"
                            size="sm"
                            @click="handleClearAmount"
                          >
                            Clear
                          </Button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
              <Button variant="outline" @click.prevent="handleBack">Cancel</Button>
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
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, CreditCard, Banknote, Clock, Check } from 'lucide-vue-next';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';

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
const paidAmount = ref(0);
const paidAmountError = ref('');

const remainingAmount = computed(() => {
  if (!props.order.invoice) return 0;
  const total = parseFloat(props.order.invoice.total_amount) || 0;
  const alreadyPaid = parseFloat(props.order.invoice.paid_amount) || 0;
  return Math.max(0, total - alreadyPaid);
});

const formatCurrency = (amount) => {
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount || 0);
  return `QR ${formatted}`;
};

const clearPaidAmountError = () => {
  // Clear error while typing
  paidAmountError.value = '';
};

const handleInputFocus = () => {
  // Don't reset on focus - just clear error if any
  paidAmountError.value = '';
};

const validatePaidAmount = () => {
  paidAmountError.value = '';

  // Convert to number if it's a string
  const amount = typeof paidAmount.value === 'string' ? parseFloat(paidAmount.value) || 0 : paidAmount.value;

  if (isNaN(amount) || amount < 0) {
    paidAmountError.value = 'Amount cannot be negative';
    paidAmount.value = 0;
    return;
  }

  if (amount > remainingAmount.value) {
    paidAmountError.value = `Amount cannot exceed remaining balance of ${formatCurrency(remainingAmount.value)}`;
    paidAmount.value = remainingAmount.value;
    return;
  }

  // Update the value to ensure it's a number
  paidAmount.value = amount;
};

const handleBack = () => {
  // If accessed via invoice, go back to invoice, otherwise go to order
  if (props.invoiceId) {
    router.visit(route('invoices.show', props.invoiceId), {
      preserveState: false,
      preserveScroll: false,
    });
  } else if (props.order?.id) {
    router.visit(route('orders.show', props.order.id), {
      preserveState: false,
      preserveScroll: false,
    });
  } else {
    router.visit(route('orders.index'), {
      preserveState: false,
      preserveScroll: false,
    });
  }
};

const handlePayFullAmount = () => {
  paidAmount.value = remainingAmount.value;
  paidAmountError.value = '';
};

const handleClearAmount = () => {
  paidAmount.value = 0;
  paidAmountError.value = '';
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

  // Validate paid amount for all payment methods
  if (props.order.invoice && selectedPaymentType.value !== 'pay_later') {
    if (!paidAmount.value || paidAmount.value <= 0) {
      paidAmountError.value = 'Please enter a valid payment amount';
      processing.value = false;
      return;
    }
    if (paidAmount.value > remainingAmount.value) {
      paidAmountError.value = `Amount cannot exceed remaining balance`;
      processing.value = false;
      return;
    }
  }

  // Process payment - update invoice payment type
  const amountToSend = selectedPaymentType.value !== 'pay_later'
    ? (typeof paidAmount.value === 'string' ? parseFloat(paidAmount.value) || 0 : paidAmount.value)
    : null;

  router.post(
    route(routeName, routeParam),
    {
      payment_type: selectedPaymentType.value,
      paid_amount: amountToSend,
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

// Watch for payment type changes and set default amount only if amount is 0
watch(selectedPaymentType, (newType) => {
  // Only set default amount if current amount is 0 or empty, and not pay_later
  if (newType !== 'pay_later' && props.order.invoice && (!paidAmount.value || paidAmount.value === 0)) {
    paidAmount.value = remainingAmount.value;
  } else if (newType === 'pay_later') {
    // Clear amount for pay later
    paidAmount.value = 0;
  }
});

onMounted(() => {
  // Set default payment type if invoice exists
  // Map payment_gateway to payment_type
  if (props.order.invoice?.payment_gateway) {
    selectedPaymentType.value = props.order.invoice.payment_gateway === 'cash' ? 'cash' : 'card';
  }

  // Set default paid amount to remaining amount for all payment methods (except pay_later)
  // Only if amount is not already set
  if (props.order.invoice && selectedPaymentType.value !== 'pay_later' && (!paidAmount.value || paidAmount.value === 0)) {
    paidAmount.value = remainingAmount.value;
  }
});
</script>

