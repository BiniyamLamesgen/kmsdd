<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, Calendar, User } from "lucide-vue-next";
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

interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    position: string;
    department: string;
}

interface Education {
    id: number;
    employee_id: number;
    field_of_study: string;
    institution: string;
    graduation_year: string;
    created_at: string;
    updated_at: string;
    employee: Employee;
}

const props = defineProps<{
    education: Education;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Education", href: route("educations.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head title="Education Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('educations.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Education Details
                        </h1>
                        <p class="text-muted-foreground">
                            Education information
                        </p>
                    </div>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <User class="h-5 w-5 text-primary" />
                        {{ props.education.institution }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <User class="h-4 w-4" />
                            Employee
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.education.employee.first_name }}
                            {{ props.education.employee.last_name }} ({{
                                props.education.employee.position
                            }}, {{ props.education.employee.department }})
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Graduation Year
                        </div>
                        <span class="font-mono text-sm">{{
                            props.education.graduation_year
                        }}</span>
                    </div>
                    <Separator />
                    <div>
                        <div class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <User class="h-4 w-4" />
                            Field of Study
                        </div>
                        <div class="rounded-md bg-gray-50 p-4 text-sm whitespace-pre-line text-gray-800 dark:bg-gray-900/20 dark:text-gray-200">
                            {{ props.education.field_of_study }}
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
