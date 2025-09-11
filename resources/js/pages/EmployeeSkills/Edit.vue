<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, BadgeCheck } from "lucide-vue-next";
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
    employeeSkill: {
        id: number;
        employee_id: number;
        skill_id: number;
        proficiency_level: string | null;
        endorsements_count: number;
        employee?: {
            id: number;
            first_name: string;
            last_name: string;
            position: string;
            department: string;
            profile_picture?: string | null;
            email?: string | null;
        };
        skill?: {
            id: number;
            skill_name: string;
        };
    };
}>();

const breadcrumbs = [
    { title: "Employee Skills", href: route("employee-skills.index") },
    { title: "Edit", href: "" },
];

const form = useForm({
    proficiency_level: "",
    endorsements_count: 0,
});

onMounted(() => {
    if (props.employeeSkill) {
        form.proficiency_level = props.employeeSkill.proficiency_level ?? "";
        form.endorsements_count = props.employeeSkill.endorsements_count ?? 0;
    }
});

watch(
    () => props.employeeSkill,
    (newSkill) => {
        if (newSkill) {
            form.proficiency_level = newSkill.proficiency_level ?? "";
            form.endorsements_count = newSkill.endorsements_count ?? 0;
        }
    }
);

const skillSchema = z.object({
    proficiency_level: z.string().min(1, "Proficiency level is required").max(255),
    endorsements_count: z
        .number()
        .int()
        .min(0, "Endorsements count must be 0 or more"),
});

const handleSubmit = () => {
    const result = skillSchema.safeParse({
        proficiency_level: form.proficiency_level,
        endorsements_count: Number(form.endorsements_count),
    });
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] = err.message;
            }
        });
        return;
    }
    form.post(route("employee-skills.update", props.employeeSkill.id), {
        preserveScroll: true,
        onSuccess: () => router.visit(route("employee-skills.index")),
        onError: (errors: Record<string, string>) =>
            console.log("Form errors:", errors),
    });
};

const handleCancel = () => {
    router.visit(route("employee-skills.index"));
};
</script>

<template>
    <Head title="Edit Employee Skill" />
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
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Employee Skill
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update skill proficiency and endorsements for this employee.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Employee Info Card -->
            <Card v-if="props.employeeSkill.employee">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        Employee
                    </CardTitle>
                    <CardDescription> Skill assigned to: </CardDescription>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        v-if="props.employeeSkill.employee.profile_picture"
                        :src="`${props.employeeSkill.employee.profile_picture}`"
                        alt="Profile"
                        class="h-12 w-12 rounded-full object-cover border"
                    />
                    <div>
                        <div class="font-semibold">
                            {{ props.employeeSkill.employee.first_name }}
                            {{ props.employeeSkill.employee.last_name }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ props.employeeSkill.employee.position }} -
                            {{ props.employeeSkill.employee.department }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ props.employeeSkill.employee.email }}
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Skill Info Card -->
            <Card v-if="props.employeeSkill.skill">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <BadgeCheck class="h-5 w-5" />
                        Skill
                    </CardTitle>
                    <CardDescription> Skill details: </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="font-semibold">
                        {{ props.employeeSkill.skill.skill_name }}
                    </div>
                </CardContent>
            </Card>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <span>Skill Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="proficiency_level">Proficiency Level *</Label>
                            <Input
                                id="proficiency_level"
                                v-model="form.proficiency_level"
                                placeholder="e.g. Beginner, Intermediate, Expert"
                                :class="{
                                    'border-red-500': form.errors.proficiency_level,
                                }"
                            />
                            <p
                                v-if="form.errors.proficiency_level"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.proficiency_level }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="endorsements_count">Endorsements Count</Label>
                            <Input
                                id="endorsements_count"
                                type="number"
                                min="0"
                                v-model="form.endorsements_count"
                                placeholder="Number of endorsements"
                                :class="{
                                    'border-red-500': form.errors.endorsements_count,
                                }"
                            />
                            <p
                                v-if="form.errors.endorsements_count"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.endorsements_count }}
                            </p>
                        </div>
                        <!-- Read-only employee and skill display -->
                        <div class="space-y-2">
                            <Label>Employee</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.employeeSkill.employee">
                                    {{ props.employeeSkill.employee.first_name }}
                                    {{ props.employeeSkill.employee.last_name }}
                                    ({{ props.employeeSkill.employee.position }},
                                    {{ props.employeeSkill.employee.department }})
                                </span>
                                <span v-else>
                                    Employee ID: {{ props.employeeSkill.employee_id }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label>Skill</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.employeeSkill.skill">
                                    {{ props.employeeSkill.skill.skill_name }}
                                </span>
                                <span v-else>
                                    Skill ID: {{ props.employeeSkill.skill_id }}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 border-t pt-6">
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleCancel"
                        class="cursor-pointer"
                    >Cancel</Button>
                    <Button
                        type="submit"
                        :loading="form.processing"
                        class="cursor-pointer"
                    >Update Skill</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>