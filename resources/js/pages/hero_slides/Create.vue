<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { AlertCircle, FileText, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const breadcrumbs = [
    { title: 'Hero Slides', href: route('hero-slides.index') },
    { title: 'Create Hero Slide', href: route('hero-slides.create') },
];

// Simple Inertia form
const form = useForm({
    title: '',
    description: '',
    order: 0,
    image: null as File | null,
});

const imagePreview = ref<string | null>(null);
const imageInputRef = ref<HTMLInputElement | null>(null);
const imageError = ref<string | null>(null);

function handleImageChange(e: Event) {
    const files = (e.target as HTMLInputElement).files;
    imageError.value = null;

    if (files && files[0]) {
        const file = files[0];

        // Basic client-side validation
        if (!file.type.startsWith('image/')) {
            imageError.value = 'Only image files are allowed';
            form.image = null;
            imagePreview.value = null;
            return;
        }

        form.image = file;
        const reader = new FileReader();
        reader.onload = (ev) => {
            imagePreview.value = ev.target?.result as string;
        };
        reader.readAsDataURL(file);
    } else {
        form.image = null;
        imagePreview.value = null;
    }
}

const submit = () => {
    // Clear previous image error
    imageError.value = null;

    // Basic validation
    if (!form.title.trim()) {
        return;
    }

    // Submit form
    form.post(route('hero-slides.store'), {
        onSuccess: () => {
            // Redirect handled by server
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};
</script>

<template>
    <Head title="Create Hero Slide" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-3xl px-4 py-8">
            <!-- Image at Top with Upload Button -->
            <div class="relative mb-6">
                <img v-if="imagePreview" :src="imagePreview" alt="Hero Slide Image" class="h-64 w-full rounded-t object-cover" />
                <button
                    type="button"
                    @click="imageInputRef?.click()"
                    class="absolute top-4 right-4 z-10 flex cursor-pointer items-center justify-center rounded-full border-4 border-black bg-white p-2 shadow-xl"
                    style="background-color: rgba(255, 255, 255, 0.98); box-shadow: 0 2px 12px 2px rgba(0, 0, 0, 0.25)"
                >
                    <FileText class="h-5 w-5 text-black" />
                    <span class="sr-only">Upload Image</span>
                </button>
                <input ref="imageInputRef" id="image" type="file" accept="image/*" @change="handleImageChange" class="hidden" />
                <p v-if="imageError || form.errors.image" class="mt-2 flex items-center gap-1 text-sm text-destructive">
                    <AlertCircle class="h-3 w-3" />
                    {{ imageError || form.errors.image }}
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <Card class="w-full">
                    <CardHeader>
                        <CardTitle>Create Hero Slide</CardTitle>
                        <CardDescription>Enter all details for the new hero slide</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="title">Title <span class="text-red-600">*</span></Label>
                                <Input
                                    id="title"
                                    v-model="form.title"
                                    placeholder="Hero slide title"
                                    :class="form.errors.title ? 'border-destructive' : ''"
                                    required
                                />
                                <p v-if="form.errors.title" class="mb-2 flex items-center gap-1 text-sm text-destructive">
                                    <AlertCircle class="h-3 w-3" />
                                    {{ form.errors.title }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="order">Order</Label>
                                <Input
                                    id="order"
                                    v-model.number="form.order"
                                    type="number"
                                    min="0"
                                    placeholder="Order"
                                    :class="form.errors.order ? 'border-destructive' : ''"
                                />
                                <p v-if="form.errors.order" class="mb-2 flex items-center gap-1 text-sm text-destructive">
                                    <AlertCircle class="h-3 w-3" />
                                    {{ form.errors.order }}
                                </p>
                            </div>
                        </div>
                        <div class="my-4 space-y-4">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Description"
                                rows="4"
                                class="w-full"
                                :class="form.errors.description ? 'border-destructive' : ''"
                            />
                            <p v-if="form.errors.description" class="mb-2 flex items-center gap-1 text-sm text-destructive">
                                <AlertCircle class="h-3 w-3" />
                                {{ form.errors.description }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end">
                    <Link :href="route('hero-slides.index')">
                        <Button variant="outline" type="button">Cancel</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing || !form.title.trim()" class="ml-2 gap-2">
                        <Plus class="h-4 w-4" />
                        <span v-if="form.processing">Creating...</span>
                        <span v-else>Create Hero Slide</span>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
