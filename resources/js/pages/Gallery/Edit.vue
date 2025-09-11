<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, UploadCloud, Star } from "lucide-vue-next";
import { z } from "zod";
import { Button } from "../../components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "../../components/ui/card";
import { Input } from "../../components/ui/input";
import { Label } from "../../components/ui/label";
import AppLayout from "../../layouts/AppLayout.vue";
import { route } from "ziggy-js";
import { ref } from "vue";

const props = defineProps<{
    gallery: {
        data: {
            id: number;
            image: string;
            alt: string | null;
            title: string;
            description: string | null;
            category: string | null;
            employee: string | null;
            date: string | null;
        };
    };
}>();

const breadcrumbs = [
    { title: "Gallery", href: route("gallery.index") },
    { title: "Edit Image", href: "" },
];

const imagePreview = ref<string | null>(
    props.gallery.data.image ? props.gallery.data.image : null
);
const imageInputRef = ref<HTMLInputElement | null>(null);
const imageError = ref<string | null>(null);

const form = useForm<{
    image: File | null;
    alt: string;
    title: string;
    description: string;
    category: string;
    employee: string;
    date: string;
}>({
    image: null,
    alt: props.gallery.data.alt ?? "",
    title: props.gallery.data.title ?? "",
    description: props.gallery.data.description ?? "",
    category: props.gallery.data.category ?? "",
    employee: props.gallery.data.employee ?? "",
    date: props.gallery.data.date ?? "",
});

const gallerySchema = z.object({
    image: z
        .any()
        .refine(
            (val) => {
                if (val === null) return true;
                if (!(val instanceof File)) return false;
                const allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/jpg",
                    "image/gif",
                    "image/svg+xml",
                ];
                return allowedTypes.includes(val.type);
            },
            {
                message: "The image field must be an image.",
            }
        )
        .optional(),
    alt: z.string().max(255).optional(),
    title: z.string().min(1, "Title is required").max(255),
    description: z.string().max(5000).optional(),
    category: z.string().max(255).optional(),
    employee: z.string().max(255).optional(),
    date: z.string().optional(),
});

function handleImageChange(e: Event) {
    const target = e.target as HTMLInputElement;
    imageError.value = null;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        if (!file.type.startsWith("image/")) {
            imageError.value = "Only image files are allowed";
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
        imagePreview.value = props.gallery.data.image
            ? props.gallery.data.image
            : null;
    }
}

const handleSubmit = () => {
    const result = gallerySchema.safeParse(form.data());
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] =
                    err.message;
            }
        });
        return;
    }
    const data = new FormData();
    Object.entries(form.data()).forEach(([key, value]) => {
        if (key === "image" && value instanceof File) {
            data.append("image", value);
        } else if (key !== "image" && value !== undefined && value !== null) {
            data.append(key, value as any);
        }
    });
    form.post(route("gallery.update", props.gallery.data.id), data);
};

const handleCancel = () => {
    router.visit(route("gallery.index"));
};
</script>

<template>
    <Head title="Edit Gallery Image" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div
                    class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4"
                >
                    <Button
                        variant="outline"
                        size="sm"
                        @click="handleCancel"
                        class="w-fit cursor-pointer"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1
                            class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100"
                        >
                            Edit Gallery Image
                        </h1>
                        <p
                            class="text-sm text-gray-600 sm:text-base dark:text-gray-400"
                        >
                            Update the gallery image details.
                        </p>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="handleSubmit"
                class="space-y-6"
                enctype="multipart/form-data"
            >
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Star class="h-5 w-5" />
                            <span>Image Information</span>
                        </CardTitle>
                        <CardDescription>
                            Edit the details for the gallery image.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6"
                        >
                            <div class="space-y-2">
                                <Label for="title">Title *</Label>
                                <Input
                                    id="title"
                                    v-model="form.title"
                                    placeholder="Title"
                                    :class="{
                                        'border-red-500': form.errors.title,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.title"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.title }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="description">Description</Label>
                                <Input
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Description"
                                    :class="{
                                        'border-red-500':
                                            form.errors.description,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.description"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.description }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="category">Category</Label>
                                <Input
                                    id="category"
                                    v-model="form.category"
                                    placeholder="Category"
                                    :class="{
                                        'border-red-500': form.errors.category,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.category"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.category }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="employee">Employee</Label>
                                <Input
                                    id="employee"
                                    v-model="form.employee"
                                    placeholder="Employee"
                                    :class="{
                                        'border-red-500': form.errors.employee,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.employee"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.employee }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="date">Date</Label>
                                <Input
                                    id="date"
                                    type="date"
                                    v-model="form.date"
                                    :class="{
                                        'border-red-500': form.errors.date,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.date"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.date }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="alt">Alt Text</Label>
                                <Input
                                    id="alt"
                                    v-model="form.alt"
                                    placeholder="Alt text"
                                    :class="{
                                        'border-red-500': form.errors.alt,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.alt"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.alt }}
                                </p>
                            </div>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="mt-8 border-t pt-6">
                            <div class="space-y-4">
                                <Label>Image File</Label>
                                <div
                                    @click="imageInputRef?.click()"
                                    @dragover.prevent
                                    @drop.prevent="handleImageChange"
                                    class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 transition-colors duration-200 bg-gray-50 hover:bg-blue-50 min-h-[120px] flex flex-col items-center justify-center"
                                >
                                    <input
                                        ref="imageInputRef"
                                        type="file"
                                        accept="image/*"
                                        @change="handleImageChange"
                                        class="hidden"
                                    />
                                    <div v-if="imagePreview" class="mb-4">
                                        <img
                                            :src="imagePreview"
                                            alt="Preview"
                                            class="max-h-40 rounded border"
                                        />
                                    </div>
                                    <div class="text-center">
                                        <p
                                            class="text-lg font-medium text-gray-700"
                                        >
                                            {{
                                                imagePreview
                                                    ? "Change Image"
                                                    : "Upload Image"
                                            }}
                                        </p>
                                        <UploadCloud
                                            class="w-12 h-12 text-gray-400 mx-auto rotate-0 mb-2"
                                        />
                                        <p class="text-sm text-gray-500 mt-1">
                                            Drag and drop an image here, or
                                            click to select
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Supports: JPG, PNG, GIF, SVG (Max:
                                            2MB)
                                        </p>
                                    </div>
                                </div>
                                <p
                                    v-if="imageError || form.errors.image"
                                    class="text-sm text-red-600"
                                >
                                    {{ imageError || form.errors.image }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Form Actions -->
                <div
                    class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4"
                >
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleCancel"
                        class="order-2 w-full cursor-pointer sm:order-1 sm:w-auto"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :loading="form.processing"
                        class="order-1 w-full cursor-pointer sm:order-2 sm:w-auto"
                    >
                        <UploadCloud class="mr-2 h-4 w-4" />
                        Update Gallery
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
