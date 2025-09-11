<script setup lang="ts">
import AppLayout from "../layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "../components/ui/card";
import { Separator } from "../components/ui/separator";
import { Badge } from "../components/ui/badge";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "../components/ui/table";
import {
    User,
    Award,
    BookOpen,
    Briefcase,
    GraduationCap,
    Layers,
    Star,
    FileText,
    CheckCircle,
    Share2,
    Projector,
} from "lucide-vue-next";
import Chart from "primevue/chart";

const props = defineProps<{
    stats: {
        employees: { total: number; recent: any[] };
        skills: { total: number; top: any[] };
        employeeSkills: { total: number };
        endorsements: { total: number; recent: any[] };
        knowledgeSharing: { total: number; recent: any[] };
        projects: { total: number; recent: any[] };
        achievements: { total: number; recent: any[] };
        certifications: { total: number; recent: any[] };
        education: { total: number; recent: any[] };
        experiences: { total: number; recent: any[] };
    };
}>();

const summaryCards = [
    {
        label: "Employees",
        value: props.stats.employees.total,
        icon: User,
        color: "bg-blue-600",
    },
    {
        label: "Endorsements",
        value: props.stats.endorsements.total,
        icon: CheckCircle,
        color: "bg-green-600",
    },
    {
        label: "Projects",
        value: props.stats.projects.total,
        icon: Projector,
        color: "bg-pink-600",
    },
    {
        label: "Achievements",
        value: props.stats.achievements.total,
        icon: Award,
        color: "bg-orange-500",
    },
    {
        label: "Certifications",
        value: props.stats.certifications.total,
        icon: BookOpen,
        color: "bg-teal-600",
    },
];

// Helper: Map employee_id to employee name for tables
function getEmployeeName(id: number) {
    const emp = props.stats.employees.recent.find((e) => e.id === id);
    return emp ? `${emp.first_name} ${emp.last_name}` : `ID: ${id}`;
}

// Employees by Department (bar)
const departmentCounts = props.stats.employees.recent.reduce((acc, emp) => {
    acc[emp.department] = (acc[emp.department] || 0) + 1;
    return acc;
}, {});
const departmentChartData = {
    labels: Object.keys(departmentCounts),
    datasets: [
        {
            label: "Employees by Department",
            data: Object.values(departmentCounts),
            backgroundColor: [
                "#3b82f6",
                "#f59e42",
                "#10b981",
                "#6366f1",
                "#f43f5e",
                "#a3e635",
            ],
        },
    ],
};
const departmentChartOptions = {
    responsive: true,
    plugins: { legend: { display: false } },
};

// Top Skills (pie)
const skillLabels = props.stats.skills.top.map((s) => s.skill_name);
const skillCounts = props.stats.skills.top.map((s) => s.employees_count ?? 0);
const skillChartData = {
    labels: skillLabels,
    datasets: [
        {
            label: "Top Skills",
            data: skillCounts,
            backgroundColor: [
                "#fbbf24",
                "#3b82f6",
                "#10b981",
                "#6366f1",
                "#f43f5e",
            ],
        },
    ],
};
const skillChartOptions = {
    responsive: true,
    plugins: { legend: { display: true, position: "bottom" } },
};

// Endorsements per Skill (bar)
const endorsementSkillLabels = props.stats.endorsements.recent.map(
    (e) => e.skill?.skill_name ?? "Unknown"
);
const endorsementSkillCounts = endorsementSkillLabels.reduce((acc, label) => {
    acc[label] = (acc[label] || 0) + 1;
    return acc;
}, {});
const endorsementSkillChartData = {
    labels: Object.keys(endorsementSkillCounts),
    datasets: [
        {
            label: "Endorsements per Skill",
            data: Object.values(endorsementSkillCounts),
            backgroundColor: "#10b981",
        },
    ],
};
const endorsementSkillChartOptions = {
    responsive: true,
    plugins: { legend: { display: false } },
};

// Projects by Role (doughnut)
const projectRoleLabels = props.stats.projects.recent.map(
    (p) => p.role ?? "Unknown"
);
const projectRoleCounts = projectRoleLabels.reduce((acc, label) => {
    acc[label] = (acc[label] || 0) + 1;
    return acc;
}, {});
const projectRoleChartData = {
    labels: Object.keys(projectRoleCounts),
    datasets: [
        {
            label: "Projects by Role",
            data: Object.values(projectRoleCounts),
            backgroundColor: [
                "#6366f1",
                "#f43f5e",
                "#3b82f6",
                "#fbbf24",
                "#10b981",
            ],
        },
    ],
};
const projectRoleChartOptions = {
    responsive: true,
    plugins: { legend: { display: true, position: "bottom" } },
};

// Achievements by Year (line)
const achievementYearLabels = props.stats.achievements.recent
    .map((a) => a.date_awarded?.slice(0, 4))
    .filter(Boolean);
const achievementYearCounts = achievementYearLabels.reduce((acc, year) => {
    acc[year] = (acc[year] || 0) + 1;
    return acc;
}, {});
const achievementYearChartData = {
    labels: Object.keys(achievementYearCounts),
    datasets: [
        {
            label: "Achievements by Year",
            data: Object.values(achievementYearCounts),
            borderColor: "#f59e42",
            backgroundColor: "#fbbf24",
            fill: true,
            tension: 0.4,
        },
    ],
};
const achievementYearChartOptions = {
    responsive: true,
    plugins: { legend: { display: true, position: "bottom" } },
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="[{ title: 'Dashboard', href: '/dashboard' }]">
        <div
            class="flex flex-col gap-10 p-2 sm:p-6 bg-gradient-to-br from-gray-50 via-white to-blue-50 min-h-screen"
        >
            <!-- Summary Cards (KPI indicators) -->
            <div
                class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-10"
            >
                <Card
                    v-for="card in summaryCards"
                    :key="card.label"
                    class="shadow-xl border border-gray-200 bg-white rounded-xl flex flex-col items-center justify-center py-6 px-4 transition hover:scale-[1.03] hover:shadow-2xl"
                >
                    <CardHeader
                        class="flex flex-col items-center gap-3 text-center"
                    >
                        <div
                            class="rounded-full p-3"
                            :class="card.color + ' bg-opacity-20'"
                        >
                            <component
                                :is="card.icon"
                                class="h-8 w-8 sm:h-10 sm:w-10"
                                :class="card.color"
                            />
                        </div>
                        <CardTitle
                            class="text-lg font-semibold text-gray-800"
                            >{{ card.label }}</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight"
                        >
                            {{ card.value }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Charts Section -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-10"
            >
                <Card
                    class="shadow-lg border border-gray-100 bg-white rounded-xl"
                >
                    <CardHeader>
                        <CardTitle>Employees by Department</CardTitle>
                        <CardDescription>
                            Distribution of employees across departments
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Chart
                            type="bar"
                            :data="departmentChartData"
                            :options="departmentChartOptions"
                            class="h-[18rem] w-full"
                        />
                    </CardContent>
                </Card>
                <Card
                    class="shadow-lg border border-gray-100 bg-white rounded-xl"
                >
                    <CardHeader>
                        <CardTitle>Top Skills</CardTitle>
                        <CardDescription>
                            Most popular skills among employees
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Chart
                            type="pie"
                            :data="skillChartData"
                            :options="skillChartOptions"
                            class="h-[18rem] w-full"
                        />
                    </CardContent>
                </Card>
                <Card
                    class="shadow-lg border border-gray-100 bg-white rounded-xl"
                >
                    <CardHeader>
                        <CardTitle>Endorsements per Skill</CardTitle>
                        <CardDescription>
                            Number of endorsements for each skill
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Chart
                            type="bar"
                            :data="endorsementSkillChartData"
                            :options="endorsementSkillChartOptions"
                            class="h-[18rem] w-full"
                        />
                    </CardContent>
                </Card>
                <Card
                    class="shadow-lg border border-gray-100 bg-white rounded-xl"
                >
                    <CardHeader>
                        <CardTitle>Projects by Role</CardTitle>
                        <CardDescription>
                            Distribution of project roles
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Chart
                            type="doughnut"
                            :data="projectRoleChartData"
                            :options="projectRoleChartOptions"
                            class="h-[18rem] w-full"
                        />
                    </CardContent>
                </Card>
                <Card
                    class="shadow-lg border border-gray-100 bg-white rounded-xl"
                >
                    <CardHeader>
                        <CardTitle>Achievements by Year</CardTitle>
                        <CardDescription>
                            Achievements trend over years
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Chart
                            type="line"
                            :data="achievementYearChartData"
                            :options="achievementYearChartOptions"
                            class="h-[18rem] w-full"
                        />
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Activity Cards (replaces tables) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- Employees -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Employees</CardTitle>
                        <CardDescription
                            >Newest employees in the system</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="emp in props.stats.employees.recent"
                                :key="emp.id"
                                class="flex items-center gap-4"
                            >
                                <User class="h-8 w-8 text-blue-600" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ emp.first_name }} {{ emp.last_name }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ emp.position }} &mdash;
                                        {{ emp.department }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ emp.created_at?.slice(0, 10) }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Skills -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Top Skills</CardTitle>
                        <CardDescription>Most assigned skills</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="skill in props.stats.skills.top"
                                :key="skill.id"
                                class="flex items-center gap-4"
                            >
                                <Star class="h-8 w-8 text-yellow-500" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ skill.skill_name }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Assigned to
                                        {{ skill.employees_count }} employee(s)
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Endorsements -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Endorsements</CardTitle>
                        <CardDescription
                            >Latest skill endorsements</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="endorsement in props.stats.endorsements
                                    .recent"
                                :key="endorsement.id"
                                class="flex items-start gap-4"
                            >
                                <CheckCircle
                                    class="h-8 w-8 text-green-600 mt-1"
                                />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{
                                            endorsement.employee?.full_name ||
                                            getEmployeeName(
                                                endorsement.employee_id
                                            )
                                        }}
                                        <span
                                            class="text-xs text-muted-foreground"
                                            >endorsed for
                                            <span class="font-bold">{{
                                                endorsement.skill?.skill_name
                                            }}</span></span
                                        >
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        By
                                        {{
                                            endorsement.endorsed_by?.full_name
                                        }}
                                        &mdash;
                                        {{
                                            endorsement.created_at?.slice(0, 10)
                                        }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Knowledge Sharing -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Knowledge Sharing</CardTitle>
                        <CardDescription
                            >Latest shared documents</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="ks in props.stats.knowledgeSharing
                                    .recent"
                                :key="ks.id"
                                class="flex items-start gap-4"
                            >
                                <Share2 class="h-8 w-8 text-purple-600 mt-1" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ ks.document_title }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        <Badge>{{ ks.document_type }}</Badge>
                                        &mdash;
                                        {{
                                            getEmployeeName(ks.employee_id)
                                        }}
                                        &mdash;
                                        {{ ks.created_at?.slice(0, 10) }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Projects -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Projects</CardTitle>
                        <CardDescription>Latest projects</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="proj in props.stats.projects.recent"
                                :key="proj.id"
                                class="flex items-start gap-4"
                            >
                                <Projector class="h-8 w-8 text-pink-600 mt-1" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ proj.project_name }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Role: {{ proj.role }} &mdash;
                                        {{
                                            getEmployeeName(proj.employee_id)
                                        }}
                                        &mdash;
                                        {{ proj.created_at?.slice(0, 10) }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Achievements -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Achievements</CardTitle>
                        <CardDescription>Latest achievements</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="ach in props.stats.achievements.recent"
                                :key="ach.id"
                                class="flex items-start gap-4"
                            >
                                <Award class="h-8 w-8 text-orange-500 mt-1" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ ach.title }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{
                                            getEmployeeName(ach.employee_id)
                                        }}
                                        &mdash; {{ ach.date_awarded }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Certifications -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Certifications</CardTitle>
                        <CardDescription>Latest certifications</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="cert in props.stats.certifications
                                    .recent"
                                :key="cert.id"
                                class="flex items-start gap-4"
                            >
                                <BookOpen class="h-8 w-8 text-teal-600 mt-1" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ cert.certification_name }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{
                                            getEmployeeName(cert.employee_id)
                                        }}
                                        &mdash; Issue:
                                        {{ cert.issue_date }} &mdash; Expiry:
                                        {{ cert.expiry_date }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Education -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Education</CardTitle>
                        <CardDescription
                            >Latest education records</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="edu in props.stats.education.recent"
                                :key="edu.id"
                                class="flex items-start gap-4"
                            >
                                <GraduationCap
                                    class="h-8 w-8 text-indigo-600 mt-1"
                                />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ edu.field_of_study }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{
                                            getEmployeeName(edu.employee_id)
                                        }}
                                        &mdash; {{ edu.institution }} &mdash;
                                        {{ edu.graduation_year }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
                <!-- Experiences -->
                <Card class="shadow border border-gray-100 bg-white rounded-xl">
                    <CardHeader>
                        <CardTitle>Recent Experiences</CardTitle>
                        <CardDescription>Latest experiences</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-5">
                            <li
                                v-for="exp in props.stats.experiences.recent"
                                :key="exp.id"
                                class="flex items-start gap-4"
                            >
                                <Briefcase class="h-8 w-8 text-gray-700 mt-1" />
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ exp.company_name }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{
                                            getEmployeeName(exp.employee_id)
                                        }}
                                        &mdash; {{ exp.position }} &mdash;
                                        {{ exp.start_date }} -
                                        {{ exp.end_date }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
