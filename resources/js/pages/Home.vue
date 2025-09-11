<script setup lang="ts">
import About from "@/component/About.vue";
import NavBar from "@/component/NavBar.vue";
import Hero from "@/component/Hero.vue";
import type { ServerPaginatedResponse } from "@/types/datatable";
import Footer from "@/component/Footer.vue";
import Gallery from "@/component/Gallery.vue";
import HeroSlider from "@/component/HeroSlider.vue";

interface HeroSlide {
    id: number;
    title: string;
    description: string;
    image: string | null;
    created_at: string | null;
    updated_at: string | null;
}

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
    credential_id: string;
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
interface Gallery {
    id: number;
    image: string;
    alt: string | null;
    title: string;
    description: string | null;
    category: string | null;
    employee: string | null;
    date: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    employees: ServerPaginatedResponse<Employee>;
    heroSlides: ServerPaginatedResponse<HeroSlide>;
    gallery: ServerPaginatedResponse<Gallery>;
}>();
</script>
<template>
    <div class="min-h-screen bg-white">
        <NavBar />
        <Hero
            :employees="props.employees.meta.total"
            :skills="
                props.employees.data.reduce(
                    (acc, emp) => acc + (emp.skills?.length || 0),
                    0
                )
            "
            access="24/7"
        />
        <Gallery :gallery="props.gallery" />
        <!-- <HeroSlider :heroSlides="props.heroSlides" /> -->
        <About />
        <Footer />
    </div>
</template>
