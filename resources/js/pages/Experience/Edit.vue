<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, Star } from "lucide-vue-next";
import { onMounted, watch } from "vue";
import { route } from "ziggy-js";
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
import { Textarea } from "../../components/ui/textarea";
import AppLayout from "../../layouts/AppLayout.vue";
import { BreadcrumbItem } from "../../types";

interface Employee {
  id: number;
  first_name?: string;
  last_name?: string;
  position?: string;
  department?: string;
}

interface Experience {
  id: number;
  employee_id: number;
  company_name: string;
  position: string;
  start_date?: string;
  end_date?: string;
  responsibilities?: string;
}

const props = defineProps<{
  experience: Experience;
  employees: Employee[];
  success: boolean;
}>();



const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Experiences', href: route('experiences.index') },
  { title: 'Edit Experience', href: route('experiences.edit', props.experience.id) },
];

// Initialize the form (send real PUT via form.put when submitting)
const form = useForm({
  _method: 'PUT', // method spoofing for Laravel
  employee_id: String(props.experience.employee_id || ''),
  company_name: props.experience.company_name || '',
  position: props.experience.position || '',
  start_date: props.experience.start_date || '',
  end_date: props.experience.end_date || '',
  responsibilities: props.experience.responsibilities || '',
});

// Helper function to get employee full name
const getEmployeeFullName = (employee: Employee) => {
  return `${employee.first_name || ''} ${employee.last_name || ''}`.trim();
};

// Update form when experience changes
onMounted(() => {
  if (props.experience) {
    form.employee_id = String(props.experience.employee_id || '');
    form.company_name = props.experience.company_name || '';
    form.position = props.experience.position || '';

    form.start_date = props.experience.start_date || '';
    form.end_date = props.experience.end_date || '';
    form.responsibilities = props.experience.responsibilities || '';
  }
});

watch(
  () => props.experience,
  (newExperience) => {
    if (newExperience) {
      form.employee_id = String(newExperience.employee_id || '');
      form.company_name = newExperience.company_name || '';
      form.position = newExperience.position || '';

      form.start_date = newExperience.start_date || '';
      form.end_date = newExperience.end_date || '';
      form.responsibilities = newExperience.responsibilities || '';
    }
  }
);

// Validation schema
const experienceSchema = z.object({
  employee_id: z.union([z.string(), z.number()]).refine((val) => String(val).length > 0, {
    message: "Employee is required",
  }),
  company_name: z.string().min(1, "Company name is required").max(255),
  position: z.string().min(1, "Position is required").max(255),
  start_date: z.string().optional(),
  end_date: z.string().optional(),
  responsibilities: z.string().optional(),
});

// Submit form - send as POST with _method override so the server accepts the request
const handleSubmit = () => {
  const result = experienceSchema.safeParse(form.data());
  form.errors = {};

  if (!result.success) {
    result.error.issues.forEach((err) => {
      if (err.path[0]) {
        form.errors[err.path[0] as keyof typeof form.errors] = err.message;
      }
    });
    return;
  }

  form.post(route('experiences.update', props.experience.id), {
    preserveScroll: true,
    onSuccess: () => {
      router.visit(route('experiences.index'));
    },
    onError: (errors) => console.log('Form errors:', errors),
  });
}
// Cancel form
const handleCancel = () => {
  router.visit(route("experiences.index"));
};
</script>

<template>
  <Head title="Edit Experience" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="mx-auto max-w-3xl space-y-6 p-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <Button variant="outline" size="sm" @click="handleCancel" class="cursor-pointer">
            <ArrowLeft class="mr-2 h-4 w-4" />
            Back
          </Button>
          <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
              Edit Experience
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
              Update experience details like company, position, dates, and responsibilities.
            </p>
          </div>
        </div>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Star class="h-5 w-5" />
              <span>Experience Information</span>
            </CardTitle>
            <CardDescription>
              Update the fields below and click save.
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <!-- Employee Field -->
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
                  {{ getEmployeeFullName(emp) }} {{ emp.position ? `(${emp.position}` : '' }}{{ emp.department ? `, ${emp.department})` : emp.position ? ')' : '' }}
                </option>
              </select>
              <p v-if="form.errors.employee_id" class="text-sm text-red-600">
                {{ form.errors.employee_id }}
              </p>
            </div>

            <!-- Company Name Field -->
            <div class="space-y-2">
              <Label for="company_name">Company Name *</Label>
              <Input
                id="company_name"
                v-model="form.company_name"
                placeholder="Company name"
                :class="{ 'border-red-500': form.errors.company_name }"
              />
              <p v-if="form.errors.company_name" class="text-sm text-red-600">
                {{ form.errors.company_name }}
              </p>
            </div>

            <!-- Position Field -->
            <div class="space-y-2">
              <Label for="position">Position *</Label>
              <Input
                id="position"
                v-model="form.position"
                placeholder="Position"
                :class="{ 'border-red-500': form.errors.position }"
              />
              <p v-if="form.errors.position" class="text-sm text-red-600">
                {{ form.errors.position }}
              </p>
            </div>

            <!-- Date Fields -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <!-- Start Date Field -->
              <div class="space-y-2">
                <Label for="start_date">Start Date</Label>
                <Input
                  id="start_date"
                  v-model="form.start_date"
                  type="date"
                  :class="{ 'border-red-500': form.errors.start_date }"
                />
                <p v-if="form.errors.start_date" class="text-sm text-red-600">
                  {{ form.errors.start_date }}
                </p>
              </div>

              <!-- End Date Field -->
              <div class="space-y-2">
                <Label for="end_date">End Date</Label>
                <Input
                  id="end_date"
                  v-model="form.end_date"
                  type="date"
                  :class="{ 'border-red-500': form.errors.end_date }"
                />
                <p v-if="form.errors.end_date" class="text-sm text-red-600">
                  {{ form.errors.end_date }}
                </p>
              </div>
            </div>

            <!-- Responsibilities Field -->
            <div class="space-y-2">
              <Label for="responsibilities">Responsibilities</Label>
              <Textarea
                id="responsibilities"
                v-model="form.responsibilities"
                rows="5"
                placeholder="Describe your responsibilities"
                :class="{ 'border-red-500': form.errors.responsibilities }"
              />
              <p v-if="form.errors.responsibilities" class="text-sm text-red-600">
                {{ form.errors.responsibilities }}
              </p>
            </div>
          </CardContent>
        </Card>

        <div class="flex items-center justify-end space-x-4 border-t pt-6">
          <Button type="button" variant="outline" @click="handleCancel" class="cursor-pointer">
            Cancel
          </Button>
          <Button type="submit" :loading="form.processing" class="cursor-pointer">
            Update Experience
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
