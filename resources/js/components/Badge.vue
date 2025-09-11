<template>
  <span
    :class="[
      'inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium',
      variantClass
    ]"
  >
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  variant?: 'default' | 'secondary' | 'destructive' | 'outline'
}>();

const variantClass = computed(() => {
  switch (props.variant) {
    case 'secondary':
      return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    case 'destructive':
      return 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200';
    case 'outline':
      return 'border border-gray-300 text-gray-800 dark:border-gray-600 dark:text-gray-200 bg-transparent';
    default:
      return 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-200';
  }
});
</script>