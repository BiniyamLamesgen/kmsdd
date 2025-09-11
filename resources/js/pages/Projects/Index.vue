<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { Edit, Plus, Trash2, Eye } from "lucide-vue-next";
import { computed, ref } from "vue";
import { route } from "ziggy-js";
import { createColumn } from "../../components/DataTable/renderers";
import ServerDataTable from "../../components/DataTable/ServerDataTable.vue";
import { Button } from "../../components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "../../components/ui/dialog";
import { useServerDataTable } from "../../composables/useServerDataTable";
import AppLayout from "../../layouts/AppLayout.vue";
import { BreadcrumbItem } from "../../types";

interface Employee {
    id: number;
    full_name: string;
    position: string;
    department: string;
    email: string;
}

interface Project {
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
    employee: Employee;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    projects: {
        data: Project[];
        meta: {
            total: number;
            count: number;
            per_page: number;
            current_page: number;
            total_pages?: number;
            from: number;
            last_page: number;
            links: Array<{
                url: string | null;
                label: string;
                active: boolean;
            }>;
            path: string;
            to: number;
        };
        links: {
            first: string;
            last: string;
            prev: string | null;
            next: string | null;
        };
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Projects", href: route("projects.index") },
];

const {
    handleSearch,
    handleSort,
    handlePageChange,
    handleFilter,
    clearFilters,
    refresh,
    loading,
} = useServerDataTable({
    baseRoute: route("projects.index"),
    defaultSort: { field: "project_name", order: "asc" },
    defaultPerPage: 10,
});

const selectedItems = ref<Project[]>([]);
const itemToDelete = ref<Project | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Project[]>([]);
const deleteForm = useForm({});

const columns = computed(() => [
    createColumn("id", "ID", { sortable: true, searchable: false }),
    createColumn("project_name", "Project Name", {
        sortable: true,
        searchable: true,
    }),
    createColumn("description", "Description", {
        sortable: false,
        searchable: true,
        render: (val: string) => val?.slice(0, 60) + (val?.length > 60 ? "..." : ""),
    }),
    createColumn("role", "Role", { sortable: true, searchable: true }),
    createColumn("employee.full_name", "Employee", {
        sortable: true,
        searchable: true,
        render: (_: any, row: Project) => row.employee?.full_name ?? "",
    }),
    createColumn("employee.position", "Position", {
        sortable: false,
        searchable: false,
        render: (_: any, row: Project) => row.employee?.position ?? "",
    }),
    createColumn("employee.department", "Department", {
        sortable: false,
        searchable: false,
        render: (_: any, row: Project) => row.employee?.department ?? "",
    }),
    createColumn("start_date", "Start Date", { sortable: true, searchable: false }),
    createColumn("end_date", "End Date", {
        sortable: true,
        searchable: false,
        render: (val: string | null) => val ?? "Ongoing",
    }),
    createColumn("duration", "Duration", { sortable: false, searchable: false }),
    createColumn("status", "Status", {
        sortable: true,
        searchable: true,
        render: (val: string) =>
            val === "Completed"
                ? `<span class="text-green-600 font-semibold">Completed</span>`
                : `<span class="text-yellow-600 font-semibold">In Progress</span>`,
    }),
    createColumn("outcome", "Outcome", {
        sortable: false,
        searchable: true,
        render: (val: string | null) => val ?? "-",
    }),
    createColumn("created_at", "Created At", { sortable: false, searchable: false }),
    createColumn("updated_at", "Updated At", { sortable: false, searchable: false }),
]);

const actions = computed(() => [
    {
        label: "View",
        icon: Eye,
        variant: "outline" as const,
        tooltip: "View project",
        onClick: (item: Project) =>
            router.visit(route("projects.show", item.id)),
    },
    {
        label: "Edit",
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit project",
        onClick: (item: Project) =>
            router.visit(route("projects.edit", item.id)),
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        label: "Delete",
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete project",
        onClick: (item: Project) => deleteItem(item.id),
        class: "text-red-600 hover:text-red-700",
    },
]);

const bulkActions = computed(() => [
    {
        label: "Delete Selected",
        action: "delete",
        icon: Trash2,
        variant: "destructive" as const,
    },
]);

function deleteItem(id: number) {
    const item = props.projects.data.find((i) => i.id === id);
    if (item) {
        itemToDelete.value = item;
        deleteDialogOpen.value = true;
    }
}

const handleRefresh = () => {
    selectedItems.value = [];
    refresh();
};

const handleClearFilters = () => {
    clearFilters();
    selectedItems.value = [];
};

function confirmDelete() {
    if (!itemToDelete.value) return;
    deleteForm.delete(route("projects.destroy", itemToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteDialogOpen.value = false;
            itemToDelete.value = null;
            refresh();
        },
    });
}

function confirmBulkDelete() {
    if (selectedItemsForBulkDelete.value.length === 0) return;
    router.post(
        route("projects.bulk-destroy"),
        { ids: selectedItemsForBulkDelete.value.map((i) => i.id) },
        {
            preserveScroll: true,
            onSuccess: () => {
                bulkDeleteDialogOpen.value = false;
                selectedItemsForBulkDelete.value = [];
                refresh();
            },
        }
    );
}

const handleBulkAction = (data: {
    action: string;
    selectedRows: Project[];
}) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === "delete") {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Projects Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Projects
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage organization projects and assignments.
                    </p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('projects.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Create Project</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div>
                <ServerDataTable
                    :data="props.projects.data"
                    :columns="columns"
                    :actions="actions"
                    :bulk-actions="bulkActions"
                    :total-records="props.projects.meta.total"
                    :current-page="props.projects.meta.current_page"
                    :loading="loading"
                    :default-rows-per-page="props.projects.meta.per_page"
                    searchable
                    sortable
                    paginator
                    filterable
                    exportable
                    selection-mode="multiple"
                    @search="handleSearch"
                    @sort="handleSort"
                    @page-change="handlePageChange"
                    @filter="handleFilter"
                    @clear-filters="handleClearFilters"
                    @refresh="handleRefresh"
                    @bulk-action="handleBulkAction"
                />
            </div>

            <!-- Dialogs -->
            <div>
                <!-- Single Delete Dialog -->
                <Dialog v-model:open="deleteDialogOpen">
                    <DialogContent class="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle class="flex items-center space-x-2">
                                <Trash2 class="h-5 w-5 text-red-500" />
                                <span>Delete Project</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete this project? This action cannot be undone.
                            </DialogDescription>
                        </DialogHeader>
                        <div v-if="itemToDelete" class="space-y-4">
                            <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                                <div class="flex items-start space-x-2">
                                    <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                                    <div class="text-sm text-red-700 dark:text-red-300">
                                        <strong>{{ itemToDelete.project_name }}</strong><br />
                                        <span class="text-xs">Employee: {{ itemToDelete.employee?.full_name }}</span><br />
                                        <span class="text-xs">Role: {{ itemToDelete.role }}</span><br />
                                        <span class="text-xs">Status: {{ itemToDelete.status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <DialogFooter class="space-x-2">
                            <Button
                                variant="outline"
                                @click="deleteDialogOpen = false"
                                class="cursor-pointer"
                            >Cancel</Button>
                            <Button
                                variant="destructive"
                                @click="confirmDelete"
                                :loading="deleteForm.processing"
                                class="cursor-pointer"
                            >
                                <Trash2 class="mr-2 h-4 w-4" />
                                Delete Project
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>

                <!-- Bulk Delete Dialog -->
                <Dialog v-model:open="bulkDeleteDialogOpen">
                    <DialogContent class="sm:max-w-lg">
                        <DialogHeader>
                            <DialogTitle class="flex items-center space-x-2">
                                <Trash2 class="h-5 w-5 text-red-500" />
                                <span>Delete Multiple Projects</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete
                                {{ selectedItemsForBulkDelete.length }} selected projects? This action cannot be undone.
                            </DialogDescription>
                        </DialogHeader>
                        <div class="space-y-4">
                            <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                                <div class="flex items-start space-x-2">
                                    <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                                    <div class="text-sm text-red-700 dark:text-red-300">
                                        <strong>{{ selectedItemsForBulkDelete.length }} projects</strong>
                                        will be permanently deleted.
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-32 overflow-y-auto text-sm text-gray-600 dark:text-gray-400">
                                <strong>Projects to be deleted:</strong>
                                <ul class="mt-1 space-y-1">
                                    <li
                                        v-for="item in selectedItemsForBulkDelete"
                                        :key="item.id"
                                    >
                                        • {{ item.project_name }} ({{ item.employee?.full_name }}, {{ item.role }})
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <DialogFooter class="space-x-2">
                            <Button
                                variant="outline"
                                @click="bulkDeleteDialogOpen = false"
                                class="cursor-pointer"
                            >Cancel</Button>
                            <Button
                                variant="destructive"
                                @click="confirmBulkDelete"
                                :loading="deleteForm.processing"
                                class="cursor-pointer"
                            >
                                <Trash2 class="mr-2 h-4 w-4" />
                                Delete All ({{ selectedItemsForBulkDelete.length }})
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    </AppLayout>
</template>