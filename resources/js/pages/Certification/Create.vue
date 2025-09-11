<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Award, Calendar, Check, FileText, Image as ImageIcon, Upload, Users } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import { z } from 'zod';

import AppLayout from '../../layouts/AppLayout.vue';
import { Button } from '../../components/ui/button';
import { Input } from '../../components/ui/input';
import { 
  Card, 
  CardContent, 
  CardDescription, 
  CardHeader, 
  CardTitle 
} from '../../components/ui/card';
import { Label } from '../../components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../../components/ui/select';
import { BreadcrumbItem } from '../../types';
import { useToast } from '../../composables/useToast';

interface Employee {
  id: number;
  name?: string;
  first_name?: string;
  last_name?: string;
}

const props = defineProps<{
  employees: Employee[];
}>();


const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Certifications', href: route('certifications.index') },
  { title: 'Create Certification', href: route('certifications.create') },
];

const form = useForm({
  employee_id: '',
  certification_name: '',
  issuing_organization: '',
  issue_date: '',
  image: null as File | null,
});

const fileInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(null);
const isDragging = ref(false);

// Validation schema using Zod
const certificationSchema = z.object({
  employee_id: z.union([z.string(), z.number()]).refine((val) => String(val).length > 0, {
    message: "Employee is required",
  }),
  certification_name: z.string().min(1, "Certification name is required").max(255),
  issuing_organization: z.string().min(1, "Issuing organization is required").max(255),
  issue_date: z.string().optional(),
  // We'll validate the image separately since it's not a string/number
});

// Function to trigger file input click
const triggerFileInputClick = () => {
  if (fileInput.value) {
    fileInput.value.click();
  }
};

const handleImageChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (!target.files?.length) return;
  
  const file = target.files[0];
  if (!file) return;
// Clear previous image errors
form.errors.image = undefined;

// Validate file type
const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
if (!validTypes.includes(file.type.toLowerCase())) {
    form.errors.image = 'Please upload a valid image file (JPEG, PNG, or GIF)';
    return;
}

// Validate file size (max 2MB)
if (file.size > 2 * 1024 * 1024) {
    form.errors.image = 'Image must be less than 2MB';
    return;
}
  form.image = file;
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const handleDrop = (e: DragEvent) => {
  e.preventDefault();
  e.stopPropagation();
  isDragging.value = false;
  
  if (!e.dataTransfer?.files.length) return;
const file = e.dataTransfer.files[0];
if (!file) return;

// Clear previous image errors
form.errors.image = undefined;

// Validate file type
const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
if (!validTypes.includes(file.type.toLowerCase())) {
    form.errors.image = 'Please upload a valid image file (JPEG, PNG, or GIF)';
    return;
}

// Validate file size (max 2MB)
if (file.size > 2 * 1024 * 1024) {
    form.errors.image = 'Image must be less than 2MB';
    return;
}
  
  form.image = file;
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const handleDragOver = (e: DragEvent) => {
  e.preventDefault();
  e.stopPropagation();
  isDragging.value = true;
};

const handleDragLeave = (e: DragEvent) => {
  e.preventDefault();
  e.stopPropagation();
  isDragging.value = false;
};

const removeImage = (e: Event) => {
  e.stopPropagation(); // Prevent the click from bubbling to the parent div
  form.image = null;
  imagePreview.value = null;
  if (fileInput.value) {
    fileInput.value.value = '';
  }
};

const handleImageError = (e: Event) => {
  const target = e.target;
  if (target instanceof HTMLImageElement) {
    // fallback SVG displayed when the image fails to load
    target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1IiAvPjx0ZXh0IHg9IjQwIiB5PSI0MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg==';
  }
};

const handleSubmit = () => {
  // Validate with Zod
  const result = certificationSchema.safeParse({
    employee_id: form.employee_id,
    certification_name: form.certification_name,
    issuing_organization: form.issuing_organization,
    issue_date: form.issue_date,
  });

  form.errors = {};
  
  if (!result.success) {
    result.error.issues.forEach((err) => {
      if (err.path[0]) {
        form.errors[err.path[0] as keyof typeof form.errors] = err.message;
      }
    });
    return;
  }
form.post(route('certifications.store'), {
    onSuccess: () => {
        router.visit(route('certifications.index'));
    },
    onError: () => {
        // Intentionally no toast notifications; server validation errors will be applied to form.errors by Inertia
    },
    preserveScroll: true,
});
};

const handleCancel = () => {
router.visit(route('certifications.index'));
};

const isDirty = computed(() => {
  return form.employee_id !== '' || 
         form.certification_name !== '' || 
         form.issuing_organization !== '' || 
         form.issue_date !== '' || 
         form.image !== null;
});
</script>

<template>
  <Head title="Create Certification" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
      <!-- Page Header -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
          <Button variant="outline" size="sm" @click="handleCancel" class="w-fit cursor-pointer">
            <ArrowLeft class="mr-2 h-4 w-4" />
            Back
          </Button>
          <div>
            <h1 class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100">
              Create Certification
            </h1>
            <p class="text-sm text-gray-600 sm:text-base dark:text-gray-400">
              Add a new certification record to your organization.
            </p>
          </div>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:gap-8 xl:grid-cols-2">
          <!-- Certification Information Card -->
          <Card class="xl:col-span-2">
            <CardHeader>
              <CardTitle class="flex items-center space-x-2">
                <Award class="h-5 w-5" />
                <span>Certification Information</span>
              </CardTitle>
              <CardDescription>
                Enter the details for the new certification record.
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                <!-- Employee Selection -->
                <div class="space-y-2">
                  <Label for="employee_id">Employee *</Label>
                  <select
                    id="employee_id"
                    v-model="form.employee_id"
                    :class="[
                      'w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100',
                      { 'border-red-500': form.errors.employee_id },
                    ]"
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
                  <p v-if="form.errors.employee_id" class="text-sm text-red-600">
                    {{ form.errors.employee_id }}
                  </p>
                </div>

                <!-- Certification Name -->
                <div class="space-y-2">
                  <Label for="certification_name">Certification Name *</Label>
                  <Input
                    id="certification_name"
                    v-model="form.certification_name"
                    placeholder="e.g. AWS Certified Solutions Architect"
                    :class="{ 'border-red-500': form.errors.certification_name }"
                    class="w-full"
                  />
                  <p v-if="form.errors.certification_name" class="text-sm text-red-600">
                    {{ form.errors.certification_name }}
                  </p>
                </div>

                <!-- Issuing Organization -->
                <div class="space-y-2">
                  <Label for="issuing_organization">Issuing Organization *</Label>
                  <Input
                    id="issuing_organization"
                    v-model="form.issuing_organization"
                    placeholder="e.g. Amazon Web Services"
                    :class="{ 'border-red-500': form.errors.issuing_organization }"
                    class="w-full"
                  />
                  <p v-if="form.errors.issuing_organization" class="text-sm text-red-600">
                    {{ form.errors.issuing_organization }}
                  </p>
                </div>

                <!-- Issue Date -->
                <div class="space-y-2">
                  <Label for="issue_date">Issue Date</Label>
                  <Input
                    id="issue_date"
                    v-model="form.issue_date"
                    type="date"
                    :class="{ 'border-red-500': form.errors.issue_date }"
                    class="w-full"
                  />
                  <p v-if="form.errors.issue_date" class="text-sm text-red-600">
                    {{ form.errors.issue_date }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Certificate Image Card -->
          <Card class="xl:col-span-2">
            <CardHeader>
              <CardTitle class="flex items-center space-x-2">
                <FileText class="h-5 w-5" />
                <span>Certificate Image</span>
              </CardTitle>
              <CardDescription>
                Upload an image of the certification document (optional).
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div class="flex flex-col md:flex-row gap-6">
                <!-- Image Upload Area -->
                <div class="flex-1">
                  <input
                    id="image"
                    ref="fileInput"
                    type="file"
                    class="hidden"
                    accept="image/*"
                    @change="handleImageChange"
                  />
                  <div 
                    :class="[
                      'border-2 border-dashed rounded-lg p-6 transition-all cursor-pointer text-center',
                      isDragging 
                        ? 'border-primary bg-primary/5 dark:border-primary/70 dark:bg-primary/10' 
                        : 'border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900/50',
                      imagePreview ? 'border-green-300 bg-green-50 dark:border-green-800 dark:bg-green-900/20' : ''
                    ]"
                    @click="triggerFileInputClick"
                    @dragover="handleDragOver"
                    @dragleave="handleDragLeave"
                    @drop="handleDrop"
                  >
                    <div class="flex flex-col items-center justify-center py-4 text-gray-500 dark:text-gray-400">
                      <div v-if="!imagePreview" class="mb-3">
                        <Upload class="h-10 w-10 mb-2 mx-auto text-gray-400" />
                        <p class="text-sm font-medium">Drag and drop your certificate image here</p>
                        <p class="text-xs mt-1">or click to browse files</p>
                        <p class="text-xs mt-3 text-gray-400">PNG, JPG or JPEG (max 2MB)</p>
                      </div>
                      
                      <div v-else class="w-full">
                        <div class="flex items-center justify-center mb-3">
                          <Check class="h-6 w-6 text-green-500" />
                          <span class="ml-2 text-green-600 dark:text-green-400 font-medium">Image uploaded successfully</span>
                        </div>
                        <p class="text-xs">Click or drag another file to replace</p>
                        
                        <Button 
                          type="button" 
                          variant="outline" 
                          size="sm" 
                          class="mt-3 cursor-pointer text-red-600 border-red-200 hover:border-red-300 hover:bg-red-50"
                          @click.stop="removeImage"
                        >
                          Remove Image
                        </Button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Image Preview -->
                <div v-if="imagePreview" class="w-full md:w-1/3 lg:w-1/4">
                  <div class="rounded-lg border overflow-hidden bg-gray-50 dark:bg-gray-900">
                    <div class="p-2 bg-gray-100 dark:bg-gray-800 border-b text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center">
                      <ImageIcon class="inline h-3 w-3 mr-1" />
                      <span>Certificate Preview</span>
                    </div>
                    <div class="p-3 flex items-center justify-center">
                      <img 
                        :src="imagePreview" 
                        alt="Certificate Preview" 
                        class="max-w-full max-h-48 object-contain rounded"
                        @error="(e) => {
                          e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1IiAvPjx0ZXh0IHg9IjQwIiB5PSI0MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg=='
                        }"
                      />
                    </div>
                  </div>
                </div>
              </div>
              
              <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                {{ form.errors.image }}
              </p>
            </CardContent>
          </Card>
        </div>

        <!-- Submit Buttons -->
        <div class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4">
          <Button type="button" variant="outline" @click="handleCancel" class="order-2 w-full cursor-pointer sm:order-1 sm:w-auto">
            Cancel
          </Button>
          <Button
            type="submit"
            :loading="form.processing"
            class="order-1 w-full cursor-pointer sm:order-2 sm:w-auto"
          >
            Create Certification
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
