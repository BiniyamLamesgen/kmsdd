<script setup lang="ts">
import { ref, computed } from "vue";

interface GalleryImage {
    id: number;
    image: string;
    alt: string | null;
    title: string;
    description: string | null;
    category: string | null;
    employee: string | null;
    date: string | null;
}

const props = defineProps<{
    gallery: {
        data: GalleryImage[];
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

// Use backend gallery images
const galleryImages = computed(() => props.gallery.data);

// Filter categories
const categories = ref([
    "All Categories",
    ...Array.from(
        new Set(
            props.gallery.data
                .map((img) => img.category)
                .filter(
                    (cat): cat is string =>
                        typeof cat === "string" && cat.length > 0
                )
        )
    ),
]);

// Reactive state
const viewMode = ref("grid");
const activeCategory = ref("All Categories");
const currentIndex = ref(0);
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

// Pagination state
const currentPage = ref(1);
const itemsPerPage = ref(8); // Show 6 images per page to make pagination more visible

// Computed properties
const filteredImages = computed(() => {
    if (activeCategory.value === "All Categories") {
        return galleryImages.value;
    }
    return galleryImages.value.filter(
        (image) => image.category === activeCategory.value
    );
});

// Paginated images for grid view
const paginatedImages = computed(() => {
    if (viewMode.value === "carousel") {
        return filteredImages.value; // Carousel shows all images
    }

    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    const result = filteredImages.value.slice(start, end);

    // Debug logging
    console.log("Pagination Debug:", {
        currentPage: currentPage.value,
        itemsPerPage: itemsPerPage.value,
        totalImages: filteredImages.value.length,
        start,
        end,
        resultCount: result.length,
        totalPages: Math.ceil(filteredImages.value.length / itemsPerPage.value),
    });

    return result;
});

// Total pages calculation
const totalPages = computed(() => {
    return Math.ceil(filteredImages.value.length / itemsPerPage.value);
});

// Pagination methods
const nextPage = () => {
    console.log(
        "nextPage called, current:",
        currentPage.value,
        "total:",
        totalPages.value
    );
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
        console.log("Page changed to:", currentPage.value);
    } else {
        console.log("Already on last page");
    }
};

const prevPage = () => {
    console.log("prevPage called, current:", currentPage.value);
    if (currentPage.value > 1) {
        currentPage.value--;
        console.log("Page changed to:", currentPage.value);
    } else {
        console.log("Already on first page");
    }
};

// Reset pagination when category changes
import { watch, onMounted, onUnmounted } from "vue";

watch(activeCategory, () => {
    currentPage.value = 1; // Reset to first page when changing category
});

watch(viewMode, (newMode) => {
    if (newMode === "carousel") {
        startAutoPlay();
    } else {
        stopAutoPlay();
    }
    currentPage.value = 1; // Reset to first page when changing view mode
});

// Methods
const openLightbox = (index: number) => {
    lightboxIndex.value = index;
    lightboxOpen.value = true;
    document.body.style.overflow = "hidden";
};

const closeLightbox = () => {
    lightboxOpen.value = false;
    document.body.style.overflow = "auto";
};

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % filteredImages.value.length;
};

const prevImage = () => {
    currentIndex.value =
        currentIndex.value === 0
            ? filteredImages.value.length - 1
            : currentIndex.value - 1;
};

const nextLightboxImage = () => {
    lightboxIndex.value =
        (lightboxIndex.value + 1) % filteredImages.value.length;
};

const prevLightboxImage = () => {
    lightboxIndex.value =
        lightboxIndex.value === 0
            ? filteredImages.value.length - 1
            : lightboxIndex.value - 1;
};

// Auto-play for carousel (optional)
let autoPlayInterval: number | null = null;

const startAutoPlay = () => {
    if (viewMode.value === "carousel") {
        autoPlayInterval = setInterval(() => {
            nextImage();
        }, 5000);
    }
};

const stopAutoPlay = () => {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null;
    }
};

// Watch for view mode changes

watch(viewMode, (newMode) => {
    if (newMode === "carousel") {
        startAutoPlay();
    } else {
        stopAutoPlay();
    }
});

const setImage = (index: number) => {
    currentIndex.value = index;
    stopAutoPlay(); // stop autoplay to prevent double increment
    startAutoPlay(); // restart fresh
};

// Keyboard navigation
const handleKeydown = (event: KeyboardEvent) => {
    if (lightboxOpen.value) {
        switch (event.key) {
            case "ArrowLeft":
                prevLightboxImage();
                break;
            case "ArrowRight":
                nextLightboxImage();
                break;
            case "Escape":
                closeLightbox();
                break;
        }
    } else if (viewMode.value === "carousel") {
        switch (event.key) {
            case "ArrowLeft":
                prevImage();
                break;
            case "ArrowRight":
                nextImage();
                break;
        }
    }
};

onMounted(() => {
    if (viewMode.value === "carousel") {
        startAutoPlay();
    }
    document.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
    stopAutoPlay();
    document.body.style.overflow = "auto";
    document.removeEventListener("keydown", handleKeydown);
});
</script>

<template>
    <section
        id="gallery"
        class="relative py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-gray-50 via-white to-blue-50 overflow-hidden"
    >
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <svg
                class="w-full h-full"
                viewBox="0 0 400 400"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <pattern
                        id="gallery-pattern"
                        x="0"
                        y="0"
                        width="40"
                        height="40"
                        patternUnits="userSpaceOnUse"
                    >
                        <circle cx="20" cy="20" r="2" fill="#3b82f6" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#gallery-pattern)" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-10 sm:mb-12 lg:mb-16">
                <div
                    class="inline-flex items-center gap-2 sm:gap-3 bg-white rounded-full px-4 sm:px-6 py-2 sm:py-3 shadow-lg border border-blue-100 mb-4 sm:mb-6"
                >
                    <div
                        class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-1.5 sm:p-2"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-blue-600 font-semibold text-sm sm:text-base"
                        >Employee Gallery</span
                    >
                </div>

                <h2
                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 px-4"
                >
                    Meet Our Amazing
                    <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent block sm:inline"
                        >Team</span
                    >
                </h2>

                <p
                    class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed px-4"
                >
                    Discover the faces behind our success. Explore our team's
                    journey through moments of collaboration, achievements, and
                    the vibrant culture that drives our organization forward.
                </p>
            </div>

            <!-- Gallery Navigation -->
            <div
                class="flex flex-col space-y-4 xl:flex-row xl:space-y-0 items-stretch xl:items-center justify-between mb-8 sm:mb-10 lg:mb-12 gap-4 xl:gap-6"
            >
                <!-- View Mode Toggle -->
                <div
                    class="flex items-center bg-white rounded-xl p-1 sm:p-1.5 shadow-lg border border-gray-200 w-full sm:w-auto sm:max-w-xs lg:max-w-none"
                >
                    <button
                        @click="viewMode = 'grid'"
                        :class="[
                            'flex-1 sm:flex-none px-2 sm:px-3 lg:px-4 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2 text-xs sm:text-sm lg:text-base min-w-0',
                            viewMode === 'grid'
                                ? 'bg-blue-500 text-white shadow-md'
                                : 'text-gray-600 hover:text-blue-500 active:text-blue-600',
                        ]"
                    >
                        <svg
                            class="w-3 h-3 sm:w-4 sm:h-4 lg:w-5 lg:h-5 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                            ></path>
                        </svg>
                        <span
                            class="hidden sm:inline lg:text-sm xl:text-base truncate"
                            >Grid View</span
                        >
                        <span class="sm:hidden text-xs">Grid</span>
                    </button>
                    <button
                        @click="viewMode = 'carousel'"
                        :class="[
                            'flex-1 sm:flex-none px-2 sm:px-3 lg:px-4 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2 text-xs sm:text-sm lg:text-base min-w-0',
                            viewMode === 'carousel'
                                ? 'bg-blue-500 text-white shadow-md'
                                : 'text-gray-600 hover:text-blue-500 active:text-blue-600',
                        ]"
                    >
                        <svg
                            class="w-3 h-3 sm:w-4 sm:h-4 lg:w-5 lg:h-5 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                        <span
                            class="hidden sm:inline lg:text-sm xl:text-base truncate"
                            >Slideshow</span
                        >
                        <span class="sm:hidden text-xs">Slides</span>
                    </button>
                </div>

                <!-- Filter Buttons -->
                <div class="w-full xl:flex-1 xl:max-w-none">
                    <div
                        class="flex flex-wrap justify-center xl:justify-end gap-2 sm:gap-3 max-w-full scrollbar-hide pb-2 sm:pb-0"
                    >
                        <button
                            v-for="category in categories"
                            :key="category"
                            @click="activeCategory = category"
                            :class="[
                                'flex-shrink-0 px-3 sm:px-4 lg:px-5 xl:px-6 py-1.5 sm:py-2 rounded-full font-medium transition-all duration-200 transform hover:scale-105 active:scale-95 text-xs sm:text-sm lg:text-base whitespace-nowrap',
                                activeCategory === category
                                    ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg ring-2 ring-blue-200'
                                    : 'bg-white text-gray-600 hover:text-blue-600 active:text-blue-700 shadow-md border border-gray-200 hover:border-blue-300 active:border-blue-400',
                            ]"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid View -->
            <div
                v-if="viewMode === 'grid'"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6"
            >
                <div
                    v-for="(image, index) in paginatedImages"
                    :key="image.id"
                    @click="
                        openLightbox(
                            filteredImages.findIndex(
                                (img) => img.id === image.id
                            )
                        )
                    "
                    class="group relative aspect-square cursor-pointer overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                >
                    <!-- Image -->
                    <img
                        :src="image.image"
                        :alt="image.alt ?? undefined"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    />

                    <!-- Overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    >
                        <div
                            class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white"
                        >
                            <h3 class="font-semibold text-sm sm:text-lg mb-1">
                                {{ image.title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-200">
                                {{ image.category }}
                            </p>
                        </div>
                    </div>

                    <!-- Hover Icon -->
                    <div
                        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    >
                        <div
                            class="bg-white/20 backdrop-blur-sm rounded-full p-3 sm:p-4"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination for Grid View -->
            <div
                v-if="viewMode === 'grid' && totalPages > 1"
                class="flex items-center justify-center mt-8 sm:mt-12 gap-4"
            >
                <!-- Previous Page Button -->
                <button
                    @click="prevPage"
                    :disabled="currentPage <= 1"
                    :class="[
                        'flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full font-medium transition-all duration-200',
                        currentPage <= 1
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-300 hover:border-blue-400 shadow-md hover:shadow-lg transform hover:scale-105',
                    ]"
                >
                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        ></path>
                    </svg>
                </button>

                <!-- Page Info -->
                <div
                    class="flex items-center gap-2 bg-white rounded-full px-4 sm:px-6 py-2 sm:py-3 shadow-lg border border-gray-200"
                >
                    <svg
                        class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                    <span
                        class="font-semibold text-gray-700 text-sm sm:text-base"
                    >
                        Page {{ currentPage }} of {{ totalPages }}
                    </span>
                </div>

                <!-- Next Page Button -->
                <button
                    @click="nextPage"
                    :disabled="currentPage >= totalPages"
                    :class="[
                        'flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full font-medium transition-all duration-200',
                        currentPage >= totalPages
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-300 hover:border-blue-400 shadow-md hover:shadow-lg transform hover:scale-105',
                    ]"
                >
                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        ></path>
                    </svg>
                </button>
            </div>

            <!-- Carousel View -->
            <div v-else-if="viewMode === 'carousel'" class="relative">
                <div
                    class="relative w-full h-[400px] sm:h-[500px] lg:h-[600px] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl bg-white"
                >
                    <!-- Main Image -->
                    <img
                        :src="filteredImages[currentIndex]?.image"
                        :alt="filteredImages[currentIndex]?.alt ?? undefined"
                        class="w-full h-full object-contain transition-all duration-500 bg-black"
                    />

                    <!-- Gradient Overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"
                    ></div>

                    <!-- Image Info -->
                    <div
                        class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 lg:p-8 text-white"
                    >
                        <div class="max-w-2xl">
                            <h3
                                class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2"
                            >
                                {{ filteredImages[currentIndex]?.title }}
                            </h3>
                            <p
                                class="text-sm sm:text-base lg:text-lg text-gray-200 mb-2 sm:mb-4"
                            >
                                {{ filteredImages[currentIndex]?.description }}
                            </p>
                            <span
                                class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium"
                            >
                                {{ filteredImages[currentIndex]?.category }}
                            </span>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button
                        @click="prevImage"
                        class="absolute left-3 sm:left-6 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-2 sm:p-3 transition-all duration-200 hover:scale-110"
                    >
                        <svg
                            class="w-5 h-5 sm:w-6 sm:h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                    </button>

                    <button
                        @click="nextImage"
                        class="absolute right-3 sm:right-6 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-2 sm:p-3 transition-all duration-200 hover:scale-110"
                    >
                        <svg
                            class="w-5 h-5 sm:w-6 sm:h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>
                </div>

                <!-- Thumbnails -->
                <div
                    class="flex justify-center mt-6 sm:mt-8 space-x-2 sm:space-x-3 overflow-x-auto pb-4 px-4 sm:px-0 scrollbar-hide"
                >
                    <button
                        v-for="(image, index) in filteredImages"
                        :key="image.id"
                        @click="setImage(index)"
                        :class="[
                            'flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-lg sm:rounded-xl overflow-hidden transition-all duration-200',
                            currentIndex === index
                                ? 'ring-4 ring-blue-500 scale-110'
                                : 'hover:scale-105 opacity-70 hover:opacity-100',
                        ]"
                    >
                        <img
                            :src="image.image"
                            :alt="image.alt ?? undefined"
                            class="w-full h-full object-cover"
                        />
                    </button>
                </div>
            </div>

            <!-- Image Counter -->
            <div class="text-center mt-6 sm:mt-8">
                <span
                    class="inline-flex items-center gap-2 bg-white rounded-full px-4 sm:px-6 py-2 sm:py-3 shadow-lg border border-gray-200"
                >
                    <svg
                        class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                    <span
                        class="font-semibold text-gray-700 text-sm sm:text-base"
                    >
                        {{
                            viewMode === "grid"
                                ? `Showing ${
                                      (currentPage - 1) * itemsPerPage + 1
                                  }-${Math.min(
                                      currentPage * itemsPerPage,
                                      filteredImages.length
                                  )} of ${filteredImages.length}`
                                : `${currentIndex + 1} / ${
                                      filteredImages.length
                                  }`
                        }}
                        {{ filteredImages.length === 1 ? "Image" : "Images" }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div
            v-if="lightboxOpen"
            @click="closeLightbox"
            class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4"
        >
            <div class="relative max-w-7xl max-h-full w-full" @click.stop>
                <img
                    :src="filteredImages[lightboxIndex]?.image"
                    :alt="filteredImages[lightboxIndex]?.alt ?? undefined"
                    class="max-w-full max-h-full object-contain rounded-lg sm:rounded-2xl shadow-2xl mx-auto"
                />

                <!-- Close Button -->
                <button
                    @click="closeLightbox"
                    class="absolute top-2 sm:top-4 right-2 sm:right-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-2 sm:p-3 transition-all duration-200"
                >
                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>

                <!-- Navigation -->
                <button
                    @click="prevLightboxImage"
                    class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-2 sm:p-3 transition-all duration-200"
                >
                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        ></path>
                    </svg>
                </button>

                <button
                    @click="nextLightboxImage"
                    class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-2 sm:p-3 transition-all duration-200"
                >
                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        ></path>
                    </svg>
                </button>

                <!-- Image Info Overlay -->
                <div
                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-4 sm:p-6 rounded-b-lg sm:rounded-b-2xl"
                >
                    <div class="text-white max-w-2xl">
                        <h3
                            class="text-lg sm:text-xl lg:text-2xl font-bold mb-1 sm:mb-2"
                        >
                            {{ filteredImages[lightboxIndex]?.title }}
                        </h3>
                        <p class="text-sm sm:text-base text-gray-200 mb-2">
                            {{ filteredImages[lightboxIndex]?.description }}
                        </p>
                        <span
                            class="inline-block px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium"
                        >
                            {{ filteredImages[lightboxIndex]?.category }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Add any additional styles here if needed */
</style>
