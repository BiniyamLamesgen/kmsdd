<script setup lang="ts">
import { defineProps } from "vue";

interface EmployeeEndorsement {
    id: number;
    employee_id: number;
    skill_id: number;
    endorsed_by: number;
    endorsement_message: string | null;
    created_at: string;
    updated_at: string;
    skill?: {
        id: number;
        skill_name: string;
    };
    endorser?: {
        id: number;
        first_name: string;
        last_name: string;
        full_name: string;
    };
}

interface Employee {
    endorsements?: EmployeeEndorsement[];
}

const props = defineProps<{ employee: Employee }>();

// Helper function to format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

// Helper function to get time since endorsement
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
</script>

<template>
    <div
        v-if="
            props.employee?.endorsements &&
            props.employee.endorsements.length > 0
        "
        class="space-y-4"
    >
        <!-- Section Header -->
        <div class="flex items-center gap-3 mb-6">
            <div
                class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg p-2"
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
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    Skill Endorsements
                </h3>
                <p class="text-sm text-gray-600">
                    Professional recommendations and validations
                </p>
            </div>
        </div>

        <!-- Endorsements List -->
        <div class="space-y-4">
            <div
                v-for="endorsement in props.employee.endorsements"
                :key="endorsement.id"
                class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:border-emerald-200"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <!-- Skill and Endorser -->
                        <div class="mb-3">
                            <h4
                                class="text-lg font-semibold text-gray-900 mb-1"
                            >
                                {{
                                    endorsement.skill?.skill_name ||
                                    "Unknown Skill"
                                }}
                            </h4>
                            <p class="text-emerald-600 font-medium text-base">
                                Endorsed by
                                {{
                                    endorsement.endorser?.full_name ||
                                    "Anonymous"
                                }}
                            </p>
                        </div>

                        <!-- Date -->
                        <div
                            class="flex items-center gap-2 text-sm text-gray-600 mb-4"
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
                            <span>{{
                                formatDate(endorsement.created_at)
                            }}</span>
                            <span class="text-gray-400">•</span>
                            <span>{{
                                getTimeSince(endorsement.created_at)
                            }}</span>
                        </div>

                        <!-- Endorsement Message -->
                        <div
                            v-if="endorsement.endorsement_message"
                            class="space-y-2"
                        >
                            <h5 class="text-sm font-medium text-gray-700">
                                Endorsement Message:
                            </h5>
                            <div
                                class="bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-lg"
                            >
                                <p
                                    class="text-sm text-gray-700 leading-relaxed italic"
                                >
                                    "{{ endorsement.endorsement_message }}"
                                </p>
                            </div>
                        </div>
                        <div v-else class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 italic">
                                No message provided with this endorsement
                            </p>
                        </div>
                    </div>

                    <!-- Endorsement Icon -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-emerald-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                ></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Footer -->
        <div
            class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 mt-6"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg
                        class="w-5 h-5 text-emerald-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <span class="text-sm font-medium text-emerald-700">
                        Total Endorsements:
                        {{
                            props.employee.endorsements?.length || 0
                        }}
                        Endorsement{{
                            (props.employee.endorsements?.length || 0) !== 1
                                ? "s"
                                : ""
                        }}
                    </span>
                </div>
                <div class="text-xs text-emerald-600">
                    {{
                        new Set(
                            props.employee.endorsements?.map(
                                (e) => e.skill?.skill_name
                            ) || []
                        ).size
                    }}
                    Unique Skills •
                    {{
                        new Set(
                            props.employee.endorsements?.map(
                                (e) => e.endorsed_by
                            ) || []
                        ).size
                    }}
                    Different Endorsers
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add any specific styling if needed */
</style>
