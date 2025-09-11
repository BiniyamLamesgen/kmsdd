<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { router as inertiaRouter } from "@inertiajs/vue3";
import NavBar from "../component/NavBar.vue";
import Footer from "../component/Footer.vue";
import EmployeeCard from "../component/EmployeeCard.vue";
import EmployeeDrawer from "../component/EmployeeDrawer.vue";
import FilterBar from "../component/FilterBar.vue";
import Pagination from "../component/Pagination.vue";
import type { ServerPaginatedResponse } from "@/types/datatable";

// Define Employee type (adjust fields as needed)
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
    img: string | null;
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
    employees: ServerPaginatedResponse<Employee>;
    departments: string[];
}>();

// Use backend employees data
const employees = computed(() => props.employees?.data ?? []);

// Pagination meta from backend - use backend default per_page
const page = ref(props.employees?.meta?.current_page ?? 1);
const perPage = ref(props.employees?.meta?.per_page ?? 10);
const total = computed(
    () => props.employees?.meta?.total ?? employees.value.length
);
const totalPages = computed(() => props.employees?.meta?.last_page ?? 1);

// Departments from backend
const departments = computed(() => props.departments ?? []);

// Filters
const searchQuery = ref("");
const selectedDepartment = ref("");
const skillQuery = ref("");

// Watch for page/perPage changes and request new data from backend
watch(page, (newPage) => {
    inertiaRouter.get(
        route("frontpage"),
        {
            page: newPage,
            perPage: perPage.value,
            search: searchQuery.value,
            department: selectedDepartment.value,
            skill: skillQuery.value,
        },
        { preserveState: true, preserveScroll: true }
    );
});

watch(perPage, (newPerPage) => {
    inertiaRouter.get(
        route("frontpage"),
        {
            page: page.value,
            perPage: newPerPage,
            search: searchQuery.value,
            department: selectedDepartment.value,
            skill: skillQuery.value,
        },
        { preserveState: true, preserveScroll: true }
    );
});

// Filter function - now sends filters to backend
const applyFilters = ({
    search,
    department,
    skill,
}: {
    search: string;
    department: string;
    skill: string;
}) => {
    searchQuery.value = search;
    selectedDepartment.value = department;
    skillQuery.value = skill;

    // Reset to page 1 when applying filters
    page.value = 1;

    // Send filter request to backend
    inertiaRouter.get(
        route("frontpage"),
        {
            page: 1,
            perPage: perPage.value,
            search: search,
            department: department,
            skill: skill,
        },
        { preserveState: true, preserveScroll: true }
    );
};

// Use backend filtered data directly (no frontend filtering)
const paginatedEmployees = computed(() => employees.value);

// Drawer
const selectedEmployee = ref<Employee | null>(null);
const openDrawer = (emp: Employee) => {
    selectedEmployee.value = emp;
};

// View mode for employee directory
const viewMode = ref("grid");
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <NavBar />

        <!-- Hero Section -->
        <section
            class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 overflow-hidden"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg
                    class="w-full h-full"
                    viewBox="0 0 1000 1000"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <defs>
                        <pattern
                            id="hero-grid"
                            x="0"
                            y="0"
                            width="50"
                            height="50"
                            patternUnits="userSpaceOnUse"
                        >
                            <circle cx="25" cy="25" r="2" fill="#3b82f6" />
                            <path
                                d="M0 25h50M25 0v50"
                                stroke="#3b82f6"
                                stroke-width="0.5"
                                opacity="0.3"
                            />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#hero-grid)" />
                </svg>
            </div>

            <!-- Floating Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                    class="absolute top-16 sm:top-20 left-4 sm:left-10 w-20 h-20 sm:w-32 sm:h-32 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full opacity-10 animate-pulse"
                ></div>
                <div
                    class="absolute top-32 sm:top-40 right-8 sm:right-20 w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full opacity-8 animate-bounce"
                    style="animation-duration: 3s"
                ></div>
                <div
                    class="absolute bottom-16 sm:bottom-20 left-1/3 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full opacity-6 animate-ping"
                    style="animation-duration: 4s"
                ></div>
            </div>

            <div
                class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 sm:pt-28 lg:pt-32 pb-12 sm:pb-16"
            >
                <div class="text-center">
                    <!-- Badge -->
                    <div
                        class="inline-flex items-center gap-2 sm:gap-3 bg-white/10 backdrop-blur-sm rounded-full px-4 sm:px-6 py-2 sm:py-3 mb-6 sm:mb-8 border border-white/20"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full p-1.5 sm:p-2"
                        >
                            <svg
                                class="w-4 h-4 sm:w-5 sm:h-5 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                        </div>
                        <span
                            class="text-white font-semibold text-sm sm:text-base"
                            >Knowledge Management System</span
                        >
                    </div>

                    <!-- Main Heading -->
                    <h1
                        class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-6xl font-bold text-white mb-4 sm:mb-6 leading-tight px-2"
                    >
                        Discover Our
                        <span
                            class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent block"
                        >
                            Talented Team
                        </span>
                    </h1>

                    <!-- Description -->
                    <p
                        class="text-base sm:text-lg md:text-xl lg:text-2xl text-blue-100 max-w-4xl mx-auto mb-8 sm:mb-12 leading-relaxed px-4"
                    >
                        Connect with experts, explore skills, and unlock
                        organizational knowledge. Find the right talent for your
                        projects and foster collaboration across teams.
                    </p>

                    <!-- Stats -->
                    <div
                        class="grid grid-cols-3 gap-4 sm:gap-6 lg:gap-8 max-w-2xl mx-auto px-4"
                    >
                        <div class="text-center">
                            <div
                                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-cyan-400 mb-1 sm:mb-2"
                            >
                                {{ total }}+
                            </div>
                            <div class="text-xs sm:text-sm text-blue-200">
                                Team Members
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-cyan-400 mb-1 sm:mb-2"
                            >
                                {{ departments.length }}+
                            </div>
                            <div class="text-xs sm:text-sm text-blue-200">
                                Departments
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-cyan-400 mb-1 sm:mb-2"
                            >
                                {{
                                    employees.reduce(
                                        (acc, emp) =>
                                            acc + (emp.skills?.length || 0),
                                        0
                                    )
                                }}+
                            </div>
                            <div class="text-xs sm:text-sm text-blue-200">
                                Skills Tracked
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wave Separator -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg
                    class="w-full h-8 sm:h-12 lg:h-16 text-white"
                    fill="currentColor"
                    viewBox="0 0 1200 120"
                    preserveAspectRatio="none"
                >
                    <path
                        d="M0,60 C200,100 400,20 600,60 C800,100 1000,20 1200,60 L1200,120 L0,120 Z"
                    ></path>
                </svg>
            </div>
        </section>

        <!-- Main Content -->
        <main class="flex-1 bg-white">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16"
            >
                <!-- Search & Filter Section -->
                <div class="mb-8 sm:mb-12">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 border border-blue-100 shadow-xl"
                    >
                        <div class="text-center mb-6 sm:mb-8">
                            <h2
                                class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-4"
                            >
                                Find the Perfect
                                <span
                                    class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent"
                                    >Expert</span
                                >
                            </h2>
                            <p
                                class="text-gray-600 text-sm sm:text-base lg:text-lg px-2"
                            >
                                Search by name, skills, department, or expertise
                                to connect with the right team member
                            </p>
                        </div>

                        <FilterBar
                            @filter="applyFilters"
                            :departments="departments"
                        />
                    </div>
                </div>

                <!-- Results Header -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8 gap-4"
                >
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-2 sm:p-3"
                        >
                            <svg
                                class="w-5 h-5 sm:w-6 sm:h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="text-xl sm:text-2xl font-bold text-gray-900"
                            >
                                Team Directory
                            </h3>
                            <p class="text-gray-600 text-sm sm:text-base">
                                {{ paginatedEmployees.length }} of
                                {{ total }} members
                            </p>
                        </div>
                    </div>

                    <!-- View Toggle -->
                    <div
                        class="hidden sm:flex items-center bg-white rounded-xl p-1 shadow-lg border border-gray-200"
                    >
                        <button
                            :class="[
                                'px-3 sm:px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200',
                                viewMode === 'grid'
                                    ? 'bg-blue-500 text-white'
                                    : 'text-gray-700',
                            ]"
                            @click="viewMode = 'grid'"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                ></path>
                            </svg>
                            <span class="hidden sm:inline">Grid View</span>
                        </button>
                        <button
                            :class="[
                                'px-3 sm:px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200',
                                viewMode === 'list'
                                    ? 'bg-blue-500 text-white'
                                    : 'text-gray-700',
                            ]"
                            @click="viewMode = 'list'"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"
                                ></path>
                            </svg>
                            <span class="hidden sm:inline">List View</span>
                        </button>
                    </div>
                </div>

                <!-- Employee Grid -->
                <div
                    v-if="viewMode === 'grid'"
                    class="grid gap-4 sm:gap-6 lg:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <EmployeeCard
                        v-for="emp in paginatedEmployees"
                        :key="emp.id"
                        :employee="emp"
                        @open="openDrawer"
                    />
                </div>

                <!-- Employee List -->
                <div v-else-if="viewMode === 'list'" class="space-y-4">
                    <div
                        v-for="emp in paginatedEmployees"
                        :key="emp.id"
                        class="bg-white rounded-xl shadow p-4 flex flex-col sm:flex-row items-center gap-4"
                    >
                        <img
                            v-if="emp.profile_picture"
                            :src="emp.profile_picture"
                            alt="Profile"
                            class="w-16 h-16 rounded-full object-cover border"
                        />
                        <div class="flex-1">
                            <div class="font-bold text-lg text-blue-700">
                                {{ emp.full_name }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ emp.position }} | {{ emp.department }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ emp.email }}
                            </div>
                        </div>
                        <button
                            @click="openDrawer(emp)"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg font-medium text-sm"
                        >
                            View
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="paginatedEmployees.length === 0"
                    class="text-center py-12 sm:py-16"
                >
                    <div
                        class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl sm:rounded-3xl p-8 sm:p-12 max-w-lg mx-auto border border-blue-100"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-3 sm:p-4 w-fit mx-auto mb-4 sm:mb-6"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4"
                        >
                            No team members found
                        </h3>
                        <p
                            class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base"
                        >
                            Try adjusting your search criteria or filters to
                            find more results.
                        </p>
                        <button
                            @click="
                                applyFilters({
                                    search: '',
                                    department: '',
                                    skill: '',
                                })
                            "
                            class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-cyan-600 transition-all duration-200 hover:scale-105 text-sm sm:text-base"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                ></path>
                            </svg>
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="totalPages > 1"
                    class="mt-12 sm:mt-16 flex justify-center"
                >
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 p-1 sm:p-2"
                    >
                        <Pagination
                            :page="page"
                            :total="total"
                            :perPage="perPage"
                            @update:page="page = $event"
                            @update:perPage="perPage = $event"
                        />
                    </div>
                </div>
            </div>
        </main>

        <!-- Employee Drawer -->
        <EmployeeDrawer
            v-if="selectedEmployee"
            :employee="selectedEmployee"
            @close="selectedEmployee = null"
        />

        <Footer />
    </div>
</template>
