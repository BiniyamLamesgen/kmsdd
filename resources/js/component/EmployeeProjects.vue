<script setup lang="ts">
interface EmployeeProject {
    id: number;
    employee_id: number;
    project_name: string;
    description: string;
    role: string;
    start_date: string;
    end_date: string | null;
    outcome: string | null;
    duration: string;
    status: string;
    is_ongoing: boolean;
    created_at: string;
    updated_at: string;
}

interface Employee {
    projects?: EmployeeProject[];
}

const props = defineProps<{
    employee: Employee;
}>();

// Helper function to format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
    });
};

// Helper function to get status color
const getStatusColor = (status: string) => {
    switch (status.toLowerCase()) {
        case "completed":
            return "bg-green-100 text-green-700";
        case "in progress":
            return "bg-blue-100 text-blue-700";
        default:
            return "bg-gray-100 text-gray-700";
    }
};
</script>

<template>
    <div v-if="(props.employee?.projects?.length ?? 0) > 0" class="space-y-4">
        <!-- Section Header -->
        <div class="flex items-center gap-3 mb-6">
            <div
                class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg p-2"
            >
                <svg
                    class="w-5 h-5 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    ></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    Projects Portfolio
                </h3>
                <p class="text-sm text-gray-600">
                    All projects and contributions
                </p>
            </div>
        </div>

        <!-- Projects List -->
        <div class="space-y-4">
            <div
                v-for="project in props.employee.projects"
                :key="project.id"
                class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:border-blue-200"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <!-- Project Name and Role -->
                        <div class="mb-3">
                            <h4
                                class="text-lg font-semibold text-gray-900 mb-1"
                            >
                                {{ project.project_name }}
                            </h4>
                            <p class="text-blue-600 font-medium text-base">
                                {{ project.role }}
                            </p>
                        </div>

                        <!-- Duration and Status -->
                        <div class="flex flex-wrap items-center gap-4 mb-4">
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                <span>
                                    {{ formatDate(project.start_date) }} -
                                    {{
                                        project.end_date
                                            ? formatDate(project.end_date)
                                            : "Ongoing"
                                    }}
                                </span>
                            </div>

                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    getStatusColor(project.status),
                                ]"
                            >
                                {{ project.status }}
                            </span>
                        </div>

                        <!-- Duration -->
                        <div class="mb-4">
                            <span
                                class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full"
                            >
                                Duration:
                                {{
                                    project.duration
                                        .split(" ")
                                        .slice(0, 4)
                                        .join(" ")
                                }}
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2 mb-4">
                            <h5 class="text-sm font-medium text-gray-700">
                                Project Description:
                            </h5>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ project.description }}
                            </p>
                        </div>

                        <!-- Outcome (if completed) -->
                        <div v-if="project.outcome" class="space-y-2">
                            <h5 class="text-sm font-medium text-gray-700">
                                Project Outcome:
                            </h5>
                            <p
                                class="text-sm text-green-600 leading-relaxed bg-green-50 p-3 rounded-lg"
                            >
                                {{ project.outcome }}
                            </p>
                        </div>
                    </div>

                    <!-- Project Icon -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Footer -->
        <div
            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mt-6"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg
                        class="w-5 h-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                        ></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-700">
                        Total Projects:
                        {{ props.employee.projects?.length ?? 0 }} Project{{
                            (props.employee.projects?.length ?? 0) !== 1
                                ? "s"
                                : ""
                        }}
                    </span>
                </div>
                <div class="text-xs text-blue-600">
                    {{
                        props.employee.projects?.filter(
                            (project) => project.is_ongoing
                        ).length ?? 0
                    }}
                    Ongoing •
                    {{
                        props.employee.projects?.filter(
                            (project) => !project.is_ongoing
                        ).length ?? 0
                    }}
                    Completed
                </div>
            </div>
        </div>
    </div>
</template>
