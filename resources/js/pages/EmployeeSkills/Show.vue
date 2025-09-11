<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, User, Calendar, Award } from "lucide-vue-next";
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

interface Skill {
    id: number;
    skill_name: string;
    created_at: string;
    updated_at: string;
}

interface EmployeeSkill {
    id: number;
    employee_id: number;
    skill_id: number;
    proficiency_level: string;
    endorsements_count: number;
    created_at: string;
    updated_at: string;
    employee: Employee;
    skill: Skill;
}

const props = defineProps<{
    employeeSkill: EmployeeSkill;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Employee Skills", href: route("employee-skills.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head title="Employee Skill Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('employee-skills.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Employee Skill Details
                        </h1>
                        <p class="text-muted-foreground">
                            Skill information for employee
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
                            props.employeeSkill.employee.full_name ??
                            props.employeeSkill.employee.first_name +
                                " " +
                                props.employeeSkill.employee.last_name
                        }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex justify-center mb-6">
                        <img
                            v-if="props.employeeSkill.employee.profile_picture"
                            :src="`/storage/${props.employeeSkill.employee.profile_picture}`"
                            alt="Profile Picture"
                            class="h-32 w-32 rounded-full object-cover border"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Award class="h-4 w-4" />
                            Skill
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.employeeSkill.skill.skill_name }}
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Award class="h-4 w-4" />
                            Proficiency Level
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.employeeSkill.proficiency_level }}
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Award class="h-4 w-4" />
                            Endorsements
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.employeeSkill.endorsements_count }}
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Created At
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.employeeSkill.created_at }}
                        </span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Updated At
                        </div>
                        <span class="font-mono text-sm">
                            {{ props.employeeSkill.updated_at }}
                        </span>
                    </div>
                    <Separator />
                    <!-- Employee Info Section -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Employee Info</h2>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Position
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.position }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Department
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.department }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Email
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.email }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Phone
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.phone }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Internal Extension
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.internal_extension }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <Calendar class="h-4 w-4" />
                                Date Joined
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.date_joined }}
                            </span>
                        </div>
                        <Separator />
                        <div>
                            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Social Links
                            </div>
                            <div class="space-y-1 text-sm">
                                <div v-if="props.employeeSkill.employee.linkedin">
                                    LinkedIn:
                                    <a
                                        :href="props.employeeSkill.employee.linkedin"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >{{ props.employeeSkill.employee.linkedin }}</a>
                                </div>
                                <div v-if="props.employeeSkill.employee.facebook">
                                    Facebook:
                                    <a
                                        :href="props.employeeSkill.employee.facebook"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >{{ props.employeeSkill.employee.facebook }}</a>
                                </div>
                                <div v-if="props.employeeSkill.employee.twitter">
                                    Twitter:
                                    <a
                                        :href="props.employeeSkill.employee.twitter"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >{{ props.employeeSkill.employee.twitter }}</a>
                                </div>
                                <div v-if="props.employeeSkill.employee.github">
                                    GitHub:
                                    <a
                                        :href="props.employeeSkill.employee.github"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >{{ props.employeeSkill.employee.github }}</a>
                                </div>
                                <div v-if="props.employeeSkill.employee.personal_website">
                                    Website:
                                    <a
                                        :href="props.employeeSkill.employee.personal_website"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >{{ props.employeeSkill.employee.personal_website }}</a>
                                </div>
                            </div>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Languages
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.languages }}
                            </span>
                        </div>
                        <Separator />
                        <div>
                            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Mentoring Interest
                            </div>
                            <div class="rounded-md bg-gray-50 p-4 text-sm whitespace-pre-line text-gray-800 dark:bg-gray-900/20 dark:text-gray-200">
                                {{ props.employeeSkill.employee.mentoring_interest }}
                            </div>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <User class="h-4 w-4" />
                                Available for Sharing
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.availability_for_sharing ? "Yes" : "No" }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <Calendar class="h-4 w-4" />
                                Created At
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.created_at }}
                            </span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <Calendar class="h-4 w-4" />
                                Updated At
                            </div>
                            <span class="font-mono text-sm">
                                {{ props.employeeSkill.employee.updated_at }}
                            </span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>