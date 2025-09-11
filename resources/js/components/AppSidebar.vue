<script setup lang="ts">
import NavMain from "../components/NavMain.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "../components/ui/sidebar";
import { type NavItem } from "../types";
import { Link } from "@inertiajs/vue3";
import {
    BookOpen,
    Building2,
    CreditCard,
    FileText,
    Heart,
    HelpCircle,
    Image,
    Info,
    LayoutGrid,
    Mail,
    MapPin,
    Newspaper,
    Target,
    UserCircle,
    Users,
} from "lucide-vue-next";

import AppLogo from "./AppLogo.vue";
import NavUser from "./NavUser.vue";
import { route } from "ziggy-js";

interface ContentManagementProps {
    contentManagementLogo?: string;
}

defineProps<ContentManagementProps>();

const mainNavItems: NavItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard"),
        icon: LayoutGrid,
    },
    {
        title: "Hero Slides",
        href: route("hero-slides.index"),
        icon: Image,
    },
    {
        title: "Employee Management",
        href: route("employees.index"),
        icon: Newspaper,
    },
    {
        title: "Departments",
        href: route("departments.index"),
        icon: Users,
    },
    {
        title: "Experience",
        href: route("experiences.index"),
        icon: Target,
    },
    {
        title: "Projects",
        href: route("projects.index"),
        icon: Image,
    },
    {
        title: "Skills",
        href: route("skills.index"),
        icon: CreditCard,
    },
    {
        title: "Employee Skills",
        href: route("employee-skills.index"),
        icon: FileText,
    },
    {
        title: "Achievements",
        href: route("achievements.index"),
        icon: Info,
    },
    {
        title: "Certifications",
        href: route("certifications.index"),
        icon: HelpCircle,
    },
    {
        title: "Educations",
        href: route("educations.index"),
        icon: Heart,
    },
    {
        title: "Knowledge Management",
        href: route("knowledge-management.index"),
        icon: Mail,
    },
    {
        title: "Endorsements",
        href: route("endorsements.index"),
        icon: Building2,
    },
    {
        title: "Gallery",
        href: route("gallery.index"),
        icon: Image,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo
                                :contentManagementLogo="
                                    contentManagementLogo ?? ''
                                "
                            />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
