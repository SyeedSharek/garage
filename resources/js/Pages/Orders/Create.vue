<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Create Order" subtitle="Select services, customer, and review your order" />
    </template>
    <template #default>
      <div class="p-4 space-y-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create Order</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Select services, choose a customer, and review your order</p>
          </div>
          <div class="flex items-center gap-2">
            <Button
              variant="destructive"
              class="gap-2 bg-red-600 hover:bg-red-700 text-white"
              @click="handleResetForm"
            >
              <RotateCcw class="h-4 w-4" />
              Reset Form
            </Button>
          <Button variant="outline" class="gap-2" @click="handleCancel">
            <ChevronLeft class="h-4 w-4" />
            Back
          </Button>
        </div>
        </div>

        <!-- Customer Selection Section (Top, Compact) -->
            <Card class="w-full">
          <CardHeader class="pb-3">
            <div class="flex items-center justify-between">
              <div>
                <CardTitle class="text-lg">Customer</CardTitle>
                <CardDescription class="text-sm">Select or create a customer for this order</CardDescription>
              </div>
              <div class="flex gap-2">
                <Button
                  @click="customerType = 'existing'"
                  :variant="customerType === 'existing' ? 'default' : 'outline'"
                  size="sm"
                >
                  Existing Customer
                </Button>
                <Button
                  @click="customerType = 'new'"
                  :variant="customerType === 'new' ? 'default' : 'outline'"
                  size="sm"
                >
                  New Customer
                </Button>
              </div>
            </div>
          </CardHeader>
          <CardContent class="p-4">
            <!-- Existing Customer Selection -->
            <div v-if="customerType === 'existing'" class="space-y-3">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 z-10" />
                <Input
                  v-model="customerSearch"
                  placeholder="Search customers by name, phone, or email..."
                  class="w-full pl-10"
                  @input="saveCartToLocalStorage"
                />
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-[200px] overflow-y-auto">
                <div
                  v-for="customer in filteredCustomers"
                  :key="customer.id"
                  @click="selectCustomer(customer)"
                  class="p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                  :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 ring-2 ring-blue-200 dark:ring-blue-800': selectedCustomer?.id === customer.id }"
                >
                  <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                      <h4 class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate">{{ customer.name }}</h4>
                      <p v-if="customer.phone" class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                        {{ customer.phone }}
                      </p>
                      <p v-if="customer.email" class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ customer.email }}
                      </p>
                    </div>
                    <div v-if="selectedCustomer?.id === customer.id" class="flex-shrink-0">
                      <div class="h-5 w-5 rounded-full bg-blue-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="filteredCustomers.length === 0" class="col-span-full text-center py-4 text-gray-500">
                  <p class="text-sm">No customers found</p>
                </div>
              </div>
            </div>

            <!-- New Customer Form -->
            <div v-if="customerType === 'new'" class="space-y-4">
              <form @submit.prevent="handleSubmit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

                <div class="space-y-2">
                  <Label for="name">Name <span class="text-red-500">*</span></Label>
                  <Input
                    id="name"
                    v-model="form.name"
                    placeholder="Enter customer name"
                    :class="errors.name ? 'border-red-500' : ''"
                    required
                  />
                  <p v-if="errors.name" class="text-xs text-red-600">{{ errors.name }}</p>
                </div>

                <div v-if="form.type === 'company'" class="space-y-2">
                  <Label for="company_name">Company Name</Label>
                  <Input
                    id="company_name"
                    v-model="form.company_name"
                    placeholder="Enter company name"
                  />
                </div>

                <div class="space-y-2">
                  <Label for="phone">Phone</Label>
                  <Input
                    id="phone"
                    v-model="form.phone"
                    placeholder="Enter phone number"
                    type="tel"
                  />
                </div>

                <div class="space-y-2">
                  <Label for="email">Email</Label>
                  <Input
                    id="email"
                    v-model="form.email"
                    placeholder="Enter email address"
                    type="email"
                  />
                </div>

                <div class="space-y-2">
                  <Label for="address">Address</Label>
                  <Input
                    id="address"
                    v-model="form.address"
                    placeholder="Enter address"
                  />
                </div>

                <div class="space-y-2">
                  <Label for="city">City</Label>
                  <Input
                    id="city"
                    v-model="form.city"
                    placeholder="Enter city"
                  />
                </div>

                <div v-if="form.type === 'company'" class="grid grid-cols-2 gap-2">
                  <div class="space-y-2">
                    <Label for="cr_number">CR Number</Label>
                    <Input
                      id="cr_number"
                      v-model="form.cr_number"
                      placeholder="CR Number"
                    />
                  </div>
                  <div class="space-y-2">
                    <Label for="tax_number">Tax Number</Label>
                    <Input
                      id="tax_number"
                      v-model="form.tax_number"
                      placeholder="Tax Number"
                    />
                  </div>
                </div>

                <div class="flex items-end">
                  <Button type="submit" :disabled="processing" class="w-full">
                    <span v-if="processing">Creating...</span>
                    <span v-else>Create Customer</span>
                  </Button>
                </div>
              </form>
            </div>

            <!-- Selected Customer Display -->
            <div v-if="selectedCustomer && customerType === 'existing'" class="mt-3 pt-3 border-t">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Selected: {{ selectedCustomer.name }}</p>
                  <p v-if="selectedCustomer.phone" class="text-xs text-gray-500 dark:text-gray-400">{{ selectedCustomer.phone }}</p>
                </div>
                <Button
                  @click="selectedCustomer = null"
                  variant="ghost"
                  size="sm"
                  class="text-gray-500"
                >
                  Change
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Main Content: Services and Cart -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
          <!-- Left Column: Services List (2 columns) -->
          <div class="lg:col-span-2">
            <Card class="w-full h-full">
              <CardHeader>
                <CardTitle>Services</CardTitle>
                <CardDescription>Select services or add custom service to your order</CardDescription>
              </CardHeader>
              <CardContent class="p-4 md:p-6">
                <!-- Custom Service Form -->
                <div class="mb-4 p-3 md:p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                  <div class="flex items-center justify-between ">
                    <div>
                      <h3 class="font-semibold text-sm md:text-base text-gray-900 dark:text-gray-100">Other Service</h3>
                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Add a custom service to your order</p>
                    </div>
                    <Button
                      @click="showCustomServiceForm = !showCustomServiceForm"
                      variant="outline"
                      size="sm"
                      class="gap-2"
                    >
                      <Plus v-if="!showCustomServiceForm" class="h-4 w-4" />
                      <span v-if="!showCustomServiceForm">Add</span>
                      <span v-else>Hide</span>
                    </Button>
                  </div>
                  <div v-if="showCustomServiceForm" class="space-y-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2">
                      <Input
                        v-model="customService.name"
                        placeholder="Enter service name"
                        class="flex-1"
                        @keyup.enter="addCustomService"
                        @input="saveCartToLocalStorage"
                      />
                      <Button
                        @click="addCustomService"
                        :disabled="!customService.name"
                        class="gap-2"
                      >
                        <Plus class="h-4 w-4" />
                        Add
                      </Button>
                      <Button
                        @click="resetCustomServiceForm"
                        variant="outline"
                        class="gap-2"
                      >
                        Clear
                      </Button>
                    </div>
                  </div>
                </div>

                <!-- Search Services -->
                <div class="mb-4 relative">
                  <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 z-10" />
                  <Input
                    v-model="serviceSearch"
                    placeholder="Search services..."
                    class="w-full pl-10"
                    @input="saveCartToLocalStorage"
                  />
                </div>
                <div class="space-y-2 md:space-y-3 max-h-[calc(100vh-500px)] overflow-y-auto pr-1">
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
                      class="ml-2 md:ml-4 gap-2 flex-shrink-0"
                      size="sm"
                    >
                      <Plus class="h-4 w-4" />
                      <span class="hidden md:inline">Add</span>
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Right Column: Cart & Pricing (1 column, sticky) -->
          <div class="lg:col-span-1">
            <Card class="w-full sticky top-6">
              <CardHeader class="pb-3">
                <CardTitle class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">Cart</CardTitle>
                <CardDescription class="text-sm text-gray-500 dark:text-gray-400">
                  {{ cartItems?.length || 0 }} {{ (cartItems?.length || 0) === 1 ? 'item' : 'items' }}
                </CardDescription>
              </CardHeader>
              <CardContent class="p-4 md:p-6">
                <div v-if="!cartItems || cartItems.length === 0" class="text-center py-8 md:py-12 text-gray-500">
                  <p class="text-sm md:text-base">Your cart is empty</p>
                  <p class="text-xs md:text-sm mt-2">Add services from the list</p>
                  <p class="text-xs text-gray-400 mt-1">Items: {{ cartItems?.length || 0 }}</p>
                </div>
                <div v-else class="space-y-4">
                  <!-- Cart Items -->
                  <div class="space-y-3 max-h-[300px] overflow-y-auto">
                  <div
                    v-for="(item, index) in cartItems"
                    :key="`${item.id}-${index}`"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                  >
                    <!-- Header: Item Name and Delete Button -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                      <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate flex-1">
                        {{ typeof item.name === 'object' ? (item.name.en || item.name.ar) : (item.name || 'Unnamed Item') }}
                      </h4>
                      <Button
                        @click="removeFromCart(index)"
                        variant="ghost"
                        size="sm"
                        class="h-7 w-7 p-0 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex-shrink-0"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                      </Button>
                    </div>

                    <!-- Content: Price, Quantity, and Total -->
                    <div class="space-y-3">
                      <!-- Price Input -->
                      <div class="flex items-center gap-3">
                        <Label class="text-xs font-medium text-gray-600 dark:text-gray-400 w-14 flex-shrink-0">Price:</Label>
                        <div class="flex items-center gap-2 flex-1">
                          <Input
                            v-model="item.unit_price"
                            @update:model-value="(val) => updatePrice(index, val)"
                            @blur="(e) => { updatePrice(index, e.target.value || 0); saveCartToLocalStorage(); }"
                            type="number"
                            step="0.01"
                            min="0"
                            class="h-8 text-sm text-right font-medium"
                            placeholder="0.00"
                          />
                          <span class="text-xs text-gray-500 font-medium w-6">QR</span>
                        </div>
                      </div>

                      <!-- Quantity Controls -->
                      <div class="flex items-center gap-3 w-full">
                        <Label class="text-xs font-medium text-gray-600 dark:text-gray-400 w-14 flex-shrink-0">Qty:</Label>
                        <div class="flex items-center gap-1.5">
                          <Button
                            @click="decreaseQuantity(index)"
                            variant="outline"
                            size="sm"
                            class="h-8 w-8 p-0 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                            :disabled="item.quantity <= 1"
                          >
                            <span class="text-base font-medium">−</span>
                          </Button>
                          <Input
                            v-model="item.quantity"
                            @update:model-value="(val) => updateQuantityDirect(index, val)"
                            @blur="(e) => { updateQuantityDirect(index, e.target.value || 1); saveCartToLocalStorage(); }"
                            type="number"
                            step="1"
                            min="1"
                            class="h-8 w-32 text-center text-sm font-semibold"
                            placeholder="1"
                          />
                          <Button
                            @click="increaseQuantity(index)"
                            variant="outline"
                            size="sm"
                            class="h-8 w-8 p-0 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                          >
                            <span class="text-base font-medium">+</span>
                          </Button>
                        </div>
                      </div>


                    </div>
                  </div>
                      </div>

                  <!-- Pricing Section -->
                  <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                      <Label class="text-sm">Subtotal:</Label>
                      <span class="text-sm font-medium">{{ formatAmount(subtotal) }}</span>
                    </div>

                    <div class="flex justify-between items-center gap-2">
                      <Label for="discount" class="text-sm">Discount:</Label>
                      <div class="flex items-center gap-1 w-32">
                        <Input
                          id="discount"
                          v-model.number="discount"
                          @update:model-value="(val) => { discount = parseFloat(val) || 0; debouncedSaveCart(); }"
                          @blur="(e) => { discount = parseFloat(e.target.value) || 0; saveCartToLocalStorage(); }"
                          type="number"
                          step="0.01"
                          min="0"
                          :max="subtotal"
                          class="text-right h-8 text-sm"
                          placeholder="0.00"
                        />
                        <span class="text-xs text-gray-500">QR</span>
                    </div>
                  </div>

                    <div class="flex justify-between items-center gap-2">
                      <Label for="tax" class="text-sm">Tax:</Label>
                      <div class="flex items-center gap-1 w-32">
                        <Input
                          id="tax"
                          v-model.number="tax"
                          @update:model-value="(val) => { tax = parseFloat(val) || 0; debouncedSaveCart(); }"
                          @blur="(e) => { tax = parseFloat(e.target.value) || 0; saveCartToLocalStorage(); }"
                          type="number"
                          step="0.01"
                          min="0"
                          class="text-right h-8 text-sm"
                          placeholder="0.00"
                        />
                        <span class="text-xs text-gray-500">QR</span>
                    </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t">
                      <Label class="text-base font-semibold">Total:</Label>
                      <span class="text-base font-bold">{{ formatAmount(total) }}</span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>

        <!-- Validation Errors -->
        <div v-if="Object.keys(validationErrors).length > 0" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mt-6">
          <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</h3>
          <ul class="list-disc list-inside space-y-1">
            <li v-for="(error, field) in validationErrors" :key="field" class="text-sm text-red-600 dark:text-red-400">
              <strong>{{ formatFieldName(field) }}:</strong> {{ Array.isArray(error) ? error[0] : error }}
            </li>
          </ul>
        </div>

        <!-- Continue Button at Bottom of Page (Outside Cart Card) -->
        <div class="flex justify-end pt-6 mt-4">
          <Button
            @click="handleContinue"
            :disabled="cartItems.length === 0 || !selectedCustomer"
            size="lg"
            class="px-8 py-2"
          >
            Continue to Preview
            <ChevronRight class="h-4 w-4 ml-2" />
          </Button>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { ChevronLeft, ChevronRight, Search, Plus, RotateCcw } from 'lucide-vue-next';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';

const page = usePage();
// Use computed to make errors reactive to page props changes
const validationErrors = computed(() => {
  const errors = page.props.errors || {};
  console.log('Computed validationErrors:', errors);
  return errors;
});
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select';
const props = defineProps({
  services: {
    type: Array,
    default: () => [],
  },
  customers: {
    type: Array,
    default: () => [],
  },
});

// ============================================
// STATE MANAGEMENT
// ============================================
const cartItems = ref([]);

// Flag to track if localStorage has been loaded (only load once)
const hasLoadedFromLocalStorage = ref(false);

// Application state (loaded from localStorage)
const appState = ref({
  items: [],
  customer: null,
  customerType: 'existing',
  customerSearch: '',
  serviceSearch: '',
  discount: 0,
  tax: 0,
  showCustomServiceForm: false,
  customService: { name: '' },
});

// Debug functions to check localStorage (can be called from browser console)
if (typeof window !== 'undefined') {
  // Debug cart data
  window.debugCart = () => {
    const cartData = localStorage.getItem(CART_STORAGE_KEY);

    console.log('=== CART DEBUG ===');
    console.log('localStorage key:', CART_STORAGE_KEY);
    console.log('Raw data:', cartData);

    if (cartData) {
      try {
        const parsed = JSON.parse(cartData);
        console.log('Parsed data:', parsed);
        console.log('Items count:', parsed.items?.length || 0);
        console.log('Items:', parsed.items);
        console.log('Subtotal:', parsed.subtotal);
        console.log('Discount:', parsed.discount);
        console.log('Tax:', parsed.tax);
        console.log('Total:', parsed.total);
        console.log('Customer:', parsed.customer);
        console.log('Customer Type:', parsed.customerType);
        console.log('Searches:', { customer: parsed.customerSearch, service: parsed.serviceSearch });
      } catch (e) {
        console.error('Error parsing data:', e);
      }
    }

    console.log('Current cartItems.value:', cartItems.value);
    console.log('CartItems length:', cartItems.value.length);
    console.log('Current selectedCustomer:', selectedCustomer.value);

    return {
      localStorage: cartData ? JSON.parse(cartData) : null,
      cartItems: cartItems.value,
      selectedCustomer: selectedCustomer.value
    };
  };

  // Debug all localStorage data
  window.debugAllStorage = () => {
    const cartData = localStorage.getItem(CART_STORAGE_KEY);
    const customerData = localStorage.getItem('order_customer');

    console.log('=== ALL LOCALSTORAGE DEBUG ===');
    console.log('All Data (order_cart):', cartData ? JSON.parse(cartData) : null);
    console.log('Customer Data:', customerData ? JSON.parse(customerData) : null);
    console.log('Current cartItems:', cartItems.value);
    console.log('Current selectedCustomer:', selectedCustomer.value);

    return {
      allData: cartData ? JSON.parse(cartData) : null,
      customer: customerData ? JSON.parse(customerData) : null,
      currentCartItems: cartItems.value,
      currentCustomer: selectedCustomer.value
    };
  };

  // Force save cart
  window.forceSaveCart = () => {
    console.log('Force saving cart...');
    saveCartToLocalStorage();
    console.log('Cart saved!');
    return window.debugCart();
  };

  // Force reload cart from localStorage (for debugging - resets the flag)
  window.forceReloadCart = () => {
    console.log('Force reloading cart from localStorage...');
    hasLoadedFromLocalStorage.value = false; // Reset flag to allow reload
    loadCartFromLocalStorage();
    console.log('Cart reloaded!');
    return window.debugCart();
  };
}

const serviceSearch = ref('');
const customerSearch = ref('');
const customerType = ref('existing');
const selectedCustomer = ref(null);
const discount = ref(0);
const tax = ref(0);
const processing = ref(false);
const showCustomServiceForm = ref(false);
const customService = ref({ name: '' });
const isInitialLoad = ref(true);

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

// LocalStorage key (all data stored in one place)
const CART_STORAGE_KEY = 'order_cart';

// ============================================
// CART UTILITY FUNCTIONS
// ============================================

/**
 * Normalize service name to string
 */
const normalizeServiceName = (name) => {
  if (typeof name === 'object' && name !== null) {
    return name.en || name.ar || 'Unknown Service';
  }
  return name || 'Unknown Service';
};

/**
 * Format price to currency string (Qatar Rial - QR)
 */
const formatPrice = (price) => {
  const numPrice = parseFloat(price) || 0;
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(numPrice);
  return `QR ${formatted}`;
};

/**
 * Create a cart item from a service
 */
const createCartItem = (service) => {
  const unitPrice = parseFloat(service.unit_price) || 0;
  const serviceName = normalizeServiceName(service.name);

  return {
    id: service.id || `cart_${service.service_id || 'custom'}_${Date.now()}`,
    service_id: service.service_id || null,
    code: service.code || null,
    name: serviceName,
    unit_price: unitPrice,
    formatted_price: service.formatted_price || formatPrice(unitPrice),
    unit: service.unit || 'pcs',
    quantity: 1,
    is_custom: service.is_custom || false,
  };
};

/**
 * Normalize cart item data for storage
 */
const normalizeCartItem = (item) => {
  return {
    id: item.id,
    service_id: item.service_id || null,
    code: item.code || null,
    name: normalizeServiceName(item.name),
    unit_price: parseFloat(item.unit_price) || 0,
    formatted_price: item.formatted_price || formatPrice(item.unit_price),
    unit: item.unit || 'pcs',
    quantity: parseInt(item.quantity) || 1,
    is_custom: item.is_custom || false,
  };
};

/**
 * Calculate cart totals
 */
const calculateCartTotals = () => {
  const subtotal = cartItems.value.reduce((total, item) => {
    return total + (parseFloat(item.unit_price) || 0) * (parseInt(item.quantity) || 1);
  }, 0);

  const disc = parseFloat(discount.value) || 0;
  const taxAmount = parseFloat(tax.value) || 0;
  const total = Math.max(0, subtotal - disc + taxAmount);

  return { subtotal, discount: disc, tax: taxAmount, total };
};

// ============================================
// CART PERSISTENCE (localStorage)
// ============================================

/**
 * Save all data to localStorage (cart + form state) - internal function
 */
const _saveCartToLocalStorage = () => {
  // Always save, even during initial load (but skip if cart is empty and we're loading)
  if (isInitialLoad.value && cartItems.value.length === 0) {
    return;
  }

  try {
    const totals = calculateCartTotals();
    const cartData = {
      // Cart items
      items: cartItems.value.map(normalizeCartItem),
      ...totals,
      saved_at: new Date().toISOString(),
      item_count: cartItems.value.length,

      // Form state
      customer: selectedCustomer.value ? {
        id: selectedCustomer.value.id,
        name: selectedCustomer.value.name,
        phone: selectedCustomer.value.phone,
        email: selectedCustomer.value.email,
        address: selectedCustomer.value.address,
        city: selectedCustomer.value.city,
        type: selectedCustomer.value.type,
        company_name: selectedCustomer.value.company_name,
      } : null,
      customerType: customerType.value,
      customerSearch: customerSearch.value,
      serviceSearch: serviceSearch.value,
      discount: discount.value,
      tax: tax.value,
      showCustomServiceForm: showCustomServiceForm.value,
      customService: { ...customService.value },
    };

    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartData));
  } catch (error) {
    console.error('Error saving to localStorage:', error);
  }
};

/**
 * Debounced save function (for input events - reduces lag)
 * 300ms delay to balance responsiveness and performance
 */
const debouncedSaveCart = useDebounceFn(_saveCartToLocalStorage, 300);

/**
 * Save all data to localStorage (cart + form state) - immediate save
 */
const saveCartToLocalStorage = () => {
  _saveCartToLocalStorage();
};

/**
 * Restore item name from services if missing
 */
const restoreItemName = (item) => {
  if (item.name && item.name !== '') {
    return item.name;
  }

  // Try to get name from services if service_id exists
  if (item.service_id && props.services?.length > 0) {
    const service = props.services.find(s => s.id === item.service_id);
    if (service) {
      return normalizeServiceName(service.name);
    }
  }

  return 'Unnamed Item';
};

/**
 * Load state from localStorage into appState (only once)
 */
const loadStateFromLocalStorage = () => {
  // Only load once
  if (hasLoadedFromLocalStorage.value) {
    console.log('State already loaded from localStorage, skipping...');
    return;
  }

  try {
    const cartData = localStorage.getItem(CART_STORAGE_KEY);
    console.log('=== LOADING STATE FROM LOCALSTORAGE (FIRST TIME) ===');
    console.log('Raw cart data:', cartData);

    if (!cartData) {
      console.log('No cart data found in localStorage');
      hasLoadedFromLocalStorage.value = true; // Mark as loaded even if empty
      return;
    }

    const parsed = JSON.parse(cartData);
    console.log('Parsed cart data:', parsed);

    // Load all data into appState
    appState.value = {
      items: parsed.items || [],
      customer: parsed.customer || null,
      customerType: parsed.customerType || 'existing',
      customerSearch: parsed.customerSearch || '',
      serviceSearch: parsed.serviceSearch || '',
      discount: parsed.discount !== undefined && parsed.discount !== null ? parseFloat(parsed.discount) || 0 : 0,
      tax: parsed.tax !== undefined && parsed.tax !== null ? parseFloat(parsed.tax) || 0 : 0,
      showCustomServiceForm: parsed.showCustomServiceForm || false,
      customService: parsed.customService || { name: '' },
    };

    // Mark as loaded
    hasLoadedFromLocalStorage.value = true;
    console.log('State loaded into appState (first time only):', appState.value);
  } catch (error) {
    console.error('Error loading state from localStorage:', error);
    console.error('Error details:', error.message, error.stack);
    hasLoadedFromLocalStorage.value = true; // Mark as loaded even on error
  }
};

/**
 * Apply appState to form fields
 */
const applyStateToForm = () => {
  try {
    console.log('=== APPLYING STATE TO FORM ===');

    // Apply cart items
    if (appState.value.items && Array.isArray(appState.value.items) && appState.value.items.length > 0) {
      console.log('Processing', appState.value.items.length, 'items from state...');

      const restoredItems = appState.value.items.map((item, idx) => {
        const unitPrice = parseFloat(item.unit_price) || 0;
        const quantity = Math.max(1, parseInt(item.quantity) || 1);
        const itemName = restoreItemName(item);

        const restoredItem = {
          id: item.id || `cart_${item.service_id || 'custom'}_${Date.now()}_${idx}`,
          service_id: item.service_id || null,
          code: item.code || null,
          name: itemName,
          unit_price: unitPrice || 0,
          quantity: quantity || 1,
          formatted_price: item.formatted_price || formatPrice(unitPrice || 0),
          unit: item.unit || 'pcs',
          is_custom: item.is_custom || false,
        };

        return restoredItem;
      });

      cartItems.value = restoredItems;
      console.log('Cart items applied:', cartItems.value.length, 'items');
    } else {
      cartItems.value = [];
    }

    // Apply discount and tax
    discount.value = appState.value.discount || 0;
    tax.value = appState.value.tax || 0;
    console.log('Discount applied:', discount.value);
    console.log('Tax applied:', tax.value);

    // Apply customer
    if (appState.value.customer) {
      const foundCustomer = props.customers.find(c => c.id === appState.value.customer.id);
      if (foundCustomer) {
        selectedCustomer.value = foundCustomer;
        console.log('Customer found in props:', foundCustomer.name);
      } else {
        selectedCustomer.value = appState.value.customer;
        console.log('Using saved customer data:', appState.value.customer.name);
      }
    }

    // Apply other form state
    customerType.value = appState.value.customerType || 'existing';
    customerSearch.value = appState.value.customerSearch || '';
    serviceSearch.value = appState.value.serviceSearch || '';
    showCustomServiceForm.value = appState.value.showCustomServiceForm || false;
    customService.value = { ...(appState.value.customService || { name: '' }) };

    console.log('=== STATE APPLIED TO FORM ===');
    console.log('Final items count:', cartItems.value.length);
    console.log('Customer:', selectedCustomer.value?.name);
  } catch (error) {
    console.error('Error applying state to form:', error);
  }
};

/**
 * Load cart from localStorage and render it (uses state pattern)
 * Only loads once on initial mount
 */
const loadCartFromLocalStorage = () => {
  // Only load if not already loaded
  if (hasLoadedFromLocalStorage.value) {
    console.log('Cart already loaded from localStorage, skipping...');
    return;
  }

  // First load into state (only once)
  loadStateFromLocalStorage();

  // Then apply state to form (only once)
  if (hasLoadedFromLocalStorage.value) {
    applyStateToForm();
  }
};


// Clear localStorage (call this after successful order creation)
const clearOrderState = () => {
  try {
    localStorage.removeItem(CART_STORAGE_KEY);
    localStorage.removeItem('order_customer');
  } catch (e) {
    console.error('Error clearing localStorage:', e);
  }
};

// Watch for changes and auto-save (only after initial load) - debounced for inputs
watch([selectedCustomer, customerType, customerSearch, serviceSearch, discount, tax, showCustomServiceForm], () => {
  if (!isInitialLoad.value) {
    // Use debounced save for search inputs, immediate for customer selection
    if (customerSearch.value !== undefined || serviceSearch.value !== undefined || discount.value !== undefined || tax.value !== undefined) {
      debouncedSaveCart(); // Debounced for inputs
    } else {
      saveCartToLocalStorage(); // Immediate for customer selection
    }
  }
}, { deep: true });

// Watch customService separately with deep watch - debounced for input
watch(customService, () => {
  if (!isInitialLoad.value) {
    debouncedSaveCart(); // Debounced since it's an input field
  }
}, { deep: true });

// Watch cart items changes (deep watch to catch quantity/price changes) - debounced save
// Note: This watch is mainly for programmatic changes, input handlers handle their own debouncing
watch(cartItems, () => {
  // Debounced save for cart changes (reduces lag)
  // Skip if initial load or if cart is empty
  if (!isInitialLoad.value && cartItems.value.length > 0) {
    debouncedSaveCart();
  }
}, { deep: true, immediate: false });

// Load all data from localStorage on mount
onMounted(async () => {
  // Check for validation errors first
  console.log('Page props on mount:', page.props);
  console.log('Errors on mount:', page.props.errors);
  if (page.props.errors && Object.keys(page.props.errors).length > 0) {
    console.log('Initial validation errors on mount:', page.props.errors);
  }

  // Set initial load flag to prevent saving during load
  isInitialLoad.value = true;
  console.log('Component mounted, starting to load data...');

  // Wait for props to be available
  await nextTick();
  console.log('Props available, services count:', props.services?.length);

  // Load cart from localStorage first
  console.log('Loading cart from localStorage...');
  loadCartFromLocalStorage();

  // Wait for Vue to process the cart items
  await nextTick();
  console.log('After loading cart, items count:', cartItems.value.length);

  // All state is now loaded in loadCartFromLocalStorage()

  // Wait again for all state to be set
  await nextTick();

  // Force a reactive update by reassigning the array
  await nextTick();
  if (cartItems.value.length > 0) {
    console.log('Forcing reactive update for', cartItems.value.length, 'items');
    const items = cartItems.value.map(item => ({ ...item }));
    cartItems.value = [];
    await nextTick();
    cartItems.value = items;
    console.log('Reactive update complete, items count:', cartItems.value.length);
    console.log('Items after reactive update:', cartItems.value);
  } else {
    console.log('No items to update, cart is empty');
  }

  // Mark initial load as complete after a delay to allow all data to load and settle
  setTimeout(() => {
    isInitialLoad.value = false;
    console.log('=== Initial load complete ===');
    console.log('Cart items count:', cartItems.value.length);
    console.log('Cart items:', JSON.stringify(cartItems.value, null, 2));
    console.log('Selected customer:', selectedCustomer.value?.name);
    console.log('Discount:', discount.value, 'Tax:', tax.value);

    // Verify all items have required fields
    cartItems.value.forEach((item, index) => {
      console.log(`Item ${index}:`, {
        id: item.id,
        name: item.name,
        quantity: item.quantity,
        unit_price: item.unit_price,
        service_id: item.service_id,
        is_custom: item.is_custom,
      });
    });
  }, 500);
});

// Use services from props
const availableServices = computed(() => {
  return props.services;
});

const filteredServices = computed(() => {
  if (!serviceSearch.value) {
    return availableServices.value;
  }
  const search = serviceSearch.value.toLowerCase();
  return availableServices.value.filter(service =>
    service.name?.en?.toLowerCase().includes(search) ||
    service.name?.ar?.toLowerCase().includes(search) ||
    service.code?.toLowerCase().includes(search)
  );
});

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

// ============================================
// COMPUTED PROPERTIES
// ============================================

const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => {
    return total + (parseFloat(item.unit_price) || 0) * (parseInt(item.quantity) || 1);
  }, 0);
});

const total = computed(() => {
  const sub = subtotal.value;
  const disc = parseFloat(discount.value) || 0;
  const taxAmount = parseFloat(tax.value) || 0;
  return Math.max(0, sub - disc + taxAmount);
});

// ============================================
// CART OPERATIONS
// ============================================

/**
 * Add service to cart
 */
const addToCart = (service) => {
  try {
    // Custom services are always added as new items
    if (service.is_custom || !service.service_id) {
      const newItem = createCartItem({
        ...service,
        id: service.id || `cart_custom_${Date.now()}`,
        is_custom: true,
      });

      cartItems.value = [...cartItems.value, newItem];
      saveCartToLocalStorage(); // Save immediately
      return;
    }

    // Check if item already exists
    const existingIndex = cartItems.value.findIndex(
      item => item.service_id === service.id
    );

    if (existingIndex !== -1) {
      // Increment quantity of existing item
      const updatedItems = [...cartItems.value];
      updatedItems[existingIndex] = {
        ...updatedItems[existingIndex],
        quantity: updatedItems[existingIndex].quantity + 1,
      };
      cartItems.value = updatedItems;
    } else {
      // Add new item
      const newItem = createCartItem(service);
      cartItems.value = [...cartItems.value, newItem];
    }

    saveCartToLocalStorage(); // Save immediately
  } catch (error) {
    console.error('Failed to add item to cart:', error);
  }
};

const resetCustomServiceForm = () => {
  customService.value = {
    name: '',
  };
  // State will be saved automatically via watch
};

const addCustomService = () => {
  if (!customService.value.name) {
    return;
  }

  const customServiceItem = {
    id: `custom_${Date.now()}`,
    service_id: null, // Custom service has no service_id
    code: null,
    name: customService.value.name,
    unit_price: 0, // Price will be set in cart
    formatted_price: 'QR 0.00',
    unit: '', // No unit for custom services
    quantity: 1, // Default quantity is 1
    is_custom: true, // Flag to identify custom services
  };

  try {
    // Add to cart
    addToCart(customServiceItem);

    // Reset form but keep it open for adding more
    resetCustomServiceForm();

    // Focus back on name input for quick entry
    setTimeout(() => {
      const nameInput = document.querySelector('input[placeholder="Service name"]');
      if (nameInput) nameInput.focus();
    }, 100);
  } catch (error) {
    console.error('Failed to add custom service to cart:', error);
  }
};

/**
 * Remove item from cart
 */
const removeFromCart = (index) => {
  if (index >= 0 && index < cartItems.value.length) {
    cartItems.value = cartItems.value.filter((_, i) => i !== index);
    saveCartToLocalStorage();
  }
};

/**
 * Update item quantity
 */
const updateItemQuantity = (index, quantity) => {
  if (index < 0 || index >= cartItems.value.length) return;

  const qty = Math.max(1, parseInt(quantity) || 1);
  const updatedItems = [...cartItems.value];
  updatedItems[index] = { ...updatedItems[index], quantity: qty };
  cartItems.value = updatedItems;
  // Debounced save for input
  debouncedSaveCart();
};

/**
 * Increase item quantity
 */
const increaseQuantity = (index) => {
  if (index >= 0 && index < cartItems.value.length) {
    updateItemQuantity(index, cartItems.value[index].quantity + 1);
  }
};

/**
 * Decrease item quantity
 */
const decreaseQuantity = (index) => {
  if (index >= 0 && index < cartItems.value.length) {
    const currentQty = cartItems.value[index].quantity;
    if (currentQty > 1) {
      updateItemQuantity(index, currentQty - 1);
    }
  }
};

/**
 * Update item price (handles raw input for immediate display)
 */
const updatePrice = (index, newPrice) => {
  if (index < 0 || index >= cartItems.value.length) return;

  // Handle empty string or null
  if (newPrice === '' || newPrice === null || newPrice === undefined) {
    const updatedItems = [...cartItems.value];
    updatedItems[index] = {
      ...updatedItems[index],
      unit_price: 0,
      formatted_price: formatPrice(0),
    };
    cartItems.value = updatedItems;
    debouncedSaveCart();
    return;
  }

  // Parse the value - allow intermediate states while typing
  const price = typeof newPrice === 'string' ? parseFloat(newPrice) : newPrice;

  // If it's a valid number, update immediately
  if (!isNaN(price) && isFinite(price)) {
    const updatedItems = [...cartItems.value];
    updatedItems[index] = {
      ...updatedItems[index],
      unit_price: price,
      formatted_price: formatPrice(price),
    };
    cartItems.value = updatedItems;
  }

  // Debounced save for input
  debouncedSaveCart();
};

/**
 * Update item name
 */
const updateItemName = (index, newName) => {
  if (index < 0 || index >= cartItems.value.length) return;

  const updatedItems = [...cartItems.value];
  updatedItems[index] = {
    ...updatedItems[index],
    name: newName || 'Unnamed Item',
  };
  cartItems.value = updatedItems;
  saveCartToLocalStorage();
};

/**
 * Handle direct quantity input (immediate update)
 */
const updateQuantityDirect = (index, value) => {
  if (index < 0 || index >= cartItems.value.length) return;

  // Handle empty string or null
  if (value === '' || value === null || value === undefined) {
    const updatedItems = [...cartItems.value];
    updatedItems[index] = { ...updatedItems[index], quantity: 1 };
    cartItems.value = updatedItems;
    debouncedSaveCart();
    return;
  }

  // Parse the value - allow intermediate states while typing
  const quantity = typeof value === 'string' ? parseInt(value) : value;

  // If it's a valid number and >= 1, update immediately
  if (!isNaN(quantity) && isFinite(quantity) && quantity >= 1) {
    const updatedItems = [...cartItems.value];
    updatedItems[index] = { ...updatedItems[index], quantity: quantity };
    cartItems.value = updatedItems;
  } else if (!isNaN(quantity) && quantity < 1) {
    // If less than 1, set to 1
    const updatedItems = [...cartItems.value];
    updatedItems[index] = { ...updatedItems[index], quantity: 1 };
    cartItems.value = updatedItems;
  }

  // Debounced save
  debouncedSaveCart();
};

const formatAmount = (amount) => {
  const formatted = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount);
  return `QR ${formatted}`;
};

// Format field names for display
const formatFieldName = (field) => {
  // Convert field names like "items.0.quantity" to "Item 1 - Quantity"
  if (field.startsWith('items.')) {
    const parts = field.split('.');
    if (parts.length >= 3) {
      const itemIndex = parseInt(parts[1]) + 1;
      const fieldName = parts[2].replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
      return `Item ${itemIndex} - ${fieldName}`;
    }
  }
  // Convert snake_case to Title Case
  return field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

// Watch for errors from page props (for debugging)
watch(() => page.props.errors, (newErrors) => {
  console.log('Page errors changed:', newErrors);
}, { immediate: true, deep: true });


const selectCustomer = (customer) => {
  selectedCustomer.value = customer;
  saveCartToLocalStorage();
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

const handleCancel = () => {
  router.visit(route('orders.index'));
};

const handleResetForm = () => {
  // Confirm before resetting
  if (!confirm('Are you sure you want to reset the form? This will clear all selected items, customer, and form data.')) {
    return;
  }

  // Clear cart items
  cartItems.value = [];

  // Reset customer selection
  selectedCustomer.value = null;
  customerType.value = 'existing';
  customerSearch.value = '';
  serviceSearch.value = '';

  // Reset pricing
  discount.value = 0;
  tax.value = 0;

  // Reset custom service form
  showCustomServiceForm.value = false;
  customService.value = {
    name: '',
  };

  // Reset new customer form
  form.reset();
  form.type = 'individual';

  // Clear localStorage
    localStorage.removeItem(CART_STORAGE_KEY);
  localStorage.removeItem('order_customer');

  console.log('Form reset successfully');
};

const handleContinue = () => {
  if (cartItems.value.length === 0) {
    return;
  }

  if (!selectedCustomer.value) {
    alert('Please select or create a customer');
    return;
  }

  // Store cart items in localStorage (for preview page)
  localStorage.setItem(CART_STORAGE_KEY, JSON.stringify({
    items: cartItems.value.map(item => ({
      service_id: item.service_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
      code: item.code,
      name: item.name,
      unit: item.unit,
    })),
    subtotal: subtotal.value,
    discount: discount.value || 0,
    tax: tax.value || 0,
    total: total.value,
  }));

  // Store customer selection in localStorage (for preview page)
  localStorage.setItem('order_customer', JSON.stringify({
    customer_id: selectedCustomer.value.id,
    customer: selectedCustomer.value,
  }));

  // State is already saved via watch, but ensure it's saved before navigation
  saveCartToLocalStorage();

  // Navigate directly to preview page
  router.visit(route('orders.create-preview'));
};
</script>
