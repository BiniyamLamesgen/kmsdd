<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import Toast from 'primevue/toast';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}
interface ContentManagementProps {
    contentManagementLogo?: string;
}


withDefaults(defineProps<Props & ContentManagementProps>(), {
    breadcrumbs: () => [],
    contentManagementLogo: '',
});
</script>

<template>
    <Toast position="top-right" />
    <AppLayout :breadcrumbs="breadcrumbs" :contentManagementLogo="contentManagementLogo">
        <slot />
    </AppLayout>
</template>
