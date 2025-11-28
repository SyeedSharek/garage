<template>
  <div class="relative">
    <button
      type="button"
      :class="
        cn(
          'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
          $attrs.class
        )
      "
      @click="open = !open"
      v-bind="$attrs"
    >
      <span class="text-foreground flex items-center">
        <template v-if="selectedLabel">
          <span class="text-gray-500">Show: </span>
          <span class="text-gray-900">{{ selectedLabel }}</span>
        </template>
        <template v-else>
          <span class="text-gray-500">Show: </span>
          <span class="text-gray-900">{{ placeholder.replace('Show: ', '') || 'All Orders' }}</span>
        </template>
      </span>
      <HugeiconsIcon
        :name="ArrowDown01Icon"
        class="h-4 w-4 opacity-50"
      />
    </button>
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-show="open"
        class="absolute z-50 mt-1 w-full rounded-md border bg-popover shadow-md"
        v-click-outside="() => (open = false)"
      >
        <div class="p-1">
          <div
            v-for="option in options"
            :key="option.value"
            :class="
              cn(
                'relative flex w-full cursor-pointer select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground',
                modelValue === option.value && 'bg-accent text-accent-foreground'
              )
            "
            @click="selectOption(option.value)"
          >
            {{ option.label }}
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { cn } from '@/lib/utils';
import { HugeiconsIcon } from '@hugeicons/vue';
import { ArrowDown01Icon } from '@hugeicons/core-free-icons';

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: null,
  },
  options: {
    type: Array,
    required: true,
  },
  placeholder: {
    type: String,
    default: 'Select...',
  },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);

const selectedLabel = computed(() => {
  if (props.modelValue === null || props.modelValue === undefined) {
    return null;
  }
  const option = props.options.find((opt) => opt.value === props.modelValue);
  return option ? option.label : null;
});

const selectOption = (value) => {
  emit('update:modelValue', value);
  open.value = false;
};

const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};
</script>

