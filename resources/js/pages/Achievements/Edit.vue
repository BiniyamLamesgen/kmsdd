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
    achievement: {
        id: number;
        employee_id: number;
        title: string;
        description: string;
        date_awarded: string;
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
    { title: "Achievements", href: route("achievements.index") },
    { title: "Edit", href: "" },
];

const form = useForm({
    title: "",
    description: "",
    date_awarded: "",
    employee_id: "",
});

onMounted(() => {
    if (props.achievement) {
        form.title = props.achievement.title ?? "";
        form.description = props.achievement.description ?? "";
        form.date_awarded = props.achievement.date_awarded ?? "";
        form.employee_id = String(props.achievement.employee_id ?? "");
    }
});

watch(
    () => props.achievement,
    (newAchievement) => {
        if (newAchievement) {
            form.title = newAchievement.title ?? "";
            form.description = newAchievement.description ?? "";
            form.date_awarded = newAchievement.date_awarded ?? "";
            form.employee_id = String(newAchievement.employee_id ?? "");
        }
    }
);

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
                form.errors[err.path[0] as keyof typeof form.errors] = err.message;
            }
        });
        return;
    }
    // Ensure employee_id is sent as a number
    const payload = {
        ...form.data(),
        employee_id: Number(form.employee_id),
    };
    form.post(
        route("achievements.update", props.achievement.id),
        {
            ...payload,
            preserveScroll: true,
            onSuccess: () => router.visit(route("achievements.index")),
            onError: (errors: Record<string, string>) => console.log("Form errors:", errors),
        }
    );
};

const handleCancel = () => {
    router.visit(route("achievements.index"));
};
</script>

<template>
    <Head title="Edit Achievement" />
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
                            Edit Achievement
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update achievement details like title, description,
                            date, and employee.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Clock class="h-5 w-5" />
                            <span>Achievement Information</span>
                        </CardTitle>
                        <CardDescription
                            >Update the fields below and click
                            save.</CardDescription
                        >
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="title">Title *</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                placeholder="Achievement title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p
                                v-if="form.errors.title"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.title }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Input
                                id="description"
                                v-model="form.description"
                                placeholder="Achievement description"
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
                            <Label for="date_awarded">Date Awarded</Label>
                            <Input
                                id="date_awarded"
                                type="date"
                                v-model="form.date_awarded"
                                :class="{
                                    'border-red-500': form.errors.date_awarded,
                                }"
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
                                    {{ emp.first_name }} {{ emp.last_name }} ({{
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
                        >Update Achievement</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>
