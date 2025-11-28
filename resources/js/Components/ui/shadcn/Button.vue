<template>
  <component
    :is="as"
    :class="
      cn(
        'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
        {
          'bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2': variant === 'default',
          'bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2': variant === 'destructive',
          'border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2': variant === 'outline',
          'bg-secondary text-secondary-foreground hover:bg-secondary/80 h-10 px-4 py-2': variant === 'secondary',
          'hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2': variant === 'ghost',
          'text-primary underline-offset-4 hover:underline h-10 px-4 py-2': variant === 'link',
        },
        size === 'sm' && 'h-9 rounded-md px-3',
        size === 'lg' && 'h-11 rounded-md px-8',
        size === 'icon' && 'h-10 w-10',
        $attrs.class
      )
    "
    v-bind="$attrs"
  >
    <slot />
  </component>
</template>

<script setup>
import { cn } from '@/lib/utils';

defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'destructive', 'outline', 'secondary', 'ghost', 'link'].includes(value),
  },
  size: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'sm', 'lg', 'icon'].includes(value),
  },
  as: {
    type: String,
    default: 'button',
  },
});
</script>

