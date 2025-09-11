<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, User, Calendar } from "lucide-vue-next";
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

interface Project {
    id: number;
    employee_id: number;
    project_name: string;
    description: string;
    role: string;
    start_date: string;
    end_date: string;
    outcome: string;
    created_at: string;
    updated_at: string;
    employee: Employee;
}

interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    position: string;
    department: string;
    email: string;
    phone: string;
    internal_extension: string;
    profile_picture: string | null;
    date_joined: string;
    linkedin: string | null;
    facebook: string | null;
    twitter: string | null;
    github: string | null;
    personal_website: string | null;
    languages: string;
    mentoring_interest: string;
    availability_for_sharing: boolean;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    project: Project;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Projects", href: route("projects.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head :title="`Project: ${props.project.project_name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('projects.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Project Details
                        </h1>
                        <p class="text-muted-foreground">
                            Information about this project
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <User class="h-5 w-5 text-primary" />
                        {{ props.project.project_name }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <User class="h-4 w-4" />
                            Description
                        </div>
                        <span class="font-mono text-sm">{{ props.project.description }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <User class="h-4 w-4" />
                            Role
                        </div>
                        <span class="font-mono text-sm">{{ props.project.role }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Start Date
                        </div>
                        <span class="font-mono text-sm">{{ props.project.start_date }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            End Date
                        </div>
                        <span class="font-mono text-sm">{{ props.project.end_date }}</span>
                    </div>
                    <Separator />
                    <div>
                        <div class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <User class="h-4 w-4" />
                            Outcome
                        </div>
                        <div class="rounded-md bg-gray-50 p-4 text-sm whitespace-pre-line text-gray-800 dark:bg-gray-900/20 dark:text-gray-200">
                            {{ props.project.outcome }}
                        </div>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Created At
                        </div>
                        <span class="font-mono text-sm">{{ props.project.created_at }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Updated At
                        </div>
                        <span class="font-mono text-sm">{{ props.project.updated_at }}</span>
                    </div>
                    <Separator />
                    <!-- Employee Info -->
                    <div>
                        <div class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <User class="h-4 w-4" />
                            Employee
                        </div>
                        <div class="flex items-center gap-4">
                            <img
                                v-if="props.project.employee.profile_picture"
                                :src="`/storage/${props.project.employee.profile_picture}`"
                                alt="Profile Picture"
                                class="h-16 w-16 rounded-full object-cover border"
                            />
                            <div>
                                <div class="font-bold">
                                    {{
                                        props.project.employee.first_name +
                                        " " +
                                        props.project.employee.last_name
                                    }}
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{ props.project.employee.position }} - {{ props.project.employee.department }}
                                </div>
                                <div class="text-sm">
                                    <a
                                        v-if="props.project.employee.linkedin"
                                        :href="props.project.employee.linkedin"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >
                                        LinkedIn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>