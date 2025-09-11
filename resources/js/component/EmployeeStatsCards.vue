<script setup lang="ts">
interface Employee {
    experiences?: any[];
    skills?: any[];
}

const props = defineProps<{
    employee: Employee;
}>();
</script>

<template>
    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4">
        <div
            class="bg-gradient-to-br from-blue-50 to-cyan-100 rounded-xl p-4 border border-blue-200/50"
        >
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center"
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
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"
                        ></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ props.employee.experiences?.length || 0 }}
                    </p>
                    <p class="text-sm text-gray-600"> Experience</p>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-cyan-50 to-blue-100 rounded-xl p-4 border border-cyan-200/50"
        >
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 bg-cyan-500 rounded-lg flex items-center justify-center"
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
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                        ></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ props.employee.skills?.length || 0 }}
                    </p>
                    <p class="text-sm text-gray-600">Skills</p>
                </div>
            </div>
        </div>
    </div>
</template>
