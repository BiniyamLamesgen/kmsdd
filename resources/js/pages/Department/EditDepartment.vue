<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref } from 'vue';
import AppLayout from '../../layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '../../components/ui/card';
import { Label } from '../../components/ui/label';
import { Input } from '../../components/ui/input';
import { Textarea } from '../../components/ui/textarea';
import { Button } from '../../components/ui/button';
import { FileText } from 'lucide-vue-next';
import InputError from '../../components/InputError.vue';
import { type BreadcrumbItem } from '../../types';
import { router } from '@inertiajs/vue3';


const props = defineProps<{
  department: {
    data: {
      id: number,
      name: string,
      description: string
    }
  }
}>();

const department = props.department.data;

const form = useForm({
  name: department.name,
  description: department.description
});

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Human Resource', href: route('dashboard') },
  { title: 'Departments', href: route('departments.index') },
  { title: 'Edit Department', href: '#' },
];

const submit = () => {
  form.post(route('departments.update', { department: department.id }), {
    onSuccess: () => {
      router.visit(route('departments.index'));
    },
  });
};

</script>

<template>
  <Head title="Edit Department" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
      <form @submit.prevent="submit" class="space-y-6">
        <Card>
          <CardHeader>
            <CardTitle>
              <FileText class="h-5 w-5 inline-block text-primary mr-2" />
              Basic Info
            </CardTitle>
            <CardDescription>Update the department details</CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div>
              <Label for="name">Name</Label>
              <Input
                id="name"
                v-model="form.name"
                :class="form.errors.name ? 'border-destructive' : ''"
              />
              <InputError :message="form.errors.name" />
            </div>
            <div>
              <Label for="description">Description</Label>
              <Textarea
                id="description"
                rows="3"
                v-model="form.description"
                :class="form.errors.description ? 'border-destructive' : ''"
              />
              <InputError :message="form.errors.description" />
            </div>
          </CardContent>
        </Card>
        <div class="flex justify-end">
          <Button type="submit" :disabled="form.processing">Update Department</Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
