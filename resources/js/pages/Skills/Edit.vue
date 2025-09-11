<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft } from "lucide-vue-next";
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
    skill: {
        id: number;
        skill_name: string;
        created_at: string;
        updated_at: string;
    };
}>();

const breadcrumbs = [
    { title: "Skills", href: route("skills.index") },
    { title: "Edit", href: "" },
];

const form = useForm({
    skill_name: "",
});

onMounted(() => {
    if (props.skill) {
        form.skill_name = props.skill.skill_name ?? "";
    }
});

watch(
    () => props.skill,
    (newSkill) => {
        if (newSkill) {
            form.skill_name = newSkill.skill_name ?? "";
        }
    }
);

const skillSchema = z.object({
    skill_name: z.string().min(1, "Skill name is required").max(255),
});

const handleSubmit = () => {
    const result = skillSchema.safeParse(form.data());
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] = err.message;
            }
        });
        return;
    }
    form.post(route("skills.update", props.skill.id), {
        preserveScroll: true,
        onSuccess: () => router.visit(route("skills.index")),
        onError: (errors: Record<string, string>) =>
            console.log("Form errors:", errors),
    });
};

const handleCancel = () => {
    router.visit(route("skills.index"));
};
</script>

<template>
    <Head title="Edit Skill" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" space-y-6 p-6">
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
                            Edit Skill
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Update skill details below.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Skill Information</CardTitle>
                        <CardDescription>
                            Update the skill name and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="skill_name">Skill Name *</Label>
                            <Input
                                id="skill_name"
                                v-model="form.skill_name"
                                placeholder="Skill name"
                                :class="{
                                    'border-red-500': form.errors.skill_name,
                                }"
                            />
                            <p
                                v-if="form.errors.skill_name"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.skill_name }}
                            </p>
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