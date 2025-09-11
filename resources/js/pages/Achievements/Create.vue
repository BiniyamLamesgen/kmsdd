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
    { title: "Achievements", href: route("achievements.index") },
    { title: "Create", href: "" },
];

const form = useForm({
    title: "",
    description: "",
    date_awarded: "",
    employee_id: "",
});

const achievementSchema = z.object({
    title: z.string().min(1, "Title is required").max(255),
    description: z.string().max(5000).optional(),
    date_awarded: z
        .string()
        .optional()
        .refine((val) => !val || /^\d{4}-\d{2}-\d{2}$/.test(val), {
            message: "Date awarded must be a valid date (YYYY-MM-DD)",
        }),
    employee_id: z
        .union([z.string(), z.number()])
        .refine((val) => String(val).length > 0, {
            message: "Employee is required",
        }),
});

const handleSubmit = () => {
    const result = achievementSchema.safeParse(form.data());
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
    // Ensure employee_id is sent as a number
    const payload = {
        ...form.data(),
        employee_id: Number(form.employee_id),
    };
    form.post(route("achievements.store"), {
        ...payload,
        onSuccess: () => {
            router.visit(route("achievements.index"));
        },
    });
};

const handleCancel = () => {
    router.visit(route("achievements.index"));
};
</script>

<template>
    <Head title="Create Achievement" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div
                    class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4"
                >
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
                        <h1
                            class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100"
                        >
                            Create Achievement
                        </h1>
                        <p
                            class="text-sm text-gray-600 sm:text-base dark:text-gray-400"
                        >
                            Add a new achievement to your organization.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 lg:gap-8 xl:grid-cols-2">
                    <!-- Achievement Information -->
                    <Card class="xl:col-span-2">
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Star class="h-5 w-5" />
                                <span>Achievement Information</span>
                            </CardTitle>
                            <CardDescription>
                                Enter the details for the new achievement.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div
                                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6"
                            >
                                <div class="space-y-2">
                                    <Label for="title">Title *</Label>
                                    <Input
                                        id="title"
                                        v-model="form.title"
                                        placeholder="Achievement title"
                                        :class="{
                                            'border-red-500': form.errors.title,
                                        }"
                                        class="w-full"
                                    />
                                    <p
                                        v-if="form.errors.title"
                                        class="text-sm text-red-600"
                                    >
                                        {{ form.errors.title }}
                                    </p>
                                </div>

                                <div
                                    class="space-y-2 md:col-span-2 lg:col-span-1"
                                >
                                    <Label for="description">Description</Label>
                                    <Input
                                        id="description"
                                        v-model="form.description"
                                        placeholder="Achievement description"
                                        :class="{
                                            'border-red-500':
                                                form.errors.description,
                                        }"
                                        class="w-full"
                                    />
                                    <p
                                        v-if="form.errors.description"
                                        class="text-sm text-red-600"
                                    >
                                        {{ form.errors.description }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="date_awarded"
                                        >Date Awarded</Label
                                    >
                                    <Input
                                        id="date_awarded"
                                        type="date"
                                        v-model="form.date_awarded"
                                        :class="{
                                            'border-red-500':
                                                form.errors.date_awarded,
                                        }"
                                        class="w-full"
                                    />
                                    <p
                                        v-if="form.errors.date_awarded"
                                        class="text-sm text-red-600"
                                    >
                                        {{ form.errors.date_awarded }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="employee_id">Employee *</Label>
                                    <select
                                        id="employee_id"
                                        v-model="form.employee_id"
                                        :class="[
                                            'w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100',
                                            {
                                                'border-red-500':
                                                    form.errors.employee_id,
                                            },
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
                                            {{ emp.first_name }}
                                            {{ emp.last_name }} ({{
                                                emp.position
                                            }}, {{ emp.department }})
                                        </option>
                                    </select>
                                    <p
                                        v-if="form.errors.employee_id"
                                        class="text-sm text-red-600"
                                    >
                                        {{ form.errors.employee_id }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4"
                >
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
                        :disabled="form.errors.title || form.errors.employee_id"
                    >
                        Create Achievement
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
