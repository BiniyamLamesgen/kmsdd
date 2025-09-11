<script setup lang="ts">
import { defineProps } from "vue";

interface EmployeeExperience {
    id: number;
    employee_id: number;
    company_name: string;
    position: string;
    start_date: string;
    end_date: string | null;
    responsibilities: string;
    duration: string;
    created_at: string;
    updated_at: string;
}

interface Employee {
    experiences: EmployeeExperience[];
}

const props = defineProps<{ employee: Employee }>();

// Helper function to format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
    });
};

// Helper function to get status
const getStatus = (endDate: string | null) => {
    return endDate ? "Completed" : "Current";
};

// Helper function to get status color
const getStatusColor = (endDate: string | null) => {
    return endDate
        ? "bg-gray-100 text-gray-700"
        : "bg-green-100 text-green-700";
};
</script>

<template>
    <div v-if="props.employee?.experiences?.length > 0" class="space-y-4">
        <!-- Section Header -->
        <div class="flex items-center gap-3 mb-6">
            <div
                class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-2"
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
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z"
                    ></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    Work Experience
                </h3>
                <p class="text-sm text-gray-600">
                    Professional background and career history
                </p>
            </div>
        </div>

        <!-- Experiences List -->
        <div class="space-y-4">
            <div
                v-for="experience in props.employee.experiences"
                :key="experience.id"
                class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:border-purple-200"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <!-- Position and Company -->
                        <div class="mb-3">
                            <h4
                                class="text-lg font-semibold text-gray-900 mb-1"
                            >
                                {{ experience.position }}
                            </h4>
                            <p class="text-purple-600 font-medium text-base">
                                {{ experience.company_name }}
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
                                    {{ formatDate(experience.start_date) }} -
                                    {{
                                        experience.end_date
                                            ? formatDate(experience.end_date)
                                            : "Present"
                                    }}
                                </span>
                            </div>

                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    getStatusColor(experience.end_date),
                                ]"
                            >
                                {{ getStatus(experience.end_date) }}
                            </span>
                        </div>

                        <!-- Duration -->
                        <div class="mb-4">
                            <span
                                class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full"
                            >
                                Duration:
                                {{
                                    experience.duration
                                        .split(" ")
                                        .slice(0, 4)
                                        .join(" ")
                                }}
                            </span>
                        </div>

                        <!-- Responsibilities -->
                        <div class="space-y-2">
                            <h5 class="text-sm font-medium text-gray-700">
                                Key Responsibilities:
                            </h5>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ experience.responsibilities }}
                            </p>
                        </div>
                    </div>

                    <!-- Experience Icon -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                ></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Footer -->
        <div
            class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 mt-6"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg
                        class="w-5 h-5 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                        ></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-700">
                        Total Experience:
                        {{ props.employee.experiences.length }} Position{{
                            props.employee.experiences.length !== 1 ? "s" : ""
                        }}
                    </span>
                </div>
                <div class="text-xs text-purple-600">
                    {{
                        props.employee.experiences.filter(
                            (exp) => !exp.end_date
                        ).length
                    }}
                    Current •
                    {{
                        props.employee.experiences.filter((exp) => exp.end_date)
                            .length
                    }}
                    Previous
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add any specific styling if needed */
</style>
