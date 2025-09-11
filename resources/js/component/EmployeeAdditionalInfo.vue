<script setup lang="ts">
interface Employee {
    languages?: string;
    availability_for_sharing?: boolean;
    mentoring_interest?: string;
}

const props = defineProps<{
    employee: Employee;
}>();
</script>

<template>
    <!-- Additional Info Section -->
    <div
        class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200"
    >
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Additional Information
        </h3>

        <div class="grid gap-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-600">Languages</span>
                <span class="text-sm text-gray-800">
                    {{ props.employee.languages || "Not specified" }}
                </span>
            </div>

            <div class="h-px bg-gray-200"></div>

            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-600"
                    >Available for Sharing</span
                >
                <div class="flex items-center space-x-2">
                    <div
                        :class="
                            props.employee.availability_for_sharing
                                ? 'bg-cyan-400'
                                : 'bg-gray-400'
                        "
                        class="w-2 h-2 rounded-full"
                    ></div>
                    <span class="text-sm text-gray-800">
                        {{
                            props.employee.availability_for_sharing
                                ? "Yes"
                                : "No"
                        }}
                    </span>
                </div>
            </div>

            <div class="h-px bg-gray-200"></div>

            <div>
                <span class="text-sm font-medium text-gray-600 block mb-2"
                    >Mentoring Interest</span
                >
                <p
                    class="text-sm text-gray-800 bg-white rounded-lg p-3 border border-gray-100"
                >
                    {{
                        props.employee.mentoring_interest ||
                        "No mentoring interests specified"
                    }}
                </p>
            </div>
        </div>
    </div>
</template>
