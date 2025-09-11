<script lang="ts" setup>
import type { ServerPaginatedResponse } from '@/types/datatable';

type Member = {
    id: number;
    title: string | null;
    name: string;
    position: string | null;
    type: string | null;
    branch?: string | null;
    branch_id?: number | null;
};

type Branch = {
    id: number;
    name: string;
    is_main: boolean | null;
    location: string | null;
    map_location: string | null;
    phone: string | null;
};

type Bio = {
    id: number;
    name: string;
    title: string;
    bio_text: string | null;
    photo: string | null;
    quote: string | null;
};

const props = defineProps<{
    bios: ServerPaginatedResponse<Bio>;
    members: ServerPaginatedResponse<Member>;
    branches: ServerPaginatedResponse<Branch>;
}>();
</script>
<template>
    <section class="mx-auto mt-12 max-w-6xl rounded-3xl border border-gray-100 bg-white p-6 shadow-xl sm:p-10">
        <!-- Header -->
        <h1 class="mb-8 bg-gradient-to-r from-sky-600 to-sky-500 bg-clip-text text-center text-3xl font-bold text-transparent sm:text-4xl">
            Ethiopian Kidney Association
        </h1>

        <!-- Bio Section -->
        <div class="mb-12">
            <div v-for="bio in props.bios.data" :key="bio.id" class="mb-8">
                <div class="flex flex-col items-center gap-6 md:flex-row">
                    <!-- Bio Photo -->
                    <div v-if="bio.photo" class="flex-shrink-0">
                        <img :src="bio.photo" :alt="bio.name" class="h-32 w-32 rounded-full object-cover shadow-lg" />
                    </div>

                    <!-- Bio Content -->
                    <div class="flex-1">
                        <div class="prose prose-lg text-gray-700">
                            <span class="text-xl font-bold text-sky-700">{{ bio.name }}</span>
                            <span v-if="bio.bio_text" class="ml-1" v-html="bio.bio_text"></span>
                        </div>

                        <!-- Quote -->
                        <div v-if="bio.quote" class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
                            <p class="text-gray-700 italic">&quot;{{ bio.quote }}&quot;</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Section -->
        <div class="grid gap-8 md:grid-cols-2">
            <!-- Board Members -->
            <div
                class="rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50 p-6 shadow-sm transition-all duration-300 hover:shadow-lg"
            >
                <div class="mb-6 flex items-center">
                    <div class="mr-3 h-10 w-1 rounded-full bg-sky-400"></div>
                    <h2 class="text-2xl font-bold text-sky-700">Board Members</h2>
                </div>
                <ul class="space-y-4">
                    <li v-for="member in props.members.data.filter((m) => m.type === 'Board Member')" :key="member.id" class="group flex items-start">
                        <div class="mt-2 mr-3 h-2 w-2 rounded-full bg-sky-300"></div>
                        <div>
                            <span class="font-medium text-gray-800 transition-colors group-hover:text-sky-700"
                                >{{ member.title || '' }} {{ member.name }}</span
                            >
                            <span v-if="member.position" class="rounded-full bg-sky-100 px-2 py-0.5 text-sm font-medium text-sky-600">{{
                                member.position
                            }}</span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Branch Members -->
            <div
                class="rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50 p-6 shadow-sm transition-all duration-300 hover:shadow-lg"
            >
                <div class="mb-6 flex items-center">
                    <div class="mr-3 h-10 w-1 rounded-full bg-sky-400"></div>
                    <h2 class="text-2xl font-bold text-sky-700">Branch Members</h2>
                </div>
                <ul class="space-y-4">
                    <li
                        v-for="member in props.members.data.filter((m) => m.type === 'Branch Member')"
                        :key="member.id"
                        class="group flex items-start"
                    >
                        <div class="mt-2 mr-3 h-2 w-2 rounded-full bg-sky-300"></div>
                        <div>
                            <span class="font-medium text-gray-800 transition-colors group-hover:text-sky-700"
                                >{{ member.title || '' }} {{ member.name }}</span
                            >
                            <span v-if="member.position" class="rounded-full bg-sky-100 px-2 py-0.5 text-sm font-medium text-sky-600">{{
                                member.position
                            }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>
