<template>
  <div :class="cn('rounded-lg border bg-card text-card-foreground shadow-sm', $attrs.class)">
    <div v-if="$slots.header" class="flex flex-col space-y-1.5 p-6">
      <slot name="header" />
    </div>
    <div :class="cn('p-6 pt-0', $slots.header ? '' : 'p-6')">
      <slot />
    </div>
    <div v-if="$slots.footer" class="flex items-center p-6 pt-0">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
import { cn } from '@/lib/utils';
</script>

