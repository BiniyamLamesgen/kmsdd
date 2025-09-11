<template>
    <section id="branches" class="bg-gray-50 py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-20">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-4xl font-bold text-sky-500">Our Branches</h2>
                <p class="mx-auto max-w-3xl text-lg text-gray-700">Find our branch locations across Ethiopia and connect with our local teams.</p>
            </div>

            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                <!-- Branches List -->
                <div class="space-y-6">
                    <div
                        v-for="branch in paginatedBranches"
                        :key="branch.id"
                        class="rounded-xl bg-white p-6 shadow-md transition-shadow duration-300 hover:shadow-lg"
                        :class="selectedBranch?.id === branch.id ? 'bg-sky-50 ring-2 ring-sky-400' : ''"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="mb-2 flex items-center gap-2">
                                    <h3 class="text-xl font-semibold text-sky-900">{{ branch.name }}</h3>
                                    <span v-if="branch.is_main" class="rounded-full bg-sky-500 px-2 py-1 text-xs font-medium text-white">
                                        Main Branch
                                    </span>
                                </div>

                                <div v-if="branch.location" class="mb-3 flex items-start gap-2">
                                    <MapPinIcon class="mt-0.5 h-5 w-5 flex-shrink-0 text-sky-500" />
                                    <p class="text-gray-700">{{ branch.location }}</p>
                                </div>

                                <div v-if="branch.phone" class="mb-4 flex items-center gap-2">
                                    <PhoneIcon class="h-4 w-4 text-sky-500" />
                                    <a :href="`tel:${branch.phone}`" class="text-sky-600 transition-colors hover:text-sky-800">
                                        {{ branch.phone }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button
                                @click="selectBranch(branch)"
                                class="flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-sky-600"
                            >
                                <MapIcon class="h-4 w-4" />
                                View on Map
                            </button>

                            <button
                                v-if="branch.map_location"
                                @click="openInGoogleMaps(branch.map_location)"
                                class="flex items-center gap-2 rounded-lg border border-sky-500 px-4 py-2 text-sm font-medium text-sky-500 transition-colors hover:bg-sky-50"
                            >
                                <ExternalLinkIcon class="h-4 w-4" />
                                Open in Google Maps
                            </button>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="totalPages > 1"
                        class="flex items-center justify-between rounded-xl border-t border-gray-200 bg-white px-4 py-3 shadow-md sm:px-6"
                    >
                        <div class="flex flex-1 justify-between sm:hidden">
                            <button
                                @click="goToPage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Previous
                            </button>
                            <button
                                @click="goToPage(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ startIndex + 1 }}</span>
                                    to
                                    <span class="font-medium">{{ Math.min(endIndex, branches.data.length) }}</span>
                                    of
                                    <span class="font-medium">{{ branches.data.length }}</span>
                                    branches
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <!-- Previous button -->
                                    <button
                                        @click="goToPage(currentPage - 1)"
                                        :disabled="currentPage === 1"
                                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <ChevronLeftIcon class="h-5 w-5" />
                                    </button>

                                    <!-- Page numbers -->
                                    <button
                                        v-for="page in visiblePages"
                                        :key="page"
                                        @click="goToPage(page)"
                                        :class="[
                                            page === currentPage
                                                ? 'bg-sky-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600'
                                                : 'text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                                            'relative inline-flex items-center px-4 py-2 text-sm font-semibold',
                                        ]"
                                    >
                                        {{ page }}
                                    </button>

                                    <!-- Next button -->
                                    <button
                                        @click="goToPage(currentPage + 1)"
                                        :disabled="currentPage === totalPages"
                                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <ChevronRightIcon class="h-5 w-5" />
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="overflow-hidden rounded-xl bg-white shadow-md">
                    <div class="relative h-96 min-h-[400px] lg:h-full">
                        <div v-if="!selectedBranch" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                            <div class="text-center">
                                <MapIcon class="mx-auto mb-3 h-12 w-12 text-gray-400" />
                                <p class="text-gray-600">Select a branch to view its location</p>
                            </div>
                        </div>

                        <iframe
                            v-if="selectedBranch && selectedBranch.map_location"
                            :src="getEmbedMapUrl(selectedBranch.map_location)"
                            width="100%"
                            height="100%"
                            style="border: 0"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="absolute inset-0"
                        ></iframe>

                        <div
                            v-else-if="selectedBranch && !selectedBranch.map_location"
                            class="absolute inset-0 flex items-center justify-center bg-gray-100"
                        >
                            <div class="text-center">
                                <MapPinIcon class="mx-auto mb-3 h-12 w-12 text-gray-400" />
                                <p class="text-gray-600">Map location not available for this branch</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="selectedBranch" class="border-t bg-sky-50 p-4">
                        <h4 class="mb-1 font-semibold text-sky-900">{{ selectedBranch.name }}</h4>
                        <p v-if="selectedBranch.location" class="text-sm text-gray-700">{{ selectedBranch.location }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import type { ServerPaginatedResponse } from '@/types/datatable';
import { ChevronLeftIcon, ChevronRightIcon, ExternalLinkIcon, MapIcon, MapPinIcon, PhoneIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Branch {
    id: number;
    name: string;
    is_main: boolean | null;
    location: string | null;
    map_location: string | null;
    phone: string | null;
}

const props = defineProps<{
    branches: ServerPaginatedResponse<Branch>;
}>();

const selectedBranch = ref<Branch | null>(null);
const currentPage = ref(1);
const itemsPerPage = 3;

// Computed properties for pagination
const totalPages = computed(() => Math.ceil(props.branches.data.length / itemsPerPage));

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => startIndex.value + itemsPerPage);

const paginatedBranches = computed(() => props.branches.data.slice(startIndex.value, endIndex.value));

const visiblePages = computed(() => {
    const pages = [];
    const start = Math.max(1, currentPage.value - 2);
    const end = Math.min(totalPages.value, currentPage.value + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }
    return pages;
});

// Functions
const selectBranch = (branch: Branch) => {
    selectedBranch.value = branch;
};

const goToPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;

        // If the selected branch is not in the current page, select the first branch of the new page
        const currentPageBranches = paginatedBranches.value;
        if (selectedBranch.value && !currentPageBranches.find((b) => b.id === selectedBranch.value?.id)) {
            if (currentPageBranches.length > 0) {
                selectedBranch.value = currentPageBranches[0];
            }
        }
    }
};

const getEmbedMapUrl = (mapLocation: string) => {
    // Check if it's already a Google Maps embed URL
    if (mapLocation.includes('embed')) {
        return mapLocation;
    }

    // Check if it's a Google Maps share URL or standard Google Maps URL
    if (mapLocation.includes('maps.google.com') || mapLocation.includes('goo.gl') || mapLocation.includes('maps.app.goo.gl')) {
        // Extract the URL and convert to embed format
        try {
            // For URLs like: https://maps.google.com/maps?q=lat,lng
            if (mapLocation.includes('?q=')) {
                const qParam = mapLocation.split('?q=')[1].split('&')[0];
                return `https://maps.google.com/maps?q=${encodeURIComponent(qParam)}&output=embed`;
            }

            // For URLs like: https://www.google.com/maps/place/Location
            if (mapLocation.includes('/place/')) {
                const placeMatch = mapLocation.match(/\/place\/([^\/\?]+)/);
                if (placeMatch) {
                    return `https://maps.google.com/maps?q=${encodeURIComponent(placeMatch[1])}&output=embed`;
                }
            }

            // For shortened URLs, use the full URL with embed output
            return `https://maps.google.com/maps?q=${encodeURIComponent(mapLocation)}&output=embed`;
        } catch {}
    }

    // Check if it's coordinates (lat,lng format)
    const coordMatch = mapLocation.match(/(-?\d+\.?\d*),\s*(-?\d+\.?\d*)/);
    if (coordMatch) {
        const [, lat, lng] = coordMatch;
        return `https://maps.google.com/maps?q=${lat},${lng}&output=embed&z=15`;
    }

    // Check if it's a place name or address
    if (!mapLocation.startsWith('http')) {
        return `https://maps.google.com/maps?q=${encodeURIComponent(mapLocation)}&output=embed`;
    }

    // Default fallback - treat as address and use Google Maps embed without API key
    return `https://maps.google.com/maps?q=${encodeURIComponent(mapLocation)}&output=embed`;
};

const openInGoogleMaps = (mapLocation: string) => {
    // Open the original map location in a new tab
    let googleMapsUrl = mapLocation;

    // If it's not already a full URL, make it one
    if (!mapLocation.startsWith('http')) {
        googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(mapLocation)}`;
    }

    // If it's coordinates, format properly for Google Maps
    const coordMatch = mapLocation.match(/(-?\d+\.?\d*),\s*(-?\d+\.?\d*)/);
    if (coordMatch) {
        const [, lat, lng] = coordMatch;
        googleMapsUrl = `https://www.google.com/maps/@${lat},${lng},15z`;
    }

    window.open(googleMapsUrl, '_blank');
};

// Auto-select main branch if available on first page
if (props.branches.data.length > 0) {
    const mainBranch = props.branches.data.find((branch) => branch.is_main);
    const firstPageBranches = props.branches.data.slice(0, itemsPerPage);

    if (mainBranch && firstPageBranches.includes(mainBranch)) {
        selectedBranch.value = mainBranch;
    } else {
        selectedBranch.value = firstPageBranches[0] || null;
    }
}
</script>
