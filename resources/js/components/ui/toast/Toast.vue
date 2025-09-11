<script setup lang="ts">
import Toast from 'primevue/toast';

interface Props {
  position?: 'top-left' | 'top-center' | 'top-right' | 'bottom-left' | 'bottom-center' | 'bottom-right';
  group?: string;
  class?: string;
}

const props = withDefaults(defineProps<Props>(), {
  position: 'top-left',
  group: 'default'
});
</script>

<template>
  <Toast 
    :position="position" 
    :group="group"
    :class="class"
  />
</template>