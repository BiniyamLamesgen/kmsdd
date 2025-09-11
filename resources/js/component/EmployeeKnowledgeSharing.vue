<script setup lang="ts">
interface EmployeeKnowledgeSharing {
    id: number;
    employee_id: number;
    document_title: string;
    document_type: string;
    link: string | null;
    date_shared: string;
    created_at: string;
    updated_at: string;
}

interface Employee {
    knowledge_sharing?: EmployeeKnowledgeSharing[];
}

const props = defineProps<{
    employee: Employee;
}>();
</script>

<template>
    <!-- Knowledge Sharing Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <div
                class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center"
            >
                <svg
                    class="w-4 h-4 text-teal-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                    ></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">
                Knowledge Sharing
            </h3>
        </div>
        <div class="space-y-3">
            <template v-if="props.employee.knowledge_sharing?.length">
                <div
                    v-for="(knowledge, i) in props.employee.knowledge_sharing"
                    :key="i"
                    class="bg-gradient-to-br from-white to-teal-50 rounded-xl p-4 border border-teal-200 shadow-sm hover:shadow-md transition-all duration-200 group cursor-pointer"
                >
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform"
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
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4
                                class="font-semibold text-gray-800 group-hover:text-teal-600 transition-colors"
                            >
                                {{ knowledge.document_title }}
                            </h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <span
                                    class="text-xs bg-teal-100 text-teal-800 px-2 py-1 rounded-full"
                                >
                                    {{ knowledge.document_type }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4 mt-3">
                                <span class="text-xs text-gray-500">
                                    {{
                                        new Date(
                                            knowledge.date_shared
                                        ).toLocaleDateString()
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <p
                    class="text-gray-400 text-sm italic text-center py-4 bg-gray-50 rounded-lg"
                >
                    No knowledge sharing available
                </p>
            </template>
        </div>
    </div>
</template>
