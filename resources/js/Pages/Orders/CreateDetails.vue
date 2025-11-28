<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Select Customer" subtitle="Choose an existing customer or create a new one" />
    </template>
    <template #default>
      <div class="p-4 space-y-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Select Customer</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Choose a customer or create a new one</p>
          </div>
          <Button variant="outline" class="gap-2" @click="handleBack">
            <ChevronLeft class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Customer Type Selector -->
        <Card class="w-full">
          <CardContent class="p-6">
            <div class="flex items-center gap-4">
              <Label class="text-base font-medium">Customer Type:</Label>
              <Select v-model="customerType" class="w-1/2">
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="existing">Existing Customer</SelectItem>
                  <SelectItem value="new">New Customer</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </CardContent>
        </Card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <!-- Left Side: Customer Selection/Form -->
          <div class="space-y-4">
            <!-- Existing Customer Selection -->
            <Card v-if="customerType === 'existing'" class="w-full">
              <CardHeader>
                <CardTitle>Select Existing Customer</CardTitle>
                <CardDescription>Search and select from existing customers</CardDescription>
              </CardHeader>
              <CardContent class="p-6">
                <!-- Search Customers -->
                <div class="mb-4 relative">
                  <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 z-10" />
                  <Input
                    v-model="customerSearch"
                    placeholder="Search customers by name, phone, or email..."
                    class="w-full pl-10"
                  />
                </div>

                <!-- Customer List -->
                <div class="space-y-2 max-h-[500px] overflow-y-auto">
                  <div
                    v-for="customer in filteredCustomers"
                    :key="customer.id"
                    @click="selectCustomer(customer)"
                    class="p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                    :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedCustomer?.id === customer.id }"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ customer.name }}</h4>
                        <p v-if="customer.phone" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                          {{ customer.phone }}
                        </p>
                        <p v-if="customer.email" class="text-sm text-gray-500 dark:text-gray-400">
                          {{ customer.email }}
                        </p>
                        <p v-if="customer.company_name" class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                          {{ customer.company_name }}
                        </p>
                      </div>
                      <div v-if="selectedCustomer?.id === customer.id" class="ml-4">
                        <div class="h-5 w-5 rounded-full bg-blue-600 flex items-center justify-center">
                          <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-if="filteredCustomers.length === 0" class="text-center py-8 text-gray-500">
                    <p>No customers found</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- New Customer Form -->
            <Card v-if="customerType === 'new'" class="w-full">
              <CardHeader>
                <CardTitle>Create New Customer</CardTitle>
                <CardDescription>Fill in the details to create a new customer</CardDescription>
              </CardHeader>
              <CardContent class="p-6">
                  <form @submit.prevent="handleSubmit" class="space-y-4">
                    <!-- Customer Type - Full Width -->
                    <div class="space-y-2">
                      <Label>Customer Type</Label>
                      <Select v-model="form.type" @update:model-value="handleTypeChange">
                        <SelectTrigger>
                          <SelectValue placeholder="Select type" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="individual">Individual</SelectItem>
                          <SelectItem value="company">Company</SelectItem>
                        </SelectContent>
                      </Select>
                    </div>

                    <!-- Two Column Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- Name -->
                      <div class="space-y-2">
                        <Label for="name">Name <span class="text-red-500">*</span></Label>
                        <Input
                          id="name"
                          v-model="form.name"
                          placeholder="Enter customer name"
                          :class="errors.name ? 'border-red-500' : ''"
                          required
                        />
                        <p v-if="errors.name" class="text-sm text-red-600">{{ errors.name }}</p>
                      </div>

                      <!-- Company Name (if type is company) -->
                      <div v-if="form.type === 'company'" class="space-y-2">
                        <Label for="company_name">Company Name</Label>
                        <Input
                          id="company_name"
                          v-model="form.company_name"
                          placeholder="Enter company name"
                        />
                      </div>

                      <!-- Phone -->
                      <div class="space-y-2">
                        <Label for="phone">Phone</Label>
                        <Input
                          id="phone"
                          v-model="form.phone"
                          placeholder="Enter phone number"
                          type="tel"
                        />
                      </div>

                      <!-- Email -->
                      <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                          id="email"
                          v-model="form.email"
                          placeholder="Enter email address"
                          type="email"
                        />
                      </div>

                      <!-- Address -->
                      <div class="space-y-2">
                        <Label for="address">Address</Label>
                        <Input
                          id="address"
                          v-model="form.address"
                          placeholder="Enter address"
                        />
                      </div>

                      <!-- City -->
                      <div class="space-y-2">
                        <Label for="city">City</Label>
                        <Input
                          id="city"
                          v-model="form.city"
                          placeholder="Enter city"
                        />
                      </div>

                      <!-- CR Number (if type is company) -->
                      <div v-if="form.type === 'company'" class="space-y-2">
                        <Label for="cr_number">CR Number</Label>
                        <Input
                          id="cr_number"
                          v-model="form.cr_number"
                          placeholder="Enter CR number"
                        />
                      </div>

                      <!-- Tax Number (if type is company) -->
                      <div v-if="form.type === 'company'" class="space-y-2">
                        <Label for="tax_number">Tax Number</Label>
                        <Input
                          id="tax_number"
                          v-model="form.tax_number"
                          placeholder="Enter tax number"
                        />
                      </div>
                    </div>
                  </form>
              </CardContent>
            </Card>
          </div>

          <!-- Right Side: Pricing Section -->
          <div>
            <!-- Pricing Section -->
            <Card class="w-full">
              <CardHeader>
                <CardTitle>Order Pricing</CardTitle>
                <CardDescription>Set discount and tax for your order</CardDescription>
              </CardHeader>
              <CardContent class="p-6">
                <div class="flex justify-end">
                  <div class="w-full space-y-4">
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
                    <span class="text-sm text-gray-500 whitespace-nowrap">SAR</span>
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
                    <span class="text-sm text-gray-500 whitespace-nowrap">SAR</span>
                  </div>
                </div>

                <!-- Total -->
                <div class="flex justify-between items-center pt-2 border-t">
                  <Label class="text-lg font-semibold">Total:</Label>
                  <span class="text-lg font-bold">{{ formatCurrency(total) }}</span>
                </div>
              </div>
            </div>
          </CardContent>
            </Card>
          </div>
        </div>

        <!-- Continue Button -->
        <div class="flex justify-end pt-6">
          <Button
            @click="handleContinueForPrices"
            :disabled="!selectedCustomer"
            size="lg"
            class="px-4 py-2"
          >
            Continue for Preview
            <ChevronRight class="h-4 w-4" />
          </Button>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, Search, Plus } from 'lucide-vue-next';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select';

const props = defineProps({
  customers: {
    type: Array,
    default: () => [],
  },
});

const customerType = ref('existing'); // Default to existing customer
const customerSearch = ref('');
const selectedCustomer = ref(null);
const processing = ref(false);
const discount = ref(0);
const tax = ref(0);

const form = useForm({
  type: 'individual',
  name: '',
  company_name: '',
  phone: '',
  email: '',
  address: '',
  city: '',
  country: 'Qatar',
  cr_number: '',
  tax_number: '',
  is_active: true,
});

const errors = computed(() => form.errors);

// Check if cart data exists, if not redirect to create page
onMounted(() => {
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

    // Load existing discount and tax if available
    if (parsed.discount !== undefined) {
      discount.value = parseFloat(parsed.discount) || 0;
    }
    if (parsed.tax !== undefined) {
      tax.value = parseFloat(parsed.tax) || 0;
    }
  } catch (e) {
    router.visit(route('orders.create'));
  }
});

const subtotal = computed(() => {
  const cartData = sessionStorage.getItem('order_cart');
  if (!cartData) return 0;

  try {
    const parsed = JSON.parse(cartData);
    if (parsed.subtotal) {
      return parseFloat(parsed.subtotal) || 0;
    }
    // Calculate from items if subtotal not available
    if (parsed.items && parsed.items.length > 0) {
      return parsed.items.reduce((sum, item) => {
        return sum + ((parseFloat(item.quantity) || 1) * (parseFloat(item.unit_price) || 0));
      }, 0);
    }
  } catch (e) {
    return 0;
  }
  return 0;
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
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 2,
  }).format(amount || 0);
};

const filteredCustomers = computed(() => {
  if (!customerSearch.value) {
    return props.customers;
  }
  const search = customerSearch.value.toLowerCase();
  return props.customers.filter(customer =>
    customer.name?.toLowerCase().includes(search) ||
    customer.phone?.toLowerCase().includes(search) ||
    customer.email?.toLowerCase().includes(search) ||
    customer.company_name?.toLowerCase().includes(search)
  );
});

const selectCustomer = (customer) => {
  selectedCustomer.value = customer;
};

const handleTypeChange = () => {
  if (form.type === 'individual') {
    form.company_name = '';
    form.cr_number = '';
    form.tax_number = '';
  }
};

const handleSubmit = async () => {
  processing.value = true;

  form.post(route('customers.store'), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      // Reload the page to get updated customers list
      router.reload({
        only: ['customers'],
        onSuccess: () => {
          // Find the newly created customer by name and phone
          const newCustomer = props.customers.find(
            c => c.name === form.name && (!form.phone || c.phone === form.phone)
          );

          if (newCustomer) {
            selectCustomer(newCustomer);
            // Switch to existing customer view after creation
            customerType.value = 'existing';
          }

          // Reset form
          form.reset();
          form.type = 'individual';
          processing.value = false;
        },
      });
    },
    onError: () => {
      processing.value = false;
    },
  });
};

// Check if cart data exists, if not redirect to create page
onMounted(() => {
  const cartData = sessionStorage.getItem('order_cart');
  if (!cartData) {
    router.visit(route('orders.create'));
    return;
  }

  try {
    const parsed = JSON.parse(cartData);
    if (!parsed.items || parsed.items.length === 0) {
      router.visit(route('orders.create'));
    }
  } catch (e) {
    router.visit(route('orders.create'));
  }
});

const handleBack = () => {
  router.visit(route('orders.create'));
};

const handleContinueForPrices = () => {
  if (!selectedCustomer.value) {
    return;
  }

  // Get cart data to calculate subtotal
  const cartData = sessionStorage.getItem('order_cart');
  if (!cartData) {
    router.visit(route('orders.create'));
    return;
  }

  // Store customer selection in sessionStorage
  sessionStorage.setItem('order_customer', JSON.stringify({
    customer_id: selectedCustomer.value.id,
    customer: selectedCustomer.value,
  }));

  // Update cart with pricing information
  try {
    const parsed = JSON.parse(cartData);
    parsed.subtotal = subtotal.value;
    parsed.discount = discount.value || 0;
    parsed.tax = tax.value || 0;
    parsed.total = total.value;
    sessionStorage.setItem('order_cart', JSON.stringify(parsed));
  } catch (e) {
    console.error('Error updating cart data:', e);
  }

  // Navigate directly to preview page
  router.visit(route('orders.create-preview'));
};

const handleContinue = async () => {
  if (!selectedCustomer.value) {
    return;
  }

  // Get cart data from sessionStorage
  const cartData = sessionStorage.getItem('order_cart');
  if (!cartData) {
    router.visit(route('orders.create'));
    return;
  }

  const cart = JSON.parse(cartData);

  if (!cart.items || cart.items.length === 0) {
    router.visit(route('orders.create'));
    return;
  }

  // Create order with items
  router.post(route('orders.store'), {
    customer_id: selectedCustomer.value.id,
    order_date: new Date().toISOString().split('T')[0],
    items: cart.items.map(item => ({
      service_id: item.service_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
    })),
  }, {
    onSuccess: () => {
      // Clear cart from IndexedDB and sessionStorage
      sessionStorage.removeItem('order_cart');
      sessionStorage.removeItem('order_customer');
    },
  });
};
</script>

