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
import type { BreadcrumbItem } from "../../types";

const props = defineProps<{
    endorsement: {
        id: number;
        employee_id: number;
        skill_id: number;
        endorsed_by: {
            id: number;
            first_name: string;
            last_name: string;
            position: string;
            department: string;
            email: string;
            profile_picture?: string | null;
        };
        endorsement_date: string | null;
        employee: {
            id: number;
            first_name: string;
            last_name: string;
            position: string;
            department: string;
            profile_picture?: string | null;
            email?: string | null;
        };
        skill: {
            id: number;
            skill_name: string;
        };
    };
}>();
const breadcrumbs: BreadcrumbItem[] = [
    { title: "Kms", href: route("dashboard") },
    { title: "Endorsements", href: route("endorsements.index") },
    {
        title: "Edit Endorsement",
        href: route("endorsements.edit", props.endorsement.id),
    },
];

const form = useForm({
    employee_id: 0,
    skill_id: 0,
    endorsed_by: 0,
    endorsement_date: "",
});

onMounted(() => {
    if (props.endorsement) {
        form.employee_id = props.endorsement.employee_id;
        form.skill_id = props.endorsement.skill_id;
        form.endorsed_by = props.endorsement.endorsed_by.id;
        form.endorsement_date = props.endorsement.endorsement_date ?? "";
    }
});

watch(
    () => props.endorsement,
    (newEndorsement) => {
        if (newEndorsement) {
            form.employee_id = newEndorsement.employee_id;
            form.skill_id = newEndorsement.skill_id;
            form.endorsed_by = newEndorsement.endorsed_by.id;
            form.endorsement_date = newEndorsement.endorsement_date ?? "";
        }
    }
);

const endorsementSchema = z.object({
    employee_id: z.number().int().min(1, "Employee is required"),
    skill_id: z.number().int().min(1, "Skill is required"),
    endorsed_by: z.number().int().min(1, "Endorser is required"),
    endorsement_date: z.string().min(1, "Endorsement date is required"),
});

const handleSubmit = () => {
    const result = endorsementSchema.safeParse({
        employee_id: Number(form.employee_id),
        skill_id: Number(form.skill_id),
        endorsed_by: Number(form.endorsed_by),
        endorsement_date: form.endorsement_date,
    });
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
    form.post(route("endorsements.update", props.endorsement.id), {
        preserveScroll: true,
        onSuccess: () => router.visit(route("endorsements.index")),
        onError: (errors: Record<string, string>) =>
            console.log("Form errors:", errors),
    });
};

const handleCancel = () => {
    router.visit(route("endorsements.index"));
};
</script>

<template>
    <Head title="Edit Endorsement" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-3xl space-y-6 p-6">
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
                            Edit Endorsement
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update endorsement details for this employee and
                            skill.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Employee Info Card -->
            <Card v-if="props.endorsement.employee">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        Employee
                    </CardTitle>
                    <CardDescription> Endorsed employee: </CardDescription>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        v-if="props.endorsement.employee.profile_picture"
                        :src="`${props.endorsement.employee.profile_picture}`"
                        alt="Profile"
                        class="h-12 w-12 rounded-full object-cover border"
                    />
                    <div>
                        <div class="font-semibold">
                            {{ props.endorsement.employee.first_name }}
                            {{ props.endorsement.employee.last_name }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ props.endorsement.employee.position }} -
                            {{ props.endorsement.employee.department }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ props.endorsement.employee.email }}
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Skill Info Card -->
            <Card v-if="props.endorsement.skill">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <BadgeCheck class="h-5 w-5" />
                        Skill
                    </CardTitle>
                    <CardDescription> Skill endorsed: </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="font-semibold">
                        {{ props.endorsement.skill.skill_name }}
                    </div>
                </CardContent>
            </Card>

            <!-- Endorser Info Card -->
            <Card v-if="props.endorsement.endorsed_by">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        Endorsed By
                    </CardTitle>
                    <CardDescription> Endorser details: </CardDescription>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        v-if="props.endorsement.endorsed_by.profile_picture"
                        :src="`${props.endorsement.endorsed_by.profile_picture}`"
                        alt="Endorser"
                        class="h-12 w-12 rounded-full object-cover border"
                    />
                    <div>
                        <div class="font-semibold">
                            {{ props.endorsement.endorsed_by.first_name }}
                            {{ props.endorsement.endorsed_by.last_name }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ props.endorsement.endorsed_by.position }} -
                            {{ props.endorsement.endorsed_by.department }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ props.endorsement.endorsed_by.email }}
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <span>Endorsement Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="endorsement_date"
                                >Endorsement Date *</Label
                            >
                            <Input
                                id="endorsement_date"
                                type="date"
                                v-model="form.endorsement_date"
                                :class="{
                                    'border-red-500':
                                        form.errors.endorsement_date,
                                }"
                            />
                            <p
                                v-if="form.errors.endorsement_date"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.endorsement_date }}
                            </p>
                        </div>
                        <!-- Read-only employee and skill display -->
                        <div class="space-y-2">
                            <Label>Employee</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.endorsement.employee">
                                    {{ props.endorsement.employee.first_name }}
                                    {{ props.endorsement.employee.last_name }}
                                    ({{ props.endorsement.employee.position }},
                                    {{ props.endorsement.employee.department }})
                                </span>
                                <span v-else>
                                    Employee ID:
                                    {{ props.endorsement.employee_id }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label>Skill</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.endorsement.skill">
                                    {{ props.endorsement.skill.skill_name }}
                                </span>
                                <span v-else>
                                    Skill ID: {{ props.endorsement.skill_id }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label>Endorsed By</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.endorsement.endorsed_by">
                                    {{
                                        props.endorsement.endorsed_by.first_name
                                    }}
                                    {{
                                        props.endorsement.endorsed_by.last_name
                                    }}
                                    ({{
                                        props.endorsement.endorsed_by.position
                                    }},
                                    {{
                                        props.endorsement.endorsed_by
                                            .department
                                    }})
                                </span>
                                <span v-else>
                                    Endorser ID: {{ form.endorsed_by }}
                                </span>
                            </div>
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
                        >Update Endorsement</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>
