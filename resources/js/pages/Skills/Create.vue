<script setup lang="ts">
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useValidatedForm } from "@/composables/useValidatedForm";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { AlertCircle, Plus } from "lucide-vue-next";
import { useField } from "vee-validate";
import { route } from "ziggy-js";
import { z } from "zod";

const breadcrumbs = [
    { title: "Skills", href: route("skills.index") },
    { title: "Create Skill", href: route("skills.create") },
];

const skillSchema = z.object({
    skill_name: z
        .string()
        .min(2, "Skill name must be at least 2 characters")
        .max(255, "Skill name must not exceed 255 characters"),
});

const initialValues = {
    skill_name: "",
};

const {
    isSubmitting,
    getBackendError,
    clearBackendError,
    hasBackendError,
    submitForm,
} = useValidatedForm(skillSchema, initialValues, route("skills.store"));

const {
    value: skillNameValue,
    errorMessage: skillNameError,
    handleBlur: skillNameBlur,
} = useField<string>("skill_name");

const submit = submitForm();
</script>

<template>
    <Head title="Create Skill" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-xl px-4 py-8">
            <form @submit.prevent="submit" class="space-y-6">
                <Card class="w-full">
                    <CardHeader>
                        <CardTitle>Create Skill</CardTitle>
                        <CardDescription
                            >Enter the name for the new skill</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <Label for="skill_name"
                                >Skill Name
                                <span class="text-red-600">*</span></Label
                            >
                            <Input
                                id="skill_name"
                                v-model="skillNameValue"
                                @blur="skillNameBlur"
                                @input="clearBackendError('skill_name')"
                                placeholder="Skill name"
                                :class="
                                    skillNameError ||
                                    hasBackendError('skill_name')
                                        ? 'border-destructive'
                                        : ''
                                "
                            />
                            <p
                                v-if="
                                    skillNameError ||
                                    hasBackendError('skill_name')
                                "
                                class="mb-2 flex items-center gap-1 text-sm text-destructive"
                            >
                                <AlertCircle class="h-3 w-3" />
                                {{
                                    skillNameError ||
                                    getBackendError("skill_name")
                                }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
                <div class="flex justify-end">
                    <Link :href="route('skills.index')">
                        <Button variant="outline" type="button">Cancel</Button>
                    </Link>
                    <Button
                        type="submit"
                        :disabled="isSubmitting"
                        class="ml-2 gap-2"
                    >
                        <Plus class="h-4 w-4" />
                        <span v-if="isSubmitting">Creating...</span>
                        <span v-else>Create Skill</span>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
