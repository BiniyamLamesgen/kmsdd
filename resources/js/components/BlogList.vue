<template>
    <section class="bg-gray-50 py-20" id="news">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Show blog list if no selected blog -->
            <div v-if="!selectedBlog">
                <h2 class="mb-8 text-center text-2xl font-bold text-sky-500 md:text-3xl">News</h2>

                <p v-if="!news?.data?.length" class="text-center text-gray-500">No news post available.</p>

                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="blog in news.data"
                        :key="blog.id"
                        class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all hover:border-sky-100 hover:shadow-md"
                    >
                        <div v-if="blog.image" class="relative aspect-video">
                            <img :src="getImageUrl(blog.image)" :alt="blog.title" class="h-full w-full object-cover" />
                        </div>
                        <div class="flex flex-grow flex-col p-5">
                            <h3 class="mb-2 line-clamp-2 text-lg font-semibold text-gray-800">
                                {{ blog.title }}
                            </h3>
                            <div class="prose prose-sm mb-4 line-clamp-3 text-sm text-gray-600" v-html="blog.content"></div>
                            <div class="mt-auto flex items-center justify-between">
                                <p class="text-xs text-gray-400">{{ formatDate(blog.created_at) }}</p>
                                <Link
                                    :href="route('news.public-show', { id: blog.id })"
                                    class="inline-block cursor-pointer px-5 py-1.5 text-sm leading-normal text-sky-500 transition hover:border-sky-700 hover:text-sky-700"
                                >
                                    Read More
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show blog detail if selected -->
            <div v-else>
                <button @click="goBack" class="mb-6 text-sky-500 hover:text-sky-700">&larr; Back to News</button>

                <article class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md">
                    <div v-if="selectedBlog.image" class="relative aspect-video">
                        <img :src="getImageUrl(selectedBlog.image)" :alt="selectedBlog.title" class="h-full w-full object-cover" />
                    </div>
                    <div class="p-6 md:p-8">
                        <h1 class="mb-4 text-2xl font-bold text-gray-800 md:text-3xl">
                            {{ selectedBlog.title }}
                        </h1>
                        <div class="prose mb-6 max-w-none whitespace-pre-line text-gray-700">
                            {{ selectedBlog.content }}
                        </div>
                        <p class="text-xs text-gray-400">Published on {{ formatDate(selectedBlog.created_at) }}</p>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

interface News {
    id: number;
    title: string;
    url: string | null;
    content: string;
    image: string | null;
    source: string | null;
    created_at: string | null;
    updated_at: string | null;
}

interface ServerPaginatedResponse<T> {
    data: T[];
    // ...other pagination fields
}

defineProps<{
    news: ServerPaginatedResponse<News>;
}>();

const selectedBlog = ref<News | null>(null);

function getImageUrl(image?: string | null): string {
    if (!image) return '';
    if (/^https?:\/\//.test(image)) return image;
    if (image.startsWith('/uploads')) return image;
    return `/uploads/${image}`;
}

function formatDate(dateStr: string | null) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

function goBack() {
    selectedBlog.value = null;
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
