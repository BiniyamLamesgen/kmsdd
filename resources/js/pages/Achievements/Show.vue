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
    email_verified_at: string | null;
}

interface Achievement {
    id: number;
    employee_id: number;
    title: string;
    description: string;
    date_awarded: string;
    created_at: string;
    updated_at: string;
    employee: Employee;
}

const props = defineProps<{
    achievement: Achievement;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Achievements", href: route("achievements.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head title="Achievement Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('achievements.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Achievement Details
                        </h1>
                        <p class="text-muted-foreground">
                            Achievement information
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <User class="h-5 w-5 text-primary" />
                        {{ props.achievement.title }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Employee
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.achievement.employee.first_name }}
                            {{ props.achievement.employee.last_name }} ({{
                                props.achievement.employee.position
                            }}, {{ props.achievement.employee.department }})
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Date Awarded
                        </div>
                        <span class="font-mono text-sm">{{
                            props.achievement.date_awarded
                        }}</span>
                    </div>
                    <Separator />
                    <div>
                        <div
                            class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Description
                        </div>
                        <div
                            class="rounded-md bg-gray-50 p-4 text-sm whitespace-pre-line text-gray-800 dark:bg-gray-900/20 dark:text-gray-200"
                        >
                            {{ props.achievement.description }}
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
