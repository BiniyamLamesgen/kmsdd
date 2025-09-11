<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Head, Link } from '@inertiajs/vue3';
import { useField } from 'vee-validate';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { z } from 'zod';

import { useValidatedForm } from '@/composables/useValidatedForm';
import AppLayout from '@/layouts/AppLayout.vue';
import { AlertCircle } from 'lucide-vue-next';

interface HeroSlide {
    id: number;
    title: string;
    description?: string | null;
    image?: string | null;
    order?: number | null;
    created_at: string;
    updated_at: string;
}

interface Response<T> {
    data: T;
}

const props = defineProps<{
    heroSlide: Response<HeroSlide>;
}>();

const breadcrumbs = [
    { title: 'Hero Slides', href: route('hero-slides.index') },
    { title: 'Edit Hero Slide', href: '#' },
];

const heroSlideSchema = z.object({
    title: z.string().min(3, 'Title must be at least 3 characters').max(255, 'Title must not exceed 255 characters'),
    description: z.string().min(10, 'Description must be at least 10 characters').max(1000, 'Description must not exceed 1000 characters').optional(),
    image: z
        .union([
            z
                .string()
                .optional()
                .refine((val) => !val || /^.*\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(val), {
                    message: 'Image must be a valid image file (jpg, jpeg, png, gif, bmp, webp)',
                }),
            z
                .instanceof(File, { message: 'Image must be a file' })
                .refine((file) => ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'].includes(file.type), {
                    message: 'Only image files are allowed',
                }),
        ])
        .optional(),
    order: z.coerce.number().int().min(0, 'Order must be a positive integer').optional(),
});

type HeroSlideForm = z.infer<typeof heroSlideSchema>;

const initialValues: Partial<HeroSlideForm> = {
    title: props.heroSlide.data.title,
    description: props.heroSlide.data.description ?? '',
    image: undefined,
    order: props.heroSlide.data.order ?? 0,
};

const imageFile = ref<File | null>(null);
const imageInputRef = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(null);

const validatedForm = useValidatedForm(heroSlideSchema, initialValues, route('hero-slides.update', { heroSlide: props.heroSlide.data.id }));
const { isSubmitting, getBackendError, clearBackendError, hasBackendError, submitForm } = validatedForm;

const { value: titleValue, errorMessage: titleError, handleBlur: titleBlur } = useField<string>('title');
const { value: descriptionValue, errorMessage: descriptionError, handleBlur: descriptionBlur } = useField<string>('description');
const { value: imageValue } = useField<File | null>('image');
const { value: orderValue, errorMessage: orderError, handleBlur: orderBlur } = useField<number>('order');

function handleImageChange(e: Event) {
    const files = (e.target as HTMLInputElement).files;
    if (files && files[0]) {
        const result = heroSlideSchema.shape.image.safeParse(files[0]);
        if (!result.success) {
            imageFile.value = null;
            imageValue.value = null;
            imagePreview.value = null;
            return;
        }
        imageFile.value = files[0];
        imageValue.value = files[0];
        const reader = new FileReader();
        reader.onload = (ev) => {
            imagePreview.value = ev.target?.result as string;
        };
        reader.readAsDataURL(files[0]);
    } else {
        imageFile.value = null;
        imageValue.value = null;
        imagePreview.value = null;
    }
}

const submit = submitForm(
    () => {},
    () => {},
    (data: any) => {
        if (imageFile.value instanceof File) {
            (data as any).image = imageFile.value;
        } else {
            delete (data as any).image;
        }
        return data;
    },
);
</script>
<template>
    <Head :title="'Edit Hero Slide'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-3xl px-4 py-8">
            <!-- Image at Top with Edit Button -->
            <div v-if="imagePreview || props.heroSlide.data.image" class="relative mb-6">
                <img
                    :src="imagePreview !== null ? imagePreview : (props.heroSlide.data.image ?? undefined)"
                    alt="Hero Slide Image"
                    class="h-64 w-full rounded-t object-cover"
                />
                <button
                    type="button"
                    @click="imageInputRef?.click()"
                    class="absolute top-4 right-4 z-10 flex cursor-pointer items-center justify-center rounded-full border-4 border-black bg-white p-2 shadow-xl"
                    style="background-color: rgba(255, 255, 255, 0.98); box-shadow: 0 2px 12px 2px rgba(0, 0, 0, 0.25)"
                >
                    <FileText class="h-5 w-5 text-black" />
                    <span class="sr-only">Edit Image</span>
                </button>
                <input ref="imageInputRef" id="image" type="file" accept="image/*" @change="handleImageChange" class="hidden" />
            </div>
            <form @submit.prevent="submit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <FileText class="h-5 w-5 text-primary" />
                            Basic Information
                        </CardTitle>
                        <CardDescription> Update the basic details of this hero slide </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="title">Title <span class="text-red-600">*</span></Label>
                                <Input
                                    id="title"
                                    v-model="titleValue"
                                    @blur="titleBlur"
                                    @input="clearBackendError('title')"
                                    placeholder="Hero slide title"
                                    :class="titleError || hasBackendError('title') ? 'border-destructive' : ''"
                                />
                                <p v-if="titleError || hasBackendError('title')" class="mb-2 flex items-center gap-1 text-sm text-destructive">
                                    <AlertCircle class="h-3 w-3" />
                                    {{ titleError || getBackendError('title') }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="order">Order</Label>
                                <Input
                                    id="order"
                                    v-model="orderValue"
                                    @blur="orderBlur"
                                    @input="clearBackendError('order')"
                                    type="number"
                                    min="0"
                                    placeholder="Order"
                                    :class="orderError || hasBackendError('order') ? 'border-destructive' : ''"
                                />
                                <p v-if="orderError || hasBackendError('order')" class="mb-2 flex items-center gap-1 text-sm text-destructive">
                                    <AlertCircle class="h-3 w-3" />
                                    {{ orderError || getBackendError('order') }}
                                </p>
                            </div>
                        </div>
                        <div class="my-4 space-y-4">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="descriptionValue"
                                @blur="descriptionBlur"
                                @input="clearBackendError('description')"
                                placeholder="Description"
                                rows="4"
                                class="w-full"
                                :class="descriptionError || hasBackendError('description') ? 'border-destructive' : ''"
                            />
                            <p
                                v-if="descriptionError || hasBackendError('description')"
                                class="mb-2 flex items-center gap-1 text-sm text-destructive"
                            >
                                <AlertCircle class="h-3 w-3" />
                                {{ descriptionError || getBackendError('description') }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex items-center justify-between pt-6">
                    <Link :href="route('hero-slides.index')">
                        <Button variant="outline" type="button"> Cancel </Button>
                    </Link>
                    <Button type="submit" :disabled="isSubmitting" class="gap-2">
                        <Save class="h-4 w-4" />
                        {{ isSubmitting ? 'Updating...' : 'Update Hero Slide' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
