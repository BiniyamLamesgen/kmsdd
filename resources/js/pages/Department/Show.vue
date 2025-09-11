
<!-- ShowDepartment.vue -->
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AppLayout from '../../layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent, CardDescription } from '../../components/ui/card';
import { ArrowLeft, FileText, Hash, Edit } from 'lucide-vue-next';
import { Button } from '../../components/ui/button';
import { Badge } from '../../components/ui/badge';
import { type BreadcrumbItem } from '../../types';

const props = defineProps<{ department: { id: number, name: string, description: string } }>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Human Resource', href: route('dashboard') },
  { title: 'Departments', href: route('departments.index') },
  { title: 'Department Details', href: '#' },
];
</script>

<template>
  <Head title="Department Details" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <Link :href="route('departments.index')">
            <Button variant="outline" size="icon">
              <ArrowLeft class="h-4 w-4" />
            </Button>
          </Link>
          <div>
            <h1 class="text-3xl font-bold">{{ props.department.name }}</h1>
            <p class="text-muted-foreground">Department details overview</p>
          </div>
        </div>
        <Link :href="route('departments.edit', { department: props.department.id })">
          <Button class="gap-2">
            <Edit class="h-4 w-4" />
            Edit
          </Button>
        </Link>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>
            <FileText class="h-5 w-5 text-primary inline-block mr-2" />
            Department Info
          </CardTitle>
          <CardDescription>Core department information</CardDescription>
        </CardHeader>
        <CardContent class="space-y-6">
          <div class="flex justify-between text-sm">
            <div class="font-medium text-muted-foreground flex items-center gap-2">
              <Hash class="h-4 w-4" />
              ID:
            </div>
            <div class="font-mono">{{ props.department.id }}</div>
          </div>
          <div class="flex justify-between text-sm">
            <div class="font-medium text-muted-foreground flex items-center gap-2">
              <FileText class="h-4 w-4" />
              Name:
            </div>
            <div class="font-semibold">{{ props.department.name }}</div>
          </div>
          <div class="text-sm">
            <div class="font-medium text-muted-foreground mb-1">Description:</div>
            <p class="leading-relaxed">{{ props.department.description || 'No description provided.' }}</p>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
