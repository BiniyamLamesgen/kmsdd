<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, Calendar } from "lucide-vue-next";
import { route } from "ziggy-js";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from "../../components/ui/card";
import { Separator } from "../../components/ui/separator";
import AppLayout from "../../layouts/AppLayout.vue";
import { type BreadcrumbItem } from "../../types";

interface Skill {
    id: number;
    skill_name: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    skill: Skill;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Skills", href: route("skills.index") },
    { title: "Show", href: "#" },
];
</script>

<template>
    <Head :title="`Skill: ${props.skill.skill_name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('skills.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Skill Details
                        </h1>
                        <p class="text-muted-foreground">
                            Information about this skill
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        {{ props.skill.skill_name }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            Skill Name
                        </div>
                        <span class="font-mono text-sm">{{ props.skill.skill_name }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Created At
                        </div>
                        <span class="font-mono text-sm">{{ props.skill.created_at }}</span>
                    </div>
                    <Separator />
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                            <Calendar class="h-4 w-4" />
                            Updated At
                        </div>
                        <span class="font-mono text-sm">{{ props.skill.updated_at }}</span>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>