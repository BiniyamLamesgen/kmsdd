<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, User, Mail, Calendar } from "lucide-vue-next";
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
    full_name?: string;
    position: string;
    department:
        | string
        | {
              id: number;
              name: string;
              description: string;
              created_at: string;
              updated_at: string;
              deleted_at: string | null;
          };
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
    employee: Employee;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Employees", href: route("employees.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head title="Employee Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-3xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('employees.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Employee Details
                        </h1>
                        <p class="text-muted-foreground">
                            Employee information
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <User class="h-5 w-5 text-primary" />
                        {{
                            props.employee.full_name ??
                            props.employee.first_name +
                                " " +
                                props.employee.last_name
                        }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex justify-center mb-6">
                        <img
                            v-if="props.employee.profile_picture"
                            :src="`/storage/${props.employee.profile_picture}`"
                            alt="Profile Picture"
                            class="h-32 w-32 rounded-full object-cover border"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Position
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.position
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Department
                        </div>
                        <span class="font-mono text-sm">
                            {{
                                typeof props.employee.department === "object"
                                    ? props.employee.department.name
                                    : props.employee.department
                            }}
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Mail class="h-4 w-4" />
                            Email
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.email
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Phone
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.phone
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Internal Extension
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.internal_extension
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Date Joined
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.date_joined
                        }}</span>
                    </div>
                    <Separator />
                    <div>
                        <div
                            class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Social Links
                        </div>
                        <div class="space-y-1 text-sm">
                            <div v-if="props.employee.linkedin">
                                LinkedIn:
                                <a
                                    :href="props.employee.linkedin"
                                    target="_blank"
                                    class="text-blue-600 underline"
                                    >{{ props.employee.linkedin }}</a
                                >
                            </div>
                            <div v-if="props.employee.facebook">
                                Facebook:
                                <a
                                    :href="props.employee.facebook"
                                    target="_blank"
                                    class="text-blue-600 underline"
                                    >{{ props.employee.facebook }}</a
                                >
                            </div>
                            <div v-if="props.employee.twitter">
                                Twitter:
                                <a
                                    :href="props.employee.twitter"
                                    target="_blank"
                                    class="text-blue-600 underline"
                                    >{{ props.employee.twitter }}</a
                                >
                            </div>
                            <div v-if="props.employee.github">
                                GitHub:
                                <a
                                    :href="props.employee.github"
                                    target="_blank"
                                    class="text-blue-600 underline"
                                    >{{ props.employee.github }}</a
                                >
                            </div>
                            <div v-if="props.employee.personal_website">
                                Website:
                                <a
                                    :href="props.employee.personal_website"
                                    target="_blank"
                                    class="text-blue-600 underline"
                                    >{{ props.employee.personal_website }}</a
                                >
                            </div>
                        </div>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Languages
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.languages
                        }}</span>
                    </div>
                    <Separator />
                    <div>
                        <div
                            class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Mentoring Interest
                        </div>
                        <div
                            class="rounded-md bg-gray-50 p-4 text-sm whitespace-pre-line text-gray-800 dark:bg-gray-900/20 dark:text-gray-200"
                        >
                            {{ props.employee.mentoring_interest }}
                        </div>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Available for Sharing
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.availability_for_sharing
                                ? "Yes"
                                : "No"
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Created At
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.created_at
                        }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Updated At
                        </div>
                        <span class="font-mono text-sm">{{
                            props.employee.updated_at
                        }}</span>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
