<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { useField } from 'vee-validate';
import { route } from 'ziggy-js';
import { object, string } from 'zod';
import { Button } from '../../components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../../components/ui/card';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import { Textarea } from '../../components/ui/textarea';
import { Separator } from '../../components/ui/separator';
import { AlertCircle, ArrowLeft, FileText, Hash, Plus } from 'lucide-vue-next';
import AppLayout from '../../layouts/AppLayout.vue';
import { type BreadcrumbItem } from '../../types';
import { useValidatedForm } from '../../composables/useValidatedForm';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Human Resource', href: route('dashboard') },
  { title: 'Departments', href: route('departments.index') },
  { title: 'Add Department', href: route('departments.create') },
];

const departmentSchema = object({
  name: string().min(2, 'Name must be at least 2 characters').max(255),
  description: string().min(3, 'Description is required').max(1000),
});

const initialValues = { name: '', description: '' };

const {
  isSubmitting,
  getBackendError,
  clearBackendError,
  hasBackendError,
  submitForm,
} = useValidatedForm(
  departmentSchema,
  initialValues,
  route('departments.store')
);

const { value: nameValue, errorMessage: nameError, handleBlur: nameBlur } = useField<string>('name');
const { value: descriptionValue, errorMessage: descriptionError, handleBlur: descriptionBlur } = useField<string>('description');

const submit = submitForm(
  () => {},
  () => {}
);
</script>

<template>
  <Head title="Add Department" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
      <div class="flex items-center gap-4 mb-8">
        <Link :href="route('departments.index')" class="border rounded p-2 hover:bg-accent">
          <ArrowLeft class="h-4 w-4" />
        </Link>
        <div>
          <h1 class="text-2xl font-bold">Create Department</h1>
          <p class="text-muted-foreground text-sm">Add a new department to your organization.</p>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <FileText class="h-5 w-5 text-primary" />
              Department Information
            </CardTitle>
            <CardDescription>Enter details for the department</CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div class="space-y-2">
              <Label for="name" class="flex items-center gap-2">
                <Hash class="h-4 w-4 text-muted-foreground" />
                Name
              </Label>
              <Input 
                id="name"
                v-model="nameValue"
                @blur="nameBlur"
                @input="clearBackendError('name')"
                placeholder="e.g. Human Resource"
                :class="(nameError || hasBackendError('name')) ? 'border-destructive' : ''"
              />
              <p v-if="nameError || hasBackendError('name')" class="text-sm text-destructive flex items-center gap-1">
                <AlertCircle class="h-3 w-3" />
                {{ nameError || getBackendError('name') }}
              </p>
            </div>

            <Separator />

            <div class="space-y-2">
              <Label for="description" class="flex items-center gap-2">
                <FileText class="h-4 w-4 text-muted-foreground" />
                Description
              </Label>
              <Textarea
                id="description"
                v-model="descriptionValue"
                @blur="descriptionBlur"
                @input="clearBackendError('description')"
                rows="3"
                placeholder="Describe the purpose and scope of this department"
                :class="(descriptionError || hasBackendError('description')) ? 'border-destructive' : ''"
              />
              <p v-if="descriptionError || hasBackendError('description')" class="text-sm text-destructive flex items-center gap-1">
                <AlertCircle class="h-3 w-3" />
                {{ descriptionError || getBackendError('description') }}
              </p>
            </div>
          </CardContent>
        </Card>

        <div class="flex justify-between">
          <Link :href="route('departments.index')">
            <Button variant="outline" type="button">Cancel</Button>
          </Link>
          <Button type="submit" :disabled="isSubmitting" class="gap-2">
            <Plus class="h-4 w-4" />
            {{ isSubmitting ? 'Saving...' : 'Save Department' }}
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
