<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import {
    ArrowLeft,
    User,
    Calendar,
    ExternalLink,
    BookOpen,
    Tag,
} from "lucide-vue-next";
import { route } from "ziggy-js";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from "../../components/ui/card";
import { Separator } from "../../components/ui/separator";
import { Badge } from "../../components/ui/badge";
import AppLayout from "../../layouts/AppLayout.vue";
import { type BreadcrumbItem } from "../../types";

interface KnowledgeSharing {
    id: number;
    employee_id: number;
    document_title: string;
    document_type: string;
    link: string | null;
    date_shared: string;
    formatted_date?: string;
    time_since_shared?: string;
    document_category?: string;
    has_link?: boolean;
    sharing_year?: number;
    created_at: string;
    updated_at: string;
    employee: Employee;
}

interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    position: string;
    department?: string;
    department_id?: number;
    email: string;
    phone: string;
    internal_extension: string;
    profile_picture: string | null;
    date_joined: string;
    linkedin: string | null;
    facebook: string | null;
    twitter: string | null;
    github: string | null;
    personal_website: string | null;
    languages: string;
    mentoring_interest: string;
    availability_for_sharing: boolean;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    knowledge_sharing: KnowledgeSharing;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Knowledge Sharing", href: route("knowledge-management.index") },
    { title: "View", href: "#" },
];

// Helper functions
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const getTimeSince = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - date.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 30) {
        return `${diffDays} days ago`;
    } else if (diffDays < 365) {
        const months = Math.floor(diffDays / 30);
        return `${months} month${months !== 1 ? "s" : ""} ago`;
    } else {
        const years = Math.floor(diffDays / 365);
        return `${years} year${years !== 1 ? "s" : ""} ago`;
    }
};

const getSharingYear = (dateString: string) => {
    return new Date(dateString).getFullYear();
};

const getDocumentCategory = (type: string) => {
    const typeLower = type?.toLowerCase() || "";

    switch (typeLower) {
        case "article":
        case "blog":
            return "Article/Blog";
        case "tutorial":
        case "guide":
            return "Tutorial/Guide";
        case "presentation":
            return "Presentation";
        case "documentation":
            return "Documentation";
        case "video":
            return "Video";
        case "template":
            return "Template";
        default:
            return type
                ? type.charAt(0).toUpperCase() + type.slice(1)
                : "Other";
    }
};

const getTypeColor = (type: string) => {
    const colors: Record<string, string> = {
        Article: "bg-blue-100 text-blue-800",
        Blog: "bg-purple-100 text-purple-800",
        Tutorial: "bg-green-100 text-green-800",
        Guide: "bg-emerald-100 text-emerald-800",
        Documentation: "bg-gray-100 text-gray-800",
        Presentation: "bg-orange-100 text-orange-800",
        Video: "bg-red-100 text-red-800",
        Template: "bg-cyan-100 text-cyan-800",
        Research: "bg-indigo-100 text-indigo-800",
        "Best Practices": "bg-teal-100 text-teal-800",
        "Case Study": "bg-yellow-100 text-yellow-800",
        "White Paper": "bg-slate-100 text-slate-800",
    };
    return colors[type] || "bg-gray-100 text-gray-800";
};
</script>

<template>
    <Head :title="`Knowledge: ${props.knowledge_sharing.document_title}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('knowledge-management.index')"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Knowledge Details
                        </h1>
                        <p class="text-muted-foreground">
                            Information about this shared knowledge
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <BookOpen class="h-5 w-5 text-primary" />
                        {{ props.knowledge_sharing.document_title }}
                    </CardTitle>
                    <div class="flex items-center gap-2 mt-2">
                        <Badge
                            :class="
                                getTypeColor(
                                    props.knowledge_sharing.document_type
                                )
                            "
                        >
                            {{ props.knowledge_sharing.document_type }}
                        </Badge>
                        <Badge variant="outline">
                            {{
                                getDocumentCategory(
                                    props.knowledge_sharing.document_type
                                )
                            }}
                        </Badge>
                    </div>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Tag class="h-4 w-4" />
                            Document Type
                        </div>
                        <span class="font-mono text-sm">{{
                            props.knowledge_sharing.document_type
                        }}</span>
                    </div>
                    <Separator />

                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Date Shared
                        </div>
                        <div class="text-right">
                            <div class="font-mono text-sm">
                                {{
                                    props.knowledge_sharing.formatted_date ||
                                    formatDate(
                                        props.knowledge_sharing.date_shared
                                    )
                                }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{
                                    props.knowledge_sharing.time_since_shared ||
                                    getTimeSince(
                                        props.knowledge_sharing.date_shared
                                    )
                                }}
                            </div>
                        </div>
                    </div>
                    <Separator />

                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Sharing Year
                        </div>
                        <span class="font-mono text-sm">{{
                            props.knowledge_sharing.sharing_year ||
                            getSharingYear(props.knowledge_sharing.date_shared)
                        }}</span>
                    </div>
                    <Separator />

                    <!-- Link Section -->
                    <div>
                        <div
                            class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <ExternalLink class="h-4 w-4" />
                            Access Link
                        </div>
                        <div
                            v-if="props.knowledge_sharing.link"
                            class="rounded-md bg-blue-50 p-4 text-sm dark:bg-blue-900/20"
                        >
                            <a
                                :href="props.knowledge_sharing.link"
                                target="_blank"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium flex items-center gap-2 break-all"
                            >
                                <ExternalLink class="h-4 w-4 flex-shrink-0" />
                                {{ props.knowledge_sharing.link }}
                            </a>
                        </div>
                        <div
                            v-else
                            class="rounded-md bg-gray-50 p-4 text-sm text-gray-600 dark:bg-gray-900/20 dark:text-gray-400"
                        >
                            No access link provided for this knowledge item.
                        </div>
                    </div>
                    <Separator />

                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Created At
                        </div>
                        <span class="font-mono text-sm">{{
                            formatDate(props.knowledge_sharing.created_at)
                        }}</span>
                    </div>
                    <Separator />

                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <Calendar class="h-4 w-4" />
                            Updated At
                        </div>
                        <span class="font-mono text-sm">{{
                            formatDate(props.knowledge_sharing.updated_at)
                        }}</span>
                    </div>
                    <Separator />

                    <!-- Employee Info -->
                    <div>
                        <div
                            class="mb-2 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                        >
                            <User class="h-4 w-4" />
                            Shared By
                        </div>
                        <div class="flex items-center gap-4">
                            <img
                                v-if="
                                    props.knowledge_sharing.employee
                                        .profile_picture
                                "
                                :src="`/storage/${props.knowledge_sharing.employee.profile_picture}`"
                                alt="Profile Picture"
                                class="h-16 w-16 rounded-full object-cover border"
                            />
                            <div
                                v-else
                                class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg border"
                            >
                                {{
                                    props.knowledge_sharing.employee.first_name.charAt(
                                        0
                                    )
                                }}{{
                                    props.knowledge_sharing.employee.last_name.charAt(
                                        0
                                    )
                                }}
                            </div>
                            <div>
                                <div class="font-bold">
                                    {{
                                        props.knowledge_sharing.employee
                                            .first_name +
                                        " " +
                                        props.knowledge_sharing.employee
                                            .last_name
                                    }}
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{
                                        props.knowledge_sharing.employee
                                            .position
                                    }}
                                    <span
                                        v-if="
                                            props.knowledge_sharing.employee
                                                .department
                                        "
                                    >
                                        -
                                        {{
                                            props.knowledge_sharing.employee
                                                .department
                                        }}
                                    </span>
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{ props.knowledge_sharing.employee.email }}
                                </div>
                                <div class="text-sm mt-1">
                                    <a
                                        v-if="
                                            props.knowledge_sharing.employee
                                                .linkedin
                                        "
                                        :href="
                                            props.knowledge_sharing.employee
                                                .linkedin
                                        "
                                        target="_blank"
                                        class="text-blue-600 underline hover:text-blue-800"
                                    >
                                        LinkedIn Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
