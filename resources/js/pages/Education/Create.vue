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

const props = defineProps<{
    employees: Array<{
        id: number;
        first_name: string;
        last_name: string;
        position: string;
        department: string;
    }>;
}>();

const breadcrumbs = [
    { title: "Education", href: route("educations.index") },
    { title: "Create", href: "" },
];

const form = useForm({
    field_of_study: "",
    institution: "",
    graduation_year: "",
    employee_id: "",
});

const educationSchema = z.object({
    field_of_study: z.string().max(255).optional(),
    institution: z.string().max(255).optional(),
    graduation_year: z.string().optional().refine((val) => !val || /^\d{4}$/.test(val), {
        message: "Graduation year must be a valid year (YYYY)",
    }),
    employee_id: z.union([z.string(), z.number()]).refine((val) => String(val).length > 0, {
        message: "Employee is required",
    }),
});

const handleSubmit = () => {
    const result = educationSchema.safeParse(form.data());
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] = err.message;
            }
        });
        return;
    }
    const payload = {
        ...form.data(),
        employee_id: Number(form.employee_id),
    };
    form.post(route("educations.store"), {
        ...payload,
        onSuccess: () => {
            router.visit(route("educations.index"));
        },
    });
};

const handleCancel = () => {
    router.visit(route("educations.index"));
};
</script>

<template>
    <Head title="Create Education" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                    <Button variant="outline" size="sm" @click="handleCancel" class="w-fit cursor-pointer">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100">
                            Create Education
                        </h1>
                        <p class="text-sm text-gray-600 sm:text-base dark:text-gray-400">
                            Add a new education record to your organization.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 lg:gap-8 xl:grid-cols-2">
                    <Card class="xl:col-span-2">
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Star class="h-5 w-5" />
                                <span>Education Information</span>
                            </CardTitle>
                            <CardDescription>
                                Enter the details for the new education record.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                                <div class="space-y-2">
                                    <Label for="field_of_study">Field of Study</Label>
                                    <Input
                                        id="field_of_study"
                                        v-model="form.field_of_study"
                                        placeholder="Field of study"
                                        :class="{ 'border-red-500': form.errors.field_of_study }"
                                        class="w-full"
                                    />
                                    <p v-if="form.errors.field_of_study" class="text-sm text-red-600">
                                        {{ form.errors.field_of_study }}
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="institution">Institution</Label>
                                    <Input
                                        id="institution"
                                        v-model="form.institution"
                                        placeholder="Institution"
                                        :class="{ 'border-red-500': form.errors.institution }"
                                        class="w-full"
                                    />
                                    <p v-if="form.errors.institution" class="text-sm text-red-600">
                                        {{ form.errors.institution }}
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="graduation_year">Graduation Year</Label>
                                    <Input
                                        id="graduation_year"
                                        v-model="form.graduation_year"
                                        placeholder="YYYY"
                                        :class="{ 'border-red-500': form.errors.graduation_year }"
                                        class="w-full"
                                    />
                                    <p v-if="form.errors.graduation_year" class="text-sm text-red-600">
                                        {{ form.errors.graduation_year }}
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="employee_id">Employee *</Label>
                                    <select
                                        id="employee_id"
                                        v-model="form.employee_id"
                                        :class="[
                                            'w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100',
                                            { 'border-red-500': form.errors.employee_id },
                                        ]"
                                    >
                                        <option value="" disabled>
                                            Select employee
                                        </option>
                                        <option
                                            v-for="emp in props.employees"
                                            :key="emp.id"
                                            :value="emp.id"
                                        >
                                            {{ emp.first_name }} {{ emp.last_name }} ({{ emp.position }}, {{ emp.department }})
                                        </option>
                                    </select>
                                    <p v-if="form.errors.employee_id" class="text-sm text-red-600">
                                        {{ form.errors.employee_id }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4">
                    <Button type="button" variant="outline" @click="handleCancel" class="order-2 w-full cursor-pointer sm:order-1 sm:w-auto">
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :loading="form.processing"
                        class="order-1 w-full cursor-pointer sm:order-2 sm:w-auto"
                        :disabled="form.errors.employee_id"
                    >
                        Create Education
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
