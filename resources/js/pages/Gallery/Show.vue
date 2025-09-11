<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, Image, Calendar } from "lucide-vue-next";
import { route } from "ziggy-js";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from "../../components/ui/card";
import { Separator } from "../../components/ui/separator";
import AppLayout from "../../layouts/AppLayout.vue";
import { type BreadcrumbItem } from "../../types";

interface Gallery {
    id: number;
    image: string;
    alt: string | null;
    title: string;
    description: string | null;
    category: string | null;
    employee: string | null;
    date: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    gallery: {
        data: Gallery;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Gallery", href: route("gallery.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head title="Gallery Image Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-3xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('gallery.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Gallery Image Details
                        </h1>
                        <p class="text-muted-foreground">
                            Gallery image information
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Image class="h-5 w-5 text-primary" />
                        {{ props.gallery.data.title }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex justify-center mb-6">
                        <img
                            v-if="props.gallery.data.image"
                            :src="props.gallery.data.image"
                            alt="Gallery Image"
                            class="h-40 w-40 rounded object-cover border"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            Alt
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.alt
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            Title
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.title
                        }}</span>
                    </div>
                    <Separator />
                    <div>
                        <div
                            class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            Description
                        </div>
                        <div
                            class="rounded-md bg-gray-50 p-4 text-sm whitespace-pre-line text-gray-800 dark:bg-gray-900/20 dark:text-gray-200"
                        >
                            {{ props.gallery.data.description }}
                        </div>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            Category
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.category
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            Employee
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.employee
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Date
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.date
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Created At
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.created_at
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Updated At
                        </div>
                        <span class="font-mono text-sm">{{
                            props.gallery.data.updated_at
                        }}</span>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
