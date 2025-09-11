<script lang="ts" setup>
import 'swiper/css';
import { Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { ref } from 'vue';

interface Partner {
    id: number;
    name: string;
    logo: string | null;
    logo_path: string | null;
    created_at: string | null;
    updated_at: string | null;
    created_at_formatted: string | null;
    updated_at_formatted: string | null;
}

const props = defineProps<{
    partners: {
        data: Partner[];
        meta: {
            total: number;
            per_page: number;
            current_page: number;
            last_page: number;
            from: number | null;
            to: number | null;
        };
        links: {
            first: string | null;
            last: string | null;
            prev: string | null;
            next: string | null;
        };
    };
    error?: string;
}>();

const partners = ref<Partner[]>(props.partners?.data || []);
const isLoading = ref(false);
const errorMsg = ref<string | null>(props.error || null);
</script>

<template>
    <section class="bg-blue-50 py-12">
        <div class="mx-auto max-w-7xl px-4 text-center">
            <h2 class="mb-8 text-2xl font-bold text-sky-500 md:text-3xl">Our Partners</h2>

            <div v-if="isLoading" class="flex h-40 items-center justify-center">
                <LoadingSpinner />
            </div>

            <div v-else-if="errorMsg" class="mx-auto max-w-md rounded-lg bg-red-50 p-4 text-red-500">
                {{ errorMsg }}
            </div>

            <p v-else-if="partners.length === 0" class="text-sky-400">No partners found.</p>

            <Swiper
                v-else
                :modules="[Autoplay]"
                :autoplay="{
                    delay: 2500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                }"
                :loop="true"
                :breakpoints="{
                    320: { slidesPerView: 2, spaceBetween: 20 },
                    640: { slidesPerView: 3, spaceBetween: 30 },
                    1024: { slidesPerView: 4, spaceBetween: 40 },
                }"
                class="py-4"
            >
                <SwiperSlide v-for="partner in partners" :key="partner.id">
                    <div class="flex cursor-pointer flex-col items-center space-y-2 p-2 transition-transform duration-300 hover:scale-105">
                        <div class="relative h-20 w-20 overflow-hidden rounded-full bg-white shadow-md">
                            <img :src="partner.logo ?? undefined" :alt="partner.name ?? undefined" class="h-full w-full object-contain p-2" />
                        </div>
                        <p class="text-center text-sm font-medium text-sky-700">
                            {{ partner.name }}
                        </p>
                    </div>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>
