<script setup lang="ts">
interface EmployeeEducation {
    id: number;
    employee_id: number;
    degree: string;
    field_of_study: string;
    institution: string;
    graduation_year: number;
    created_at: string;
    updated_at: string;
}

interface Employee {
    education?: EmployeeEducation[];
}

const props = defineProps<{
    employee: Employee;
}>();
</script>

<template>
    <!-- Education Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <div
                class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center"
            >
                <svg
                    class="w-4 h-4 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                    ></path>
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                    ></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Education</h3>
        </div>
        <div class="space-y-3">
            <template v-if="props.employee.education?.length">
                <div
                    v-for="(education, i) in props.employee.education"
                    :key="i"
                    class="bg-gradient-to-br from-white to-blue-50 rounded-xl p-4 border border-blue-200 shadow-sm hover:shadow-md transition-all duration-200"
                >
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0"
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
                                    d="M12 14l9-5-9-5-9 5 9 5z"
                                ></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">
                                {{ education.degree }}
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ education.field_of_study }} •
                                {{ education.institution }}
                            </p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span
                                    class="text-xs text-blue-600 font-medium"
                                    >{{ education.graduation_year }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <p
                    class="text-gray-400 text-sm italic text-center py-4 bg-gray-50 rounded-lg"
                >
                    No education records available
                </p>
            </template>
        </div>
    </div>
</template>
