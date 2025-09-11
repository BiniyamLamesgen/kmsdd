<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface Blog {
    id: number;
    title: string;
    content: string;
    image?: string;
    createdAt: string;
}

const props = defineProps<{ blog: Blog }>();

const formatDate = (dateStr: string) =>
    new Date(dateStr).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
</script>

<template>
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <Link href="/news" class="group mb-6 flex items-center text-sm font-medium text-sky-500 transition-colors hover:text-sky-700">
                <svg
                    class="mr-2 h-4 w-4 transition-transform group-hover:-translate-x-1"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to News
            </Link>

            <article class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md">
                <div v-if="props.blog.image" class="relative aspect-video">
                    <img
                        :src="props.blog.image.startsWith('/uploads') ? props.blog.image : `/uploads/${props.blog.image}`"
                        :alt="props.blog.title"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div class="p-6 md:p-8">
                    <h1 class="mb-4 text-2xl font-bold text-gray-800 md:text-3xl">
                        {{ props.blog.title }}
                    </h1>
                    <div class="prose mb-6 max-w-none whitespace-pre-line text-gray-700">
                        {{ props.blog.content }}
                    </div>
                    <p class="text-xs text-gray-400">Published on {{ formatDate(props.blog.createdAt) }}</p>
                </div>
            </article>
        </div>
    </section>
</template>
