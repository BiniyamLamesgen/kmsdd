<script setup lang="ts">
import type { ServerPaginatedResponse } from '@/types/datatable';
import { LucideEye, LucideTarget } from 'lucide-vue-next';

interface MissionVisionItem {
    id: number;
    type: string;
    description: string;
    created_at: string | null;
    updated_at: string | null;
}

const props = defineProps<{
    MissionVision: ServerPaginatedResponse<MissionVisionItem>;
}>();

// Filter mission and vision from the data
const mission = props.MissionVision.data.find((item) => item.type.toLowerCase() === 'mission')?.description || '';

const vision = props.MissionVision.data.find((item) => item.type.toLowerCase() === 'vision')?.description || '';
</script>
<template>
    <section class="bg-gradient-to-br from-blue-100 via-white to-blue-200 px-6 py-16 md:px-10 md:py-24">
        <div class="mx-auto max-w-7xl space-y-16">
            <!-- Section Title -->
            <div class="text-center">
                <h2 class="mb-4 bg-gradient-to-r from-sky-600 to-sky-500 bg-clip-text text-3xl font-bold text-transparent md:text-4xl">
                    Our Mission & Vision
                </h2>
                <p class="mx-auto max-w-2xl text-lg leading-relaxed text-gray-700 md:text-xl">
                    Dedicated to raising awareness and advancing kidney and blood pressure care across Ethiopia.
                </p>
            </div>

            <!-- Mission and Vision Cards -->
            <div class="grid gap-6 md:grid-cols-2 md:gap-8">
                <!-- Mission Card -->
                <div
                    v-if="mission"
                    class="group relative overflow-hidden rounded-3xl border border-blue-200 bg-white p-8 shadow-xl transition-all duration-300 hover:shadow-2xl"
                >
                    <div
                        class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-blue-300 opacity-30 transition-all duration-500 group-hover:opacity-50"
                    ></div>
                    <div class="relative z-10">
                        <div class="mb-6 flex items-center">
                            <div class="mr-4 rounded-xl bg-blue-300 p-3 text-sky-500">
                                <LucideTarget class="h-6 w-6" />
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Our Mission</h3>
                        </div>
                        <p class="text-lg leading-relaxed text-gray-700" v-html="mission"></p>
                    </div>
                </div>

                <!-- Vision Card -->
                <div
                    v-if="vision"
                    class="group relative overflow-hidden rounded-3xl border border-blue-200 bg-white p-8 shadow-xl transition-all duration-300 hover:shadow-2xl"
                >
                    <div
                        class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-blue-300 opacity-30 transition-all duration-500 group-hover:opacity-50"
                    ></div>
                    <div class="relative z-10">
                        <div class="mb-6 flex items-center">
                            <div class="mr-4 rounded-xl bg-blue-300 p-3 text-sky-500">
                                <LucideEye class="h-6 w-6" />
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Our Vision</h3>
                        </div>
                        <p class="text-lg leading-relaxed text-gray-700" v-html="vision"></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
