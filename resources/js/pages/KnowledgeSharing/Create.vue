<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, BookOpen } from "lucide-vue-next";
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
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const employees = page.props.employees as Array<{
    id: number;
    first_name: string;
    last_name: string;
}>;

const breadcrumbs = [
    { title: "Knowledge Sharing", href: route("knowledge-management.index") },
    { title: "Create", href: "" },
];

const form = useForm<{
    employee_id: number | "";
    document_title: string;
    document_type: string;
    link: string;
    date_shared: string;
}>({
    employee_id: "",
    document_title: "",
    document_type: "",
    link: "",
    date_shared: "",
});

const knowledgeSchema = z.object({
    employee_id: z.number({ required_error: "Employee is required" }),
    document_title: z.string().min(1, "Document title is required").max(255),
    document_type: z.string().min(1, "Document type is required").max(100),
    link: z
        .string()
        .url("Please enter a valid URL")
        .optional()
        .or(z.literal("")),
    date_shared: z.string().min(1, "Date shared is required"),
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

const handleSubmit = () => {
    form.errors = {};
    // Convert employee_id to number if possible
    if (form.employee_id === "") {
        form.errors.employee_id = "Employee is required";
        return;
    }
    const result = knowledgeSchema.safeParse({
        ...form.data(),
        employee_id: Number(form.employee_id),
    });
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] =
                    err.message;
            }
        });
        return;
    }
    form.post(route("knowledge-management.store"), {
        onSuccess: () => {
            router.visit(route("knowledge-management.index"));
        },
    });
};

const handleCancel = () => {
    router.visit(route("knowledge-management.index"));
};
</script>

<template>
    <Head title="Share Knowledge" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6 lg:p-8">
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
                            Share Knowledge
                        </h1>
                        <p
                            class="text-sm text-gray-600 sm:text-base dark:text-gray-400"
                        >
                            Add a new knowledge sharing item to help others
                            learn.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <BookOpen class="h-5 w-5" />
                            <span>Knowledge Information</span>
                        </CardTitle>
                        <CardDescription>
                            Enter the details for the knowledge sharing item.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:gap-6"
                        >
                            <div class="space-y-2">
                                <Label for="employee_id">Employee *</Label>
                                <select
                                    id="employee_id"
                                    v-model="form.employee_id"
                                    :class="[
                                        'w-full rounded border px-3 py-2',
                                        form.errors.employee_id
                                            ? 'border-red-500'
                                            : 'border-gray-300',
                                    ]"
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
                                <p
                                    v-if="form.errors.employee_id"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.employee_id }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="document_title"
                                    >Document Title *</Label
                                >
                                <Input
                                    id="document_title"
                                    v-model="form.document_title"
                                    placeholder="Enter document title"
                                    :class="{
                                        'border-red-500':
                                            form.errors.document_title,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.document_title"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.document_title }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="document_type"
                                    >Document Type *</Label
                                >
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
                                    <option value="">
                                        Select document type
                                    </option>
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
                                <Label for="date_shared">Date Shared *</Label>
                                <Input
                                    id="date_shared"
                                    type="date"
                                    v-model="form.date_shared"
                                    :class="{
                                        'border-red-500':
                                            form.errors.date_shared,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.date_shared"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.date_shared }}
                                </p>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="link">Link (Optional)</Label>
                                <Input
                                    id="link"
                                    type="url"
                                    v-model="form.link"
                                    placeholder="https://example.com/document"
                                    :class="{
                                        'border-red-500': form.errors.link,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.link"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.link }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Provide a link to access the shared
                                    knowledge (if available online)
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

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
                    >
                        Share Knowledge
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
