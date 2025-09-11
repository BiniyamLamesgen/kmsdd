<script setup lang="ts">
import { defineProps } from "vue";

interface EmployeeSocialLinks {
    linkedin: string | null;
    facebook: string | null;
    twitter: string | null;
    github: string | null;
    personal_website: string | null;
}

interface EmployeeExperience {
    id: number;
    company_name: string;
    position: string;
    start_date: string;
    end_date: string | null;
    responsibilities: string;
    created_at: string;
    updated_at: string;
}

interface EmployeeProject {
    id: number;
    project_name: string;
    description: string;
    role: string;
    start_date: string;
    end_date: string | null;
    outcome: string | null;
    created_at: string;
    updated_at: string;
}

interface EmployeeSkill {
    id: number;
    skill_name: string;
    created_at: string;
    updated_at: string;
    pivot?: {
        employee_id: number;
        skill_id: number;
        proficiency_level: string;
        endorsements_count: number;
        created_at: string;
        updated_at: string;
    };
}

interface EmployeeAchievement {
    id: number;
    employee_id: number;
    title: string;
    description: string;
    date_awarded: string;
    created_at: string;
    updated_at: string;
}

interface EmployeeCertification {
    id: number;
    employee_id: number;
    certification_name: string;
    issuing_organization: string;
    issue_date: string;
    expiry_date: string | null;
    created_at: string;
    updated_at: string;
}

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
    id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    position: string;
    department: string;
    email: string;
    phone: string;
    internal_extension: string;
    profile_picture: string | null;
    date_joined: string;
    social_links: EmployeeSocialLinks;
    languages: string;
    mentoring_interest: string;
    availability_for_sharing: boolean;
    experiences: EmployeeExperience[];
    projects: EmployeeProject[];
    skills: EmployeeSkill[];
    achievements: EmployeeAchievement[];
    certifications: EmployeeCertification[];
    education: EmployeeEducation[];
    knowledge_sharing: EmployeeKnowledgeSharing[];
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    employee: Employee;
}>();

// Defensive: fallback to empty string if first_name or last_name is missing
// Initials can be computed in the template using employee.first_name and employee.last_name
</script>

<template>
    <!-- Modern Employee Card with Blue/Water Theme -->
    <div
        class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer border border-gray-100 hover:border-blue-200 overflow-hidden transform hover:scale-105"
        @click="$emit('open', props.employee)"
    >
        <!-- Background Gradient Overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-white to-cyan-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
        ></div>

        <!-- Decorative Element -->
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-cyan-400/5 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700"
        ></div>

        <div class="relative p-6">
            <!-- Profile Section -->
            <div class="flex items-start gap-4 mb-6">
                <div class="relative">
                    <!-- Profile Picture/Avatar -->
                    <div
                        class="h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-bold text-white text-xl overflow-hidden shadow-lg ring-4 ring-white group-hover:ring-blue-100 transition-all duration-300"
                    >
                        <template v-if="props.employee.profile_picture">
                            <img
                                :src="props.employee.profile_picture"
                                alt="Profile"
                                class="object-cover w-full h-full rounded-2xl"
                            />
                        </template>
                        <template v-else>
                            {{
                                (props.employee.first_name?.charAt(0) ?? "") +
                                (props.employee.last_name?.charAt(0) ?? "")
                            }}
                        </template>
                    </div>

                    <!-- Status Indicator -->
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full border-2 border-white flex items-center justify-center"
                    >
                        <div
                            class="w-2 h-2 bg-white rounded-full animate-pulse"
                        ></div>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <!-- Name & Position -->
                    <h3
                        class="font-bold text-gray-900 text-lg leading-tight truncate group-hover:text-blue-600 transition-colors duration-300"
                    >
                        {{ props.employee.full_name }}
                    </h3>
                    <p
                        class="text-blue-600 font-semibold text-sm mt-1 group-hover:text-blue-700 transition-colors"
                    >
                        {{ props.employee.position }}
                    </p>
                    <div class="flex items-center mt-2">
                        <div
                            class="w-2 h-2 bg-blue-400 rounded-full mr-2"
                        ></div>
                        <p class="text-xs text-gray-500 font-medium">
                            {{ props.employee.department }}
                        </p>
                    </div>
                </div>

                <!-- Action Button -->
                <button
                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-2 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-500 hover:text-blue-600"
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
                            d="M9 5l7 7-7 7"
                        ></path>
                    </svg>
                </button>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Experience -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-3 border border-blue-200/50"
                >
                    <div class="flex items-center gap-2 mb-1">
                        <div
                            class="w-6 h-6 bg-blue-500 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-3 h-3 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v6m-8-6v6m0 0v6a2 2 0 002 2h4a2 2 0 002-2v-6m-8 0h8"
                                ></path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-700"
                            >Experience</span
                        >
                    </div>
                    <p class="text-sm font-bold text-blue-900">
                        {{ props.employee.experiences.length }} 
                    </p>
                </div>

                <!-- Projects -->
                <div
                    class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-xl p-3 border border-cyan-200/50"
                >
                    <div class="flex items-center gap-2 mb-1">
                        <div
                            class="w-6 h-6 bg-cyan-500 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-3 h-3 text-white"
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
                        <span class="text-xs font-semibold text-cyan-700"
                            >Projects</span
                        >
                    </div>
                    <p class="text-sm font-bold text-cyan-900">
                        {{ (props.employee.projects ?? []).length }}
                    </p>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div
                        class="w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-3 h-3 text-white"
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
                    <span class="text-sm font-bold text-gray-800"
                        >Top Skills</span
                    >
                </div>

                <div class="flex flex-wrap gap-2">
                    <template v-if="(props.employee.skills ?? []).length > 0">
                        <span
                            v-for="(skill, index) in (
                                props.employee.skills ?? []
                            ).slice(0, 3)"
                            :key="index"
                            class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs rounded-full font-medium shadow-sm hover:shadow-md transition-shadow duration-200"
                        >
                            {{ skill.skill_name }}
                        </span>
                        <span
                            v-if="(props.employee.skills ?? []).length > 3"
                            class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-full font-medium transition-colors duration-200 cursor-pointer"
                        >
                            +{{ (props.employee.skills ?? []).length - 3 }} more
                        </span>
                    </template>
                    <template v-else>
                        <span
                            class="text-xs text-gray-400 italic bg-gray-50 px-3 py-1 rounded-full"
                        >
                            No skills listed
                        </span>
                    </template>
                </div>
            </div>

            <!-- Projects Preview -->
            <div class="mb-6" v-if="(props.employee.projects ?? []).length > 0">
                <div class="flex items-center gap-2 mb-3">
                    <div
                        class="w-5 h-5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-3 h-3 text-white"
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
                    <span class="text-sm font-bold text-gray-800"
                        >Recent Projects</span
                    >
                </div>

                <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                    <div class="text-sm text-gray-700 leading-relaxed">
                        <template
                            v-if="(props.employee.projects ?? []).length > 0"
                        >
                            <div class="space-y-1">
                                <div
                                    v-for="(project, index) in (
                                        props.employee.projects ?? []
                                    ).slice(0, 2)"
                                    :key="index"
                                    class="flex items-center gap-2"
                                >
                                    <div
                                        class="w-1.5 h-1.5 bg-blue-400 rounded-full"
                                    ></div>
                                    <span
                                        class="text-xs text-gray-600 font-medium"
                                        >{{ project.project_name }}</span
                                    >
                                </div>
                                <span
                                    v-if="
                                        (props.employee.projects ?? []).length >
                                        2
                                    "
                                    class="text-xs text-blue-500 font-medium ml-3.5"
                                >
                                    +{{
                                        (props.employee.projects ?? []).length -
                                        2
                                    }}
                                    more projects
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Contact Actions -->
            <div
                class="flex items-center justify-between pt-4 border-t border-gray-100"
            >
                <!-- Social Links -->
                <div class="flex gap-2">
                    <a
                        v-if="props.employee.social_links.linkedin"
                        :href="props.employee.social_links.linkedin"
                        target="_blank"
                        rel="noopener"
                        class="group/social w-8 h-8 bg-blue-50 hover:bg-blue-500 text-blue-500 hover:text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110"
                        title="LinkedIn"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5v-14c0-2.76-2.24-5-5-5zm-11 19h-3v-10h3v10zm-1.5-11.25c-.966 0-1.75-.784-1.75-1.75s.784-1.75 1.75-1.75 1.75.784 1.75 1.75-.784 1.75-1.75 1.75zm13.5 11.25h-3v-5.5c0-1.104-.896-2-2-2s-2 .896-2 2v5.5h-3v-10h3v1.354c.684-.632 1.582-1.354 2.646-1.354 2.206 0 4 1.794 4 4v6z"
                            />
                        </svg>
                    </a>

                    <a
                        v-if="props.employee.social_links.github"
                        :href="props.employee.social_links.github"
                        target="_blank"
                        rel="noopener"
                        class="group/social w-8 h-8 bg-gray-50 hover:bg-gray-800 text-gray-600 hover:text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110"
                        title="GitHub"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.757-1.333-1.757-1.089-.745.083-.729.083-.729 1.205.084 1.84 1.236 1.84 1.236 1.07 1.834 2.809 1.304 3.495.997.108-.775.418-1.305.762-1.605-2.665-.305-5.466-1.332-5.466-5.93 0-1.31.469-2.381 1.236-3.221-.124-.303-.535-1.523.117-3.176 0 0 1.008-.322 3.301 1.23a11.52 11.52 0 013.003-.404c1.018.005 2.045.138 3.003.404 2.291-1.553 3.297-1.23 3.297-1.23.653 1.653.242 2.873.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.803 5.624-5.475 5.921.43.371.823 1.102.823 2.222v3.293c0 .322.218.694.825.576C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"
                            />
                        </svg>
                    </a>

                    <a
                        v-if="props.employee.social_links.twitter"
                        :href="props.employee.social_links.twitter"
                        target="_blank"
                        rel="noopener"
                        class="group/social w-8 h-8 bg-sky-50 hover:bg-sky-500 text-sky-500 hover:text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110"
                        title="Twitter"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M24 4.557a9.93 9.93 0 01-2.828.775 4.932 4.932 0 002.165-2.724c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482c-4.086-.205-7.713-2.164-10.141-5.144-.423.724-.666 1.562-.666 2.475 0 1.708.87 3.216 2.188 4.099a4.904 4.904 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.936 4.936 0 01-2.224.084c.627 1.956 2.444 3.377 4.6 3.417A9.867 9.867 0 010 21.543a13.94 13.94 0 007.548 2.212c9.058 0 14.009-7.513 14.009-14.009 0-.213-.005-.425-.014-.636A10.025 10.025 0 0024 4.557z"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hover Effect Glow -->
        <div
            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
        ></div>
    </div>
</template>

<style>
/* Tailwind handles most styles */
</style>
