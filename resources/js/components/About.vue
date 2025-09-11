<template>
    <section id="about" class="mx-auto max-w-7xl bg-white px-6 py-20 sm:px-10 lg:px-20">
        <div v-for="about in props.abouts.data" :key="about.id" class="mx-auto mb-12 flex max-w-6xl flex-col items-center gap-12 lg:flex-row">
            <!-- Image Section -->
            <div class="w-full flex-1">
                <div class="relative h-80 w-full overflow-hidden rounded-xl shadow-lg transition-shadow duration-500 hover:shadow-2xl sm:h-96">
                    <img
                        :src="about.image || 'https://via.placeholder.com/400x300?text=No+Image'"
                        alt="About Image"
                        class="h-full w-full object-cover"
                        loading="lazy"
                    />
                </div>
            </div>

            <!-- Text Section -->
            <div class="flex-1 text-center lg:text-left">
                <h2 class="mb-6 text-2xl leading-tight font-extrabold text-sky-500 sm:text-3xl lg:text-4xl">About Us</h2>

                <p class="mb-5 text-base leading-relaxed text-gray-700 sm:text-lg">
                    {{ expandedItems[about.id] ? about.content : truncateText(about.content, 150) }}
                </p>

                <!-- Toggle Button -->
                <button
                    v-if="about.content.length > 150"
                    @click="toggleExpanded(about.id)"
                    class="mt-4 inline-block cursor-pointer rounded-full border border-sky-500 px-5 py-2 text-sm font-semibold text-sky-500 transition hover:bg-sky-500 hover:text-white sm:text-base"
                >
                    {{ expandedItems[about.id] ? 'See Less' : 'Read More' }}
                </button>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { reactive } from 'vue';

interface About {
    id: number;
    content: string;
    image: string | null;
}

const props = defineProps<{
    abouts: {
        data: About[];
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

// Track expanded state for each about item
const expandedItems = reactive<Record<number, boolean>>({});

// Function to toggle expanded state
const toggleExpanded = (id: number) => {
    expandedItems[id] = !expandedItems[id];
};

// Function to truncate text
const truncateText = (text: string, maxLength: number): string => {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength).trim() + '...';
};
</script>
