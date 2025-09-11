<script setup lang="ts">
import { SidebarProvider } from "@/components/ui/sidebar";
import { usePage } from "@inertiajs/vue3";
import { useFlashToast } from "../composables/useFlashToast";
import Toast from "primevue/toast";

interface Props {
    variant?: "header" | "sidebar";
}

defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;
useFlashToast();
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <slot />
        <Toast position="top-right" class="!z-[9999]" />
    </div>
    <SidebarProvider v-else :default-open="isOpen">
        <slot />
        <Toast position="top-right" class="!z-[9999]" />
    </SidebarProvider>
</template>
