<script setup lang="ts">
import { ref } from "vue";

interface EmployeeSkill {
    skill_name: string;
}

interface Employee {
    skills?: EmployeeSkill[];
}

const props = defineProps<{
    employee: Employee;
    contentVisible: boolean;
}>();
</script>

<template>
    <!-- Skills Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <div
                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"
            >
                <svg
                    class="w-4 h-4 text-blue-600"
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
            <h3 class="text-lg font-semibold text-gray-800">
                Skills & Expertise
            </h3>
        </div>

        <div class="flex flex-wrap gap-2">
            <template
                v-if="props.employee.skills && props.employee.skills.length"
            >
                <Transition
                    v-for="(skill, i) in props.employee.skills"
                    :key="i"
                    enter-active-class="transition-all duration-300"
                    :enter-from-class="`opacity-0 scale-75 translate-y-2`"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    :style="{ transitionDelay: `${i * 50}ms` }"
                >
                    <span
                        v-if="contentVisible"
                        class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 cursor-pointer group"
                    >
                        {{ skill.skill_name }}
                        <div
                            class="ml-2 w-2 h-2 bg-white/50 rounded-full group-hover:bg-white/80 transition-colors"
                        ></div>
                    </span>
                </Transition>
            </template>
            <template v-else>
                <span
                    class="text-gray-400 text-sm italic bg-gray-100 px-4 py-2 rounded-full"
                >
                    No skills listed
                </span>
            </template>
        </div>
    </div>
</template>
