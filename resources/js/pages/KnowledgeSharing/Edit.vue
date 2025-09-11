<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, BookOpen } from "lucide-vue-next";
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
    knowledge_sharing: {
        id: number;
        employee_id: number;
        document_title: string;
        document_type: string;
        link: string | null;
        date_shared: string;
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
    { title: "Knowledge Sharing", href: route("knowledge-management.index") },
    { title: "Edit", href: "" },
];

const form = useForm({
    document_title: "",
    document_type: "",
    link: "",
    date_shared: "",
    employee_id: "",
});

const documentTypes = [
    "Article",
    "Blog",
    "Tutorial",
    "Guide",
    "Documentation",
    "Presentation",
    "Video",
    "Template",
    "Research",
    "Best Practices",
    "Case Study",
    "White Paper",
    "Other",
];

onMounted(() => {
    if (props.knowledge_sharing) {
        form.document_title = props.knowledge_sharing.document_title ?? "";
        form.document_type = props.knowledge_sharing.document_type ?? "";
        form.link = props.knowledge_sharing.link ?? "";
        form.date_shared = props.knowledge_sharing.date_shared ?? "";
        form.employee_id = String(props.knowledge_sharing.employee_id ?? "");
    }
});

watch(
    () => props.knowledge_sharing,
    (newKnowledge) => {
        if (newKnowledge) {
            form.document_title = newKnowledge.document_title ?? "";
            form.document_type = newKnowledge.document_type ?? "";
            form.link = newKnowledge.link ?? "";
            form.date_shared = newKnowledge.date_shared ?? "";
            form.employee_id = String(newKnowledge.employee_id ?? "");
        }
    }
);

const knowledgeSchema = z.object({
    document_title: z.string().min(1, "Document title is required").max(255),
    document_type: z.string().min(1, "Document type is required").max(100),
    link: z
        .string()
        .url("Please enter a valid URL")
        .optional()
        .or(z.literal("")),
    date_shared: z.string().min(1, "Date shared is required"),
    employee_id: z
        .union([z.string(), z.number()])
        .refine((val) => String(val).length > 0, {
            message: "Employee is required",
        }),
});

const handleSubmit = () => {
    const result = knowledgeSchema.safeParse(form.data());
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
    form.post(
        route("knowledge-management.update", props.knowledge_sharing.id),
        {
            ...payload,
            preserveScroll: true,
            onSuccess: () => router.visit(route("knowledge-management.index")),
            onError: (errors: Record<string, string>) =>
                console.log("Form errors:", errors),
        }
    );
};

const handleCancel = () => {
    router.visit(route("knowledge-management.index"));
};
</script>

<template>
    <Head title="Edit Knowledge Sharing" />
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
                            Edit Knowledge Sharing
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update knowledge sharing details like title, type,
                            link, and date.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Employee Info Card -->
            <Card v-if="props.knowledge_sharing.employee">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        Employee
                    </CardTitle>
                    <CardDescription> Knowledge shared by: </CardDescription>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        v-if="props.knowledge_sharing.employee.profile_picture"
                        :src="`/storage/${props.knowledge_sharing.employee.profile_picture}`"
                        alt="Profile"
                        class="h-12 w-12 rounded-full object-cover border"
                    />
                    <div>
                        <div class="font-semibold">
                            {{ props.knowledge_sharing.employee.first_name }}
                            {{ props.knowledge_sharing.employee.last_name }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ props.knowledge_sharing.employee.position }} -
                            {{ props.knowledge_sharing.employee.department }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ props.knowledge_sharing.employee.email }}
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <BookOpen class="h-5 w-5" />
                            <span>Knowledge Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="document_title">Document Title *</Label>
                            <Input
                                id="document_title"
                                v-model="form.document_title"
                                placeholder="Enter document title"
                                :class="{
                                    'border-red-500':
                                        form.errors.document_title,
                                }"
                            />
                            <p
                                v-if="form.errors.document_title"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.document_title }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="document_type">Document Type *</Label>
                            <select
                                id="document_type"
                                v-model="form.document_type"
                                :class="[
                                    'w-full rounded border px-3 py-2',
                                    form.errors.document_type
                                        ? 'border-red-500'
                                        : 'border-gray-300',
                                ]"
                            >
                                <option value="">Select document type</option>
                                <option
                                    v-for="type in documentTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.document_type"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.document_type }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="link">Link (Optional)</Label>
                            <Input
                                id="link"
                                type="url"
                                v-model="form.link"
                                placeholder="https://example.com/document"
                                :class="{
                                    'border-red-500': form.errors.link,
                                }"
                            />
                            <p
                                v-if="form.errors.link"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.link }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Provide a link to access the shared knowledge
                                (if available online)
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="date_shared">Date Shared *</Label>
                            <Input
                                id="date_shared"
                                type="date"
                                v-model="form.date_shared"
                                :class="{
                                    'border-red-500': form.errors.date_shared,
                                }"
                            />
                            <p
                                v-if="form.errors.date_shared"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.date_shared }}
                            </p>
                        </div>

                        <!-- Replace employee dropdown with read-only display -->
                        <div class="space-y-2">
                            <Label for="employee_id">Employee</Label>
                            <div
                                class="w-full rounded border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            >
                                <span v-if="props.knowledge_sharing.employee">
                                    {{
                                        props.knowledge_sharing.employee
                                            .first_name
                                    }}
                                    {{
                                        props.knowledge_sharing.employee
                                            .last_name
                                    }}
                                    ({{
                                        props.knowledge_sharing.employee
                                            .position
                                    }},
                                    {{
                                        props.knowledge_sharing.employee
                                            .department
                                    }})
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
                        >Update Knowledge</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>
