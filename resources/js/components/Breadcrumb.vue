<script setup lang="ts">
import { ChevronRight, Home } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export interface BreadcrumbItem {
  title: string;
  href?: string;
  icon?: any;
}

defineProps<{
  items: BreadcrumbItem[];
  showHome?: boolean;
}>();
</script>

<template>
  <nav class="mb-4 flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
      <li v-if="showHome" class="inline-flex items-center">
        <Link
          :href="route('dashboard')"
          class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-gray-300"
        >
          <Home class="mr-2 h-4 w-4" />
          Home
        </Link>
      </li>
      
      <template v-for="(item, index) in items" :key="index">
        <li class="inline-flex items-center">
          <div v-if="index > 0 || showHome" class="mx-1 text-gray-400">
            <ChevronRight class="h-5 w-5" />
          </div>
          
          <template v-if="item.href">
            <Link
              :href="item.href"
              class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-gray-300"
            >
              <component :is="item.icon" v-if="item.icon" class="mr-2 h-4 w-4" />
              {{ item.title }}
            </Link>
          </template>
          <template v-else>
            <span class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400">
              <component :is="item.icon" v-if="item.icon" class="mr-2 h-4 w-4" />
              {{ item.title }}
            </span>
          </template>
        </li>
      </template>
    </ol>
  </nav>
</template>
