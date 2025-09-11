<script setup lang="ts">
interface EmployeeSocialLinks {
    linkedin: string | null;
    facebook: string | null;
    twitter: string | null;
    github: string | null;
    personal_website: string | null;
}

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
    credential_id: string;
    created_at: string;
    updated_at: string;
    image_url?: string | null; // Added for certificate image
    image?: string | null; // Added for fallback image field
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
    endorsements?: EmployeeEndorsement[];
    created_at: string;
    updated_at: string;
}

import { ref, computed, onMounted, nextTick } from "vue";
import EmployeeStatsCards from "./EmployeeStatsCards.vue";
import EmployeeSkills from "./EmployeeSkills.vue";
import EmployeeExperiences from "./EmployeeExperiences.vue";
import EmployeeProjects from "./EmployeeProjects.vue";
import EmployeeAchievements from "./EmployeeAchievements.vue";
import EmployeeCertificates from "./EmployeeCertificates.vue";
import EmployeeEducation from "./EmployeeEducation.vue";
import EmployeeKnowledgeSharing from "./EmployeeKnowledgeSharing.vue";
import EmployeeEndorsements from "./EmployeeEndorsements.vue";
import EmployeeAdditionalInfo from "./EmployeeAdditionalInfo.vue";
import CertificateDetailModal from "./CertificateDetailModal.vue";

const props = defineProps<{ employee: any }>();

// Certificate detail modal state
const selectedCertificate = ref<EmployeeCertification | null>(null);
const certificateModalOpen = ref(false);

// Certificate detail functions
function openCertificateDetail(certificate: any) {
    // Ensure all required properties exist before assigning
    if (
        certificate &&
        typeof certificate.employee_id !== "undefined" &&
        typeof certificate.created_at !== "undefined" &&
        typeof certificate.updated_at !== "undefined"
    ) {
        selectedCertificate.value = certificate as EmployeeCertification;
        certificateModalOpen.value = true;
    }
}

function closeCertificateDetail() {
    certificateModalOpen.value = false;
    selectedCertificate.value = null;
}

// Animation states
const isVisible = ref(false);
const contentVisible = ref(false);

onMounted(() => {
    nextTick(() => {
        isVisible.value = true;
        setTimeout(() => {
            contentVisible.value = true;
        }, 150);
    });
});

const closeDrawer = () => {
    contentVisible.value = false;
    setTimeout(() => {
        isVisible.value = false;
        setTimeout(() => {
            emit("close");
        }, 300);
    }, 100);
};

const emit = defineEmits(["close"]);

// Certificates with images for display
const certsWithImages = computed(() =>
    (props.employee?.certifications ?? []).filter(
        (cert: EmployeeCertification) => cert.image_url || cert.image
    )
);

// Certificate navigation state
const certIndex = ref(0);
const visibleCount = 3;
const maxIndex = computed(() =>
    Math.max(0, certsWithImages.value.length - visibleCount)
);

function nextCert() {
    if (certIndex.value < maxIndex.value) certIndex.value += visibleCount;
}
function prevCert() {
    if (certIndex.value > 0) certIndex.value -= visibleCount;
}
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="props.employee && isVisible"
                class="fixed inset-0 z-50 flex"
            >
                <!-- Enhanced Overlay with Blur -->
                <div
                    class="flex-1 bg-black/60 backdrop-blur-sm"
                    @click="closeDrawer"
                ></div>

                <!-- Redesigned Drawer -->
                <Transition
                    enter-active-class="transition-transform duration-300 ease-out"
                    enter-from-class="translate-x-full"
                    enter-to-class="translate-x-0"
                    leave-active-class="transition-transform duration-200 ease-in"
                    leave-from-class="translate-x-0"
                    leave-to-class="translate-x-full"
                >
                    <div
                        v-if="isVisible"
                        class="w-full max-w-xl bg-white shadow-2xl overflow-hidden flex flex-col"
                    >
                        <!-- Hero Header Section -->
                        <div
                            class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 text-white"
                        >
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-10">
                                <svg
                                    class="w-full h-full"
                                    viewBox="0 0 400 400"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <defs>
                                        <pattern
                                            id="drawer-pattern"
                                            x="0"
                                            y="0"
                                            width="32"
                                            height="32"
                                            patternUnits="userSpaceOnUse"
                                        >
                                            <circle
                                                cx="16"
                                                cy="16"
                                                r="1"
                                                fill="#3b82f6"
                                            />
                                            <path
                                                d="M0 16h32M16 0v32"
                                                stroke="#3b82f6"
                                                stroke-width="0.3"
                                                opacity="0.3"
                                            />
                                        </pattern>
                                    </defs>
                                    <rect
                                        width="100%"
                                        height="100%"
                                        fill="url(#drawer-pattern)"
                                    />
                                </svg>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent"
                            ></div>

                            <!-- Close Button -->
                            <button
                                @click="closeDrawer"
                                class="absolute top-4 right-4 z-10 p-1.5 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-200 backdrop-blur-sm group"
                            >
                                <svg
                                    class="w-5 h-5 text-white group-hover:rotate-90 transition-transform duration-200"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    ></path>
                                </svg>
                            </button>

                            <!-- Profile Section -->
                            <div class="relative px-6 py-6">
                                <Transition
                                    enter-active-class="transition-all duration-500 delay-200"
                                    enter-from-class="scale-50 opacity-0"
                                    enter-to-class="scale-100 opacity-100"
                                >
                                    <div
                                        v-if="contentVisible"
                                        class="flex flex-row items-start gap-4"
                                    >
                                        <!-- Profile Picture Section -->
                                        <div class="flex-shrink-0">
                                            <div class="relative w-16 h-16">
                                                <!-- Profile Picture with Glow Effect -->
                                                <div
                                                    class="absolute inset-0 bg-white/30 rounded-full blur-xl scale-110"
                                                ></div>
                                                <div
                                                    class="relative w-full h-full rounded-full border-3 border-white/50 overflow-hidden bg-gradient-to-br from-blue-500 to-cyan-500 backdrop-blur-sm"
                                                >
                                                    <template
                                                        v-if="
                                                            props.employee
                                                                .profile_picture
                                                        "
                                                    >
                                                        <img
                                                            :src="
                                                                props.employee
                                                                    .profile_picture
                                                            "
                                                            alt="Profile"
                                                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                                                        />
                                                    </template>
                                                    <template v-else>
                                                        <div
                                                            class="w-full h-full flex items-center justify-center text-lg font-bold text-white"
                                                        >
                                                            {{
                                                                (
                                                                    props
                                                                        .employee
                                                                        .full_name ??
                                                                    ""
                                                                ).charAt(0)
                                                            }}
                                                        </div>
                                                    </template>
                                                </div>
                                                <!-- Status Indicator -->
                                                <div
                                                    class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full border-2 border-white flex items-center justify-center"
                                                >
                                                    <div
                                                        class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Name and Title Section -->
                                        <div
                                            class="flex-1 text-left space-y-1 min-w-0"
                                        >
                                            <div>
                                                <h1
                                                    class="text-lg font-bold text-white drop-shadow-lg leading-tight"
                                                >
                                                    {{
                                                        props.employee.full_name
                                                    }}
                                                </h1>
                                                <p
                                                    class="text-sm text-blue-100 font-medium"
                                                >
                                                    {{
                                                        props.employee.position
                                                    }}
                                                </p>
                                            </div>

                                            <!-- Department and Additional Info -->
                                            <div class="space-y-1">
                                                <div
                                                    class="flex items-center justify-start space-x-2"
                                                >
                                                    <div
                                                        class="w-1.5 h-1.5 bg-cyan-400 rounded-full"
                                                    ></div>
                                                    <span
                                                        class="text-blue-200 text-xs font-medium"
                                                        >{{
                                                            props.employee
                                                                .department
                                                        }}</span
                                                    >
                                                </div>

                                                <!-- Contact Info -->
                                                <div class="space-y-0.5">
                                                    <div
                                                        class="flex items-center justify-start space-x-2 text-blue-200 text-xs"
                                                    >
                                                        <svg
                                                            class="w-3 h-3 flex-shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                            ></path>
                                                        </svg>
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                props.employee
                                                                    .email
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="flex items-center justify-start space-x-2 text-blue-200 text-xs"
                                                    >
                                                        <svg
                                                            class="w-3 h-3 flex-shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                                            ></path>
                                                        </svg>
                                                        <span>{{
                                                            props.employee.phone
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Transition>
                            </div>

                            <!-- Wave Border -->
                            <div class="absolute bottom-0 left-0 right-0">
                                <svg
                                    viewBox="0 0 1200 120"
                                    preserveAspectRatio="none"
                                    class="w-full h-6 fill-white"
                                >
                                    <path
                                        d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                                    ></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 overflow-y-auto">
                            <Transition
                                enter-active-class="transition-all duration-700 delay-300"
                                enter-from-class="translate-y-8 opacity-0"
                                enter-to-class="translate-y-0 opacity-100"
                            >
                                <div
                                    v-if="contentVisible"
                                    class="p-8 space-y-8"
                                >
                                    <!-- Quick Stats Cards -->
                                    <EmployeeStatsCards
                                        :employee="props.employee"
                                    />

                                    <!-- Skills Section -->
                                    <EmployeeSkills
                                        :employee="props.employee"
                                        :contentVisible="contentVisible"
                                    />

                                    <!-- Experience Section -->
                                    <EmployeeExperiences
                                        :employee="props.employee"
                                    />

                                    <!-- Projects Section -->
                                    <EmployeeProjects
                                        :employee="props.employee"
                                    />

                                    <!-- Achievements -->
                                    <EmployeeAchievements
                                        :employee="props.employee"
                                    />

                                    <!-- Enhanced Certificates Section -->
                                    <EmployeeCertificates
                                        :employee="props.employee"
                                        @openDetail="openCertificateDetail"
                                    />

                                    <!-- Education Section -->
                                    <EmployeeEducation
                                        :employee="props.employee"
                                    />

                                    <!-- Knowledge Sharing Section -->
                                    <EmployeeKnowledgeSharing
                                        :employee="props.employee"
                                    />

                                    <!-- Endorsements Section -->
                                    <EmployeeEndorsements
                                        :employee="props.employee"
                                    />

                                    <!-- Additional Info Section -->
                                    <EmployeeAdditionalInfo
                                        :employee="props.employee"
                                    />
                                </div>
                            </Transition>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>

        <!-- Certificate Detail Modal -->
        <CertificateDetailModal
            :isOpen="certificateModalOpen"
            :certificate="selectedCertificate"
            @close="closeCertificateDetail"
        />
    </Teleport>
</template>

<style scoped>
/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth animations for dynamic content */
.v-enter-active,
.v-leave-active {
    transition: all 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
</style>
