<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, Star } from "lucide-vue-next";
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
    education: {
        id: number;
        employee_id: number;
        field_of_study: string;
        institution: string;
        graduation_year: string;
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
    { title: "Education", href: route("educations.index") },
    { title: "Edit", href: "" },
];

const form = useForm({
    field_of_study: "",
    institution: "",
    graduation_year: "",
    employee_id: "",
});

onMounted(() => {
    if (props.education) {
        form.field_of_study = props.education.field_of_study ?? "";
        form.institution = props.education.institution ?? "";
        form.graduation_year = String(props.education.graduation_year ?? "");
        form.employee_id = String(props.education.employee_id ?? "");
    }
});

watch(
    () => props.education,
    (newEducation) => {
        if (newEducation) {
            form.field_of_study = newEducation.field_of_study ?? "";
            form.institution = newEducation.institution ?? "";
            form.graduation_year = String(newEducation.graduation_year ?? "");
            form.employee_id = String(newEducation.employee_id ?? "");
        }
    }
);

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
    form.post(
        route("educations.update", props.education.id),
        {
            ...payload,
            preserveScroll: true,
            onSuccess: () => router.visit(route("education.index")),
            onError: (errors: Record<string, string>) => console.log("Form errors:", errors),
        }
    );
};

const handleCancel = () => {
    router.visit(route("educations.index"));
};
</script>

<template>
    <Head title="Edit Education" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-3xl space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Button variant="outline" size="sm" @click="handleCancel" class="cursor-pointer">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Education
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update education details like field, institution, year, and employee.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Star class="h-5 w-5" />
                            <span>Education Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="field_of_study">Field of Study</Label>
                            <Input
                                id="field_of_study"
                                v-model="form.field_of_study"
                                placeholder="Field of study"
                                :class="{ 'border-red-500': form.errors.field_of_study }"
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
                    </CardContent>
                </Card>

                <div class="flex items-center justify-end space-x-4 border-t pt-6">
                    <Button type="button" variant="outline" @click="handleCancel" class="cursor-pointer">
                        Cancel
                    </Button>
                    <Button type="submit" :loading="form.processing" class="cursor-pointer">
                        Update Education
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
