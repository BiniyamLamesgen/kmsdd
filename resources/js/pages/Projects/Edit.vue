<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, Clock } from "lucide-vue-next";
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
import AppLayout from "../../layouts/AppLayout.vue";

const props = defineProps<{
    project: {
        id: number;
        employee_id: number;
        project_name: string;
        description: string | null;
        role: string | null;
        start_date: string | null;
        end_date: string | null;
        outcome: string | null;
        employee?: {
            id: number;
            first_name: string;
            last_name: string;
            position: string;
            department: string;
            profile_picture?: string | null;
            email?: string | null;
        };
    };
    employees: Array<{
        id: number;
        first_name: string;
        last_name: string;
        position: string;
        department: string;
    }>;
}>();

const breadcrumbs = [
    { title: "Projects", href: route("projects.index") },
    { title: "Edit", href: "" },
];

const form = useForm({
    project_name: "",
    description: "",
    role: "",
    start_date: "",
    end_date: "",
    outcome: "",
    employee_id: "",
});

onMounted(() => {
    if (props.project) {
        form.project_name = props.project.project_name ?? "";
        form.description = props.project.description ?? "";
        form.role = props.project.role ?? "";
        form.start_date = props.project.start_date ?? "";
        form.end_date = props.project.end_date ?? "";
        form.outcome = props.project.outcome ?? "";
        form.employee_id = String(props.project.employee_id ?? "");
    }
});

watch(
    () => props.project,
    (newProject) => {
        if (newProject) {
            form.project_name = newProject.project_name ?? "";
            form.description = newProject.description ?? "";
            form.role = newProject.role ?? "";
            form.start_date = newProject.start_date ?? "";
            form.end_date = newProject.end_date ?? "";
            form.outcome = newProject.outcome ?? "";
            form.employee_id = String(newProject.employee_id ?? "");
        }
    }
);

const projectSchema = z.object({
    project_name: z.string().min(1, "Project name is required").max(255),
    description: z.string().max(5000).optional(),
    role: z.string().max(255).optional(),
    start_date: z
        .string()
        .optional()
        .refine((val) => !val || /^\d{4}-\d{2}-\d{2}$/.test(val), {
            message: "Start date must be a valid date (YYYY-MM-DD)",
        }),
    end_date: z
        .string()
        .optional()
        .refine((val) => !val || /^\d{4}-\d{2}-\d{2}$/.test(val), {
            message: "End date must be a valid date (YYYY-MM-DD)",
        }),
    outcome: z.string().max(5000).optional(),
    employee_id: z
        .union([z.string(), z.number()])
        .refine((val) => String(val).length > 0, {
            message: "Employee is required",
        }),
});

const handleSubmit = () => {
    const result = projectSchema.safeParse(form.data());
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] =
                    err.message;
            }
        });
        return;
    }
    const payload = {
        ...form.data(),
        employee_id: Number(form.employee_id),
    };
    form.post(route("projects.update", props.project.id), {
        ...payload,
        preserveScroll: true,
        onSuccess: () => router.visit(route("projects.index")),
        onError: (errors: Record<string, string>) =>
            console.log("Form errors:", errors),
    });
};

const handleCancel = () => {
    router.visit(route("projects.index"));
};
</script>

<template>
    <Head title="Edit Project" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-3xl space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="handleCancel"
                        class="cursor-pointer"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Edit Project
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update project details like name, description,
                            dates, and employee.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Employee Info Card -->
            <Card v-if="props.project.employee">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        Employee
                    </CardTitle>
                    <CardDescription> Project assigned to: </CardDescription>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        v-if="props.project.employee.profile_picture"
                        :src="`${props.project.employee.profile_picture}`"
                        alt="Profile"
                        class="h-12 w-12 rounded-full object-cover border"
                    />
                    <div>
                        <div class="font-semibold">
                            {{ props.project.employee.first_name }}
                            {{ props.project.employee.last_name }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ props.project.employee.position }} -
                            {{ props.project.employee.department }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ props.project.employee.email }}
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Clock class="h-5 w-5" />
                            <span>Project Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="project_name">Project Name *</Label>
                            <Input
                                id="project_name"
                                v-model="form.project_name"
                                placeholder="Project name"
                                :class="{
                                    'border-red-500': form.errors.project_name,
                                }"
                            />
                            <p
                                v-if="form.errors.project_name"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.project_name }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Input
                                id="description"
                                v-model="form.description"
                                placeholder="Project description"
                                :class="{
                                    'border-red-500': form.errors.description,
                                }"
                            />
                            <p
                                v-if="form.errors.description"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.description }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="role">Role</Label>
                            <Input
                                id="role"
                                v-model="form.role"
                                placeholder="Role in project"
                                :class="{ 'border-red-500': form.errors.role }"
                            />
                            <p
                                v-if="form.errors.role"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.role }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="start_date">Start Date</Label>
                            <Input
                                id="start_date"
                                type="date"
                                v-model="form.start_date"
                                :class="{
                                    'border-red-500': form.errors.start_date,
                                }"
                            />
                            <p
                                v-if="form.errors.start_date"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.start_date }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="end_date">End Date</Label>
                            <Input
                                id="end_date"
                                type="date"
                                v-model="form.end_date"
                                :class="{
                                    'border-red-500': form.errors.end_date,
                                }"
                            />
                            <p
                                v-if="form.errors.end_date"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.end_date }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="outcome">Outcome</Label>
                            <Input
                                id="outcome"
                                v-model="form.outcome"
                                placeholder="Project outcome"
                                :class="{
                                    'border-red-500': form.errors.outcome,
                                }"
                            />
                            <p
                                v-if="form.errors.outcome"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.outcome }}
                            </p>
                        </div>
                        <!-- Replace employee dropdown with read-only display -->
                        <div class="space-y-2">
                            <Label for="employee_id">Employee</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.project.employee">
                                    {{ props.project.employee.first_name }}
                                    {{ props.project.employee.last_name }} ({{
                                        props.project.employee.position
                                    }}, {{ props.project.employee.department }})
                                </span>
                                <span v-else>
                                    Employee ID: {{ form.employee_id }}
                                </span>
                            </div>
                            <!-- Keep error display for validation -->
                            <p
                                v-if="form.errors.employee_id"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.employee_id }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Actions -->
                <div
                    class="flex items-center justify-end space-x-4 border-t pt-6"
                >
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleCancel"
                        class="cursor-pointer"
                        >Cancel</Button
                    >
                    <Button
                        type="submit"
                        :loading="form.processing"
                        class="cursor-pointer"
                        >Update Project</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>
