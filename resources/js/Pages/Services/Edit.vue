<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Edit Service" subtitle="Update service information" />
    </template>
    <template #default>
      <div class="p-6 space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Service</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the service details below</p>
          </div>
          <Button variant="outline" class="gap-2" @click="handleCancel">
            <HugeiconsIcon :name="ArrowLeft01Icon" class="h-4 w-4" />
            Back
          </Button>
        </div>

        <!-- Form Card -->
        <Card class="w-full">
          <CardHeader>
            <CardTitle>Service Information</CardTitle>
            <CardDescription>Update the service details below</CardDescription>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Code Field -->
              <div class="space-y-2">
                <Label for="code">Service Code</Label>
                <Input
                  id="code"
                  v-model="form.code"
                  placeholder="e.g., SVC-001"
                  :class="errors.code ? 'border-red-500' : ''"
                />
                <p v-if="errors.code" class="text-sm text-red-600">{{ errors.code }}</p>
                <p class="text-xs text-gray-500">Optional: Leave empty to auto-generate</p>
              </div>

              <!-- Name Fields (English & Arabic) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <Label for="name_en">Service Name (English) <span class="text-red-500">*</span></Label>
                  <Input
                    id="name_en"
                    v-model="form.name.en"
                    placeholder="Enter service name in English"
                    :class="errors['name.en'] ? 'border-red-500' : ''"
                    required
                  />
                  <p v-if="errors['name.en']" class="text-sm text-red-600">{{ errors['name.en'] }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="name_ar">Service Name (Arabic) <span class="text-red-500">*</span></Label>
                  <Input
                    id="name_ar"
                    v-model="form.name.ar"
                    placeholder="أدخل اسم الخدمة بالعربية"
                    :class="errors['name.ar'] ? 'border-red-500' : ''"
                    dir="rtl"
                    required
                  />
                  <p v-if="errors['name.ar']" class="text-sm text-red-600">{{ errors['name.ar'] }}</p>
                </div>
              </div>

              <!-- Description Fields (English & Arabic) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <Label for="description_en">Description (English)</Label>
                  <textarea
                    id="description_en"
                    v-model="form.description.en"
                    placeholder="Enter service description in English"
                    rows="3"
                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    :class="errors['description.en'] ? 'border-red-500' : ''"
                  />
                  <p v-if="errors['description.en']" class="text-sm text-red-600">{{ errors['description.en'] }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="description_ar">Description (Arabic)</Label>
                  <textarea
                    id="description_ar"
                    v-model="form.description.ar"
                    placeholder="أدخل وصف الخدمة بالعربية"
                    rows="3"
                    dir="rtl"
                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    :class="errors['description.ar'] ? 'border-red-500' : ''"
                  />
                  <p v-if="errors['description.ar']" class="text-sm text-red-600">{{ errors['description.ar'] }}</p>
                </div>
              </div>

              <!-- Price and Unit Fields -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <Label for="unit_price">Unit Price <span class="text-red-500">*</span></Label>
                  <Input
                    id="unit_price"
                    v-model="form.unit_price"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    :class="errors.unit_price ? 'border-red-500' : ''"
                    required
                  />
                  <p v-if="errors.unit_price" class="text-sm text-red-600">{{ errors.unit_price }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="unit">Unit <span class="text-red-500">*</span></Label>
                  <Select v-model="form.unit" :class="errors.unit ? 'border-red-500' : ''">
                    <SelectTrigger id="unit">
                      <SelectValue placeholder="Select unit" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="pcs">Pieces (pcs)</SelectItem>
                      <SelectItem value="hrs">Hours (hrs)</SelectItem>
                      <SelectItem value="days">Days</SelectItem>
                      <SelectItem value="kg">Kilograms (kg)</SelectItem>
                      <SelectItem value="liters">Liters</SelectItem>
                    </SelectContent>
                  </Select>
                  <p v-if="errors.unit" class="text-sm text-red-600">{{ errors.unit }}</p>
                </div>
              </div>

              <!-- Sort Order and Active Status -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <Label for="sort_order">Sort Order</Label>
                  <Input
                    id="sort_order"
                    v-model="form.sort_order"
                    type="number"
                    min="0"
                    placeholder="0"
                    :class="errors.sort_order ? 'border-red-500' : ''"
                  />
                  <p v-if="errors.sort_order" class="text-sm text-red-600">{{ errors.sort_order }}</p>
                  <p class="text-xs text-gray-500">Lower numbers appear first</p>
                </div>
                <div class="space-y-2">
                  <Label for="is_active">Status</Label>
                  <div class="flex items-center space-x-2 pt-2">
                    <Switch
                      id="is_active"
                      v-model:checked="form.is_active"
                    />
                    <Label for="is_active" class="cursor-pointer">
                      {{ form.is_active ? 'Active' : 'Inactive' }}
                    </Label>
                  </div>
                  <p v-if="errors.is_active" class="text-sm text-red-600">{{ errors.is_active }}</p>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <Button type="button" variant="outline" @click="handleCancel">
                  Cancel
                </Button>
                <Button type="submit" :disabled="processing">
                  <HugeiconsIcon v-if="!processing" :name="Edit01Icon" class="h-4 w-4 mr-2" />
                  <span v-if="processing">Updating...</span>
                  <span v-else>Update Service</span>
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { HugeiconsIcon } from '@hugeicons/vue';
import { ArrowLeft01Icon, Edit01Icon } from '@hugeicons/core-free-icons';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select';
import { Switch } from '@/Components/ui/switch';

const props = defineProps({
  service: {
    type: Object,
    required: true,
  },
});

const page = usePage();

const form = ref({
  code: '',
  name: {
    en: '',
    ar: '',
  },
  description: {
    en: '',
    ar: '',
  },
  unit_price: '0.00',
  unit: 'pcs',
  is_active: true,
  sort_order: 0,
});

const processing = ref(false);
const errors = ref(page.props.errors || {});

onMounted(() => {
  // Initialize form with service data
  form.value = {
    code: props.service.code || '',
    name: {
      en: props.service.name?.en || '',
      ar: props.service.name?.ar || '',
    },
    description: {
      en: props.service.description?.en || '',
      ar: props.service.description?.ar || '',
    },
    unit_price: props.service.unit_price?.toString() || '0.00',
    unit: props.service.unit || 'pcs',
    is_active: props.service.is_active ?? true,
    sort_order: props.service.sort_order || 0,
  };
});

const handleSubmit = () => {
  processing.value = true;
  errors.value = {};

  router.put(
    route('services.update', props.service.id),
    {
      code: form.value.code || null,
      name: {
        en: form.value.name.en,
        ar: form.value.name.ar,
      },
      description: {
        en: form.value.description.en || null,
        ar: form.value.description.ar || null,
      },
      unit_price: parseFloat(form.value.unit_price) || 0,
      unit: form.value.unit,
      is_active: form.value.is_active,
      sort_order: parseInt(form.value.sort_order) || 0,
    },
    {
      preserveScroll: true,
      onError: (err) => {
        errors.value = err;
        processing.value = false;
      },
      onSuccess: () => {
        processing.value = false;
      },
    }
  );
};

const handleCancel = () => {
  router.visit(route('services.index'));
};
</script>

