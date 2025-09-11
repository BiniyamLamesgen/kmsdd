<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, Star } from "lucide-vue-next";
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
import { route } from "ziggy-js";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const employees = page.props.employees as Array<{
    id: number;
    first_name: string;
    last_name: string;
}>;

const breadcrumbs = [
    { title: "Projects", href: route("projects.index") },
    { title: "Create", href: "" },
];

const form = useForm<{
    employee_id: number | "";
    project_name: string;
    description: string;
    role: string;
    start_date: string;
    end_date: string;
    outcome: string;
}>({
    employee_id: "",
    project_name: "",
    description: "",
    role: "",
    start_date: "",
    end_date: "",
    outcome: "",
});

const projectSchema = z.object({
    employee_id: z.number({ required_error: "Employee is required" }),
    project_name: z.string().min(1, "Project name is required").max(255),
    description: z.string().max(5000).optional(),
    role: z.string().max(255).optional(),
    start_date: z.string().optional(),
    end_date: z.string().optional(),
    outcome: z.string().max(5000).optional(),
});

const handleSubmit = () => {
    form.errors = {};
    // Convert employee_id to number if possible
    if (form.employee_id === "") {
        form.errors.employee_id = "Employee is required";
        return;
    }
    const result = projectSchema.safeParse({
        ...form.data(),
        employee_id: Number(form.employee_id),
    });
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] = err.message;
            }
        });
        return;
    }
    form.post(route("projects.store"), {
        onSuccess: () => {
            router.visit(route("projects.index"));
        },
    });
};

const handleCancel = () => {
    router.visit(route("projects.index"));
};
</script>

<template>
    <Head title="Create Project" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="handleCancel"
                        class="w-fit cursor-pointer"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100">
                            Create Project
                        </h1>
                        <p class="text-sm text-gray-600 sm:text-base dark:text-gray-400">
                            Add a new project for an employee.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Star class="h-5 w-5" />
                            <span>Project Information</span>
                        </CardTitle>
                        <CardDescription>
                            Enter the details for the new project.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                            <div class="space-y-2">
                                <Label for="employee_id">Employee *</Label>
                                <select
                                    id="employee_id"
                                    v-model="form.employee_id"
                                    :class="['w-full rounded border', form.errors.employee_id ? 'border-red-500' : 'border-gray-300']"
                                >
                                    <option value="">Select employee</option>
                                    <option
                                        v-for="emp in employees"
                                        :key="emp.id"
                                        :value="emp.id"
                                    >
                                        {{ emp.first_name }} {{ emp.last_name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.employee_id" class="text-sm text-red-600">{{ form.errors.employee_id }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="project_name">Project Name *</Label>
                                <Input
                                    id="project_name"
                                    v-model="form.project_name"
                                    placeholder="Project name"
                                    :class="{ 'border-red-500': form.errors.project_name }"
                                    class="w-full"
                                />
                                <p v-if="form.errors.project_name" class="text-sm text-red-600">{{ form.errors.project_name }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="role">Role</Label>
                                <Input
                                    id="role"
                                    v-model="form.role"
                                    placeholder="Role in project"
                                    :class="{ 'border-red-500': form.errors.role }"
                                    class="w-full"
                                />
                                <p v-if="form.errors.role" class="text-sm text-red-600">{{ form.errors.role }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="start_date">Start Date</Label>
                                <Input
                                    id="start_date"
                                    type="date"
                                    v-model="form.start_date"
                                    :class="{ 'border-red-500': form.errors.start_date }"
                                    class="w-full"
                                />
                                <p v-if="form.errors.start_date" class="text-sm text-red-600">{{ form.errors.start_date }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="end_date">End Date</Label>
                                <Input
                                    id="end_date"
                                    type="date"
                                    v-model="form.end_date"
                                    :class="{ 'border-red-500': form.errors.end_date }"
                                    class="w-full"
                                />
                                <p v-if="form.errors.end_date" class="text-sm text-red-600">{{ form.errors.end_date }}</p>
                            </div>
                            <div class="space-y-2 md:col-span-2 lg:col-span-3">
                                <Label for="description">Description</Label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Project description"
                                    rows="3"
                                    :class="['w-full rounded border px-3 py-2', form.errors.description ? 'border-red-500' : 'border-gray-300']"
                                ></textarea>
                                <p v-if="form.errors.description" class="text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>
                            <div class="space-y-2 md:col-span-2 lg:col-span-3">
                                <Label for="outcome">Outcome</Label>
                                <textarea
                                    id="outcome"
                                    v-model="form.outcome"
                                    placeholder="Project outcome"
                                    rows="2"
                                    :class="['w-full rounded border px-3 py-2', form.errors.outcome ? 'border-red-500' : 'border-gray-300']"
                                ></textarea>
                                <p v-if="form.errors.outcome" class="text-sm text-red-600">{{ form.errors.outcome }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Form Actions -->
                <div class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4">
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleCancel"
                        class="order-2 w-full cursor-pointer sm:order-1 sm:w-auto"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :loading="form.processing"
                        class="order-1 w-full cursor-pointer sm:order-2 sm:w-auto"
                    >
                        Create Project
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>