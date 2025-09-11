<script setup lang="ts">
import { ref } from "vue";

defineProps<{
    contentManagementLogo: string;
}>();

const imageSrc = ref("/images/img.jpg");
function onImgError(e: Event) {
    (e.target as HTMLImageElement).src =
        "https://via.placeholder.com/64x64?text=Logo";
}
</script>

<template>
    <div class="flex items-center pb-2">
        <div
            class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-full"
        >
            <img :src="imageSrc" alt="Logo" @error="onImgError" />
        </div>
        <div class="ml-1 grid flex-1 text-left text-sm">
            <span class="mb-0.5 truncate leading-tight font-semibold">Lemi Kura Sub City</span>
        </div>
    </div>
</template>
