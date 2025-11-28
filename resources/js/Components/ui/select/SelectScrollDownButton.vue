<script setup>
import { reactiveOmit } from "@vueuse/core";
import { HugeiconsIcon } from '@hugeicons/vue';
import { ArrowDown01Icon } from '@hugeicons/core-free-icons';
import { SelectScrollDownButton, useForwardProps } from "reka-ui";
import { cn } from '@/lib/utils';

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});

const delegatedProps = reactiveOmit(props, "class");

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <SelectScrollDownButton
    v-bind="forwardedProps"
    :class="
      cn('flex cursor-default items-center justify-center py-1', props.class)
    "
  >
    <slot>
      <HugeiconsIcon :name="ArrowDown01Icon" class="h-4 w-4" />
    </slot>
  </SelectScrollDownButton>
</template>
