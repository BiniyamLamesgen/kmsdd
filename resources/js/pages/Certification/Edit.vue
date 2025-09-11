<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ArrowLeft, Star, Image as LucideImage } from 'lucide-vue-next';
import { ref, onMounted, watch, computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '../../components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '../../components/ui/card';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import AppLayout from '../../layouts/AppLayout.vue';

const props = defineProps<{
    certification: {
        id: number;
        employee_id: number;
        certification_name: string;
        issuing_organization: string;
        issue_date: string | null;
        image: string | null;
        image_url: string | null;
        employee?: { first_name?: string; last_name?: string };
        created_at?: string;
        updated_at?: string;
    };
    employees: Array<{ id: number; name: string }>;
}>();

const breadcrumbs = [
    { title: "Certifications", href: route("certifications.index") },
    { title: "Edit", href: "" },
];

// Debug flag to control development-only UI (set to true for local debugging)
const DEBUG_MODE: boolean = false;

const form = useForm({
    employee_id: "",
    certification_name: "",
    issuing_organization: "",
    issue_date: "",
    image: null as File | null,
    _method: "POST", // For method spoofing in Laravel
});

// Log certification data to debug
console.log('Certification data received:', props.certification);

// Computed property for image URL with timestamp to prevent caching issues
const imageUrl = computed(() => {
    if (!props.certification?.image_url) return undefined;
    
    // Make sure the URL uses 127.0.0.1:8000 instead of localhost
    const fixedUrl = props.certification.image_url.replace('http://localhost/', 'http://127.0.0.1:8000/');
    
    // Add a timestamp query parameter to prevent browser caching
    return `${fixedUrl}?t=${Date.now()}`;
});

// Use the image_url from the certification for displaying the image
const imagePreview = ref<string | undefined>(undefined);

onMounted(() => {
    if (props.certification) {
        form.employee_id = String(props.certification.employee_id ?? "");
        form.certification_name = props.certification.certification_name ?? "";
        form.issuing_organization = props.certification.issuing_organization ?? "";
        form.issue_date = props.certification.issue_date ?? "";
        
        // Set image preview using the computed property with the fixed URL
        imagePreview.value = imageUrl.value;
        
        console.log('Setting image preview to:', imagePreview.value);
    }
});

watch(
    () => props.certification,
    (newCert) => {
        if (newCert) {
            form.employee_id = String(newCert.employee_id ?? "");
            form.certification_name = newCert.certification_name ?? "";
            form.issuing_organization = newCert.issuing_organization ?? "";
            form.issue_date = newCert.issue_date ?? "";
            
            // Update the image preview when certification changes (map null to undefined)
            imagePreview.value = newCert.image_url ?? undefined;
        }
    }
);

function handleImageChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.image = target.files[0];
        // Create a local preview URL for the new image
        imagePreview.value = URL.createObjectURL(target.files[0]);
    }
}

function handleImageError(e: Event) {
    const target = e.target as HTMLImageElement;
    console.error('Image failed to load:', imagePreview.value);
    target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1IiAvPjx0ZXh0IHg9IjQwIiB5PSI0MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg==';
}

function openImage() {
    if (!imagePreview.value) return;
    window.open(imagePreview.value, '_blank');
}

function handleSubmit() {
    form.post(route('certifications.update', props.certification.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => router.visit(route('certifications.index')),
    });
}

function handleCancel() {
    router.visit(route("certifications.index"));
}
</script>

<template>
    <Head title="Edit Certification" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-3xl space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Button variant="outline" size="sm" @click="handleCancel" class="cursor-pointer">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Certification
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update certification details and upload certificate image.
                        </p>
                    </div>
                </div>
            </div>
<!-- <pre>{{ certification }}</pre> -->
            <form @submit.prevent="handleSubmit" class="space-y-6" enctype="multipart/form-data">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Star class="h-5 w-5" />
                            <span>Certification Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Form fields -->
                        <div class="space-y-2">
                            <Label for="employee_id">Employee *</Label>
                            <select
                                id="employee_id"
                                v-model="form.employee_id"
                                :class="['w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100']"
                            >
                                <option value="" disabled>
                                    Select employee
                                </option>
                                <option
                                    v-for="emp in props.employees"
                                    :key="emp.id"
                                    :value="emp.id"
                                >
                                    {{ emp.name }}
                                </option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="certification_name">Certification Name</Label>
                            <Input
                                id="certification_name"
                                v-model="form.certification_name"
                                placeholder="Certification name"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="issuing_organization">Issuing Organization</Label>
                            <Input
                                id="issuing_organization"
                                v-model="form.issuing_organization"
                                placeholder="Organization"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="issue_date">Issue Date</Label>
                            <Input
                                id="issue_date"
                                v-model="form.issue_date"
                                type="date"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="image">Certificate Image</Label>
                            <Input
                                id="image"
                                type="file"
                                @change="handleImageChange"
                            />
                            <!-- Use a container with proper dimensions to avoid layout shift -->
                            <div class="mt-1 border rounded overflow-hidden" style="max-width: 150px; max-height: 100px;">
                                <img
                                    v-if="imagePreview"
                                    :src="imagePreview"
                                    alt="Certificate Image"
                                    class="max-w-full max-h-24 object-contain cursor-pointer hover:opacity-80"
                                    @error="handleImageError"
                                    @click="openImage"
                                    title="Click to view full size"
                                />
                                <div v-else class="w-full h-24 flex items-center justify-center text-xs text-gray-500">
                                    No image preview
                                </div>
                            </div>

                            <!-- Add a hint that user can click to view full size -->
                            <div class="mt-1 text-xs text-gray-500">
                                <span class="italic">Click image to view full size</span>
                            </div>

                            <!-- Show the current image path for debugging
                            <div class="mt-1 text-xs text-gray-500 break-all">
                                {{ certification.image }}
                            </div> -->
                        </div>
                    </CardContent>
                </Card>

                <div class="flex items-center justify-end space-x-4 border-t pt-6">
                    <Button type="button" variant="outline" @click="handleCancel" class="cursor-pointer">
                        Cancel
                    </Button>
                    <Button type="submit" :loading="form.processing" class="cursor-pointer">
                        Update Certification
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
    
    <!-- Debug section to show the URLs -->
    <div v-if="DEBUG_MODE" class="p-2 my-2 text-xs bg-gray-100 rounded">
        <p><strong>Image URL Debug Info:</strong></p>
        <p>Original image_url: {{ certification.image_url }}</p>
        <p>Computed imageUrl: {{ imageUrl }}</p>
        <p>Image Preview Value: {{ imagePreview }}</p>
    </div>
</template>
