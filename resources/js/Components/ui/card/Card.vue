<template>
  <div :class="cn(
    'rounded-xl border border-gray-200 bg-card text-card-foreground shadow',
    props.class
  )
    ">
    <slot />
  </div>

</template>

<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: HTMLAttributes['class'];
}>();
</script>
