<template>
    <section class="relative h-screen w-full overflow-hidden bg-black">
        <!-- Slide Background -->
        <Motion
            v-if="currentSlide"
            :key="currentSlide.id"
            :initial="getEnterAnimation(direction)"
            :animate="getCenterAnimation()"
            :exit="getExitAnimation(direction)"
            class="absolute inset-0 z-0 h-full w-full"
        >
            <img v-if="currentSlide" :src="currentSlide.image ?? undefined" :alt="currentSlide.title ?? ''" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-black/40" />
        </Motion>

        <!-- Text Overlay -->
        <Motion
            v-if="currentSlide"
            :key="`text-${currentIndex}`"
            :initial="{ opacity: 0, y: 20 }"
            :animate="{ opacity: 1, y: 0 }"
            :exit="{ opacity: 0, y: -20 }"
            :transition="{ duration: 0.5, delay: 0.2 }"
            class="absolute top-1/2 left-1/2 z-10 w-[90%] -translate-x-1/2 -translate-y-1/2 transform md:w-[80%] lg:w-[65%] xl:w-[50%]"
        >
            <div class="rounded-xl border border-white/10 bg-black/60 p-6 text-center shadow-xl backdrop-blur-md md:p-8">
                <h2 class="mb-4 text-xl font-bold text-sky-500 md:text-2xl lg:text-3xl">
                    {{ currentSlide?.title ?? '' }}
                </h2>
                <p class="text-lg text-white md:text-xl">
                    {{ currentSlide?.description ?? '' }}
                </p>
            </div>
        </Motion>

        <!-- Navigation Arrows with Scale Animation -->
        <Motion
            :animate="arrowAnimation.prev"
            @click="handleArrowClick('prev')"
            class="absolute top-1/2 left-4 z-20 -translate-y-1/2 cursor-pointer rounded-full bg-sky-500 p-2 text-white shadow-lg transition hover:bg-sky-600"
            aria-label="Previous slide"
        >
            <svg class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </Motion>

        <Motion
            :animate="arrowAnimation.next"
            @click="handleArrowClick('next')"
            class="absolute top-1/2 right-4 z-20 -translate-y-1/2 cursor-pointer rounded-full bg-sky-500 p-2 text-white shadow-lg transition hover:bg-sky-600"
            aria-label="Next slide"
        >
            <svg class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </Motion>

        <!-- Dots -->
        <div class="absolute bottom-8 left-1/2 z-10 flex -translate-x-1/2 gap-3">
            <button
                v-for="(s, index) in slides"
                :key="index"
                @click="goToSlide(index)"
                :class="[
                    'h-3 w-3 cursor-pointer rounded-full transition-all duration-300 md:h-4 md:w-4',
                    index === currentIndex ? 'scale-125 bg-sky-500' : 'bg-sky-300/70 hover:bg-sky-400',
                ]"
                :aria-label="`Go to slide ${index + 1}`"
            />
        </div>
    </section>
</template>

<script setup lang="ts">
import { Motion } from '@motionone/vue';
import { computed, onMounted, ref, watchEffect } from 'vue';

interface Slide {
    id: number;
    title: string;
    description: string;
    image: string | null;
}
const props = defineProps<{
    heroSlides: {
        data: Slide[];
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
}>();

const AUTO_SLIDE_INTERVAL = 5000;
const currentIndex = ref(0);
const direction = ref<'left' | 'right'>('right');
let intervalId: number | undefined;

// Define slides as a ref containing the slides data from props
const slides = ref<Slide[]>(props.heroSlides?.data ?? []);

const currentSlide = computed(() => slides.value[currentIndex.value]);

const arrowAnimation = ref({
    prev: { scale: 1 },
    next: { scale: 1 },
});

const goToPrevious = () => {
    direction.value = 'left';
    currentIndex.value = currentIndex.value === 0 ? slides.value.length - 1 : currentIndex.value - 1;
};

const goToNext = () => {
    direction.value = 'right';
    currentIndex.value = currentIndex.value === slides.value.length - 1 ? 0 : currentIndex.value + 1;
};

const handleArrowClick = (type: 'prev' | 'next') => {
    if (type === 'prev') {
        arrowAnimation.value.prev = { scale: 0.9 };
        goToPrevious();
    } else {
        arrowAnimation.value.next = { scale: 0.9 };
        goToNext();
    }

    setTimeout(() => {
        arrowAnimation.value = { prev: { scale: 1 }, next: { scale: 1 } };
    }, 150);
};

const goToSlide = (index: number) => {
    direction.value = index > currentIndex.value ? 'right' : 'left';
    currentIndex.value = index;
};

onMounted(() => {
    intervalId = window.setInterval(() => {
        if (slides.value.length > 1) {
            goToNext();
        }
    }, AUTO_SLIDE_INTERVAL);
});

watchEffect((onInvalidate) => {
    onInvalidate(() => {
        clearInterval(intervalId);
    });
});

const getEnterAnimation = (dir: 'left' | 'right') => ({
    x: dir === 'right' ? '100%' : '-100%',
    opacity: 0,
});

const getCenterAnimation = () => ({
    x: 0,
    opacity: 1,
    transition: { duration: 0.6 },
});

const getExitAnimation = (dir: 'left' | 'right') => ({
    x: dir === 'right' ? '-100%' : '100%',
    opacity: 0,
    transition: { duration: 0.6 },
});
</script>
