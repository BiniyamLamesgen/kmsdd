<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, BadgePlus } from "lucide-vue-next";
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
    skills: Array<{
        id: number;
        skill_name: string;
    }>;
}>();

const breadcrumbs = [
    { title: "Endorsements", href: route("endorsements.index") },
    { title: "Create", href: "" },
];

const form = useForm({
    employee_id: "",
    skill_id: "",
    endorsed_by: "",
    endorsement_date: "",
});

const endorsementSchema = z.object({
    employee_id: z.union([z.string(), z.number()]).refine((val) => String(val).length > 0, {
        message: "Employee is required",
    }),
    skill_id: z.union([z.string(), z.number()]).refine((val) => String(val).length > 0, {
        message: "Skill is required",
    }),
    endorsed_by: z.union([z.string(), z.number()]).refine((val) => String(val).length > 0, {
        message: "Endorser is required",
    }),
    endorsement_date: z.string().optional(),
});

const handleSubmit = () => {
    const result = endorsementSchema.safeParse(form.data());
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] = err.message;
            }
        });
        return;
    }
    form.post(route("endorsements.store"), {
        onSuccess: () => {
            router.visit(route("endorsements.index"));
        },
    });
};

const handleCancel = () => {
    router.visit(route("endorsements.index"));
};
</script>

<template>
    <Head title="Create Endorsement" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
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
                            Create Endorsement
                        </h1>
                        <p class="text-sm text-gray-600 sm:text-base dark:text-gray-400">
                            Endorse an employee for a skill.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 lg:gap-8 xl:grid-cols-2">
                    <Card class="xl:col-span-2">
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <BadgePlus class="h-5 w-5" />
                                <span>Endorsement Information</span>
                            </CardTitle>
                            <CardDescription>
                                Enter the details for the new endorsement.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
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
                                        <option value="" disabled>Select employee</option>
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

                                <div class="space-y-2">
                                    <Label for="skill_id">Skill *</Label>
                                    <select
                                        id="skill_id"
                                        v-model="form.skill_id"
                                        :class="[
                                            'w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100',
                                            { 'border-red-500': form.errors.skill_id },
                                        ]"
                                    >
                                        <option value="" disabled>Select skill</option>
                                        <option
                                            v-for="skill in props.skills"
                                            :key="skill.id"
                                            :value="skill.id"
                                        >
                                            {{ skill.skill_name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.skill_id" class="text-sm text-red-600">
                                        {{ form.errors.skill_id }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="endorsed_by">Endorsed By *</Label>
                                    <select
                                        id="endorsed_by"
                                        v-model="form.endorsed_by"
                                        :class="[
                                            'w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100',
                                            { 'border-red-500': form.errors.endorsed_by },
                                        ]"
                                    >
                                        <option value="" disabled>Select endorser</option>
                                        <option
                                            v-for="emp in props.employees"
                                            :key="emp.id"
                                            :value="emp.id"
                                        >
                                            {{ emp.first_name }} {{ emp.last_name }} ({{ emp.position }}, {{ emp.department }})
                                        </option>
                                    </select>
                                    <p v-if="form.errors.endorsed_by" class="text-sm text-red-600">
                                        {{ form.errors.endorsed_by }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="endorsement_date">Endorsement Date</Label>
                                    <Input
                                        id="endorsement_date"
                                        type="date"
                                        v-model="form.endorsement_date"
                                        :class="{
                                            'border-red-500': form.errors.endorsement_date,
                                        }"
                                        class="w-full"
                                    />
                                    <p v-if="form.errors.endorsement_date" class="text-sm text-red-600">
                                        {{ form.errors.endorsement_date }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

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
                        :disabled="form.errors.employee_id || form.errors.skill_id || form.errors.endorsed_by"
                    >
                        Create Endorsement
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>