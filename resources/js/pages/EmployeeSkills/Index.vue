<script setup lang="ts">
import ServerDataTable from "@/components/DataTable/ServerDataTable.vue";
import { CommonColumns, createColumn } from "@/components/DataTable/renderers";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { useServerDataTable } from "@/composables/useServerDataTable";
import AppLayout from "@/layouts/AppLayout.vue";
import { type ServerPaginatedResponse } from "@/types/datatable";
import { Head, router, useForm } from "@inertiajs/vue3";
import { Edit, Eye, Plus, Trash2 } from "lucide-vue-next";
import { ref } from "vue";
import { route } from "ziggy-js";

interface EmployeeSkillItem {
    id: number;
    employee_id: number;
    skill_id: number;
    proficiency_level: string;
    endorsements_count: number;
    proficiency_badge: string;
    endorsement_status: string;
    employee: {
        id: number;
        full_name: string;
        position: string;
        department: string;
        email: string;
    } | null;
    skill: {
        id: number;
        skill_name: string;
    } | null;
    created_at: string;
    updated_at: string;
}

defineProps<{ employeeSkills: ServerPaginatedResponse<EmployeeSkillItem> }>();

const columns = [
    CommonColumns.id(),
    createColumn("employee", "Employee", {
        sortable: true,
        searchable: true,
        render: (employee) => employee?.full_name ?? "-",
    }),
    createColumn("employee", "Position", {
        sortable: true,
        searchable: true,
        render: (employee) => employee?.position ?? "-",
    }),
    createColumn("employee", "Department", {
        sortable: true,
        searchable: true,
        render: (employee) => employee?.department ?? "-",
    }),
    createColumn("skill", "Skill", {
        sortable: true,
        searchable: true,
        render: (skill) => skill?.skill_name ?? "-",
    }),
createColumn("proficiency_level", "Proficiency", {
    sortable: true,
    searchable: true,
    render: (_: unknown, row: EmployeeSkillItem) =>
        `${row.proficiency_badge ?? ""} ${row.proficiency_level ?? "-"}`,
}),

createColumn("endorsements_count", "Endorsements", {
    sortable: true,
    render: (_: unknown, row: EmployeeSkillItem) =>
        row ? `${row.endorsements_count ?? 0} (${row.endorsement_status ?? "-"})` : "-",
}),
    CommonColumns.createdAt(),
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
    baseRoute: route("employee-skills.index"),
    defaultSort: { field: "created_at", order: "desc" },
    defaultPerPage: 10,
    columns: columns.map((col) => ({
        key: col.key,
        sortable: col.sortable,
        searchable: col.searchable,
    })),
});

const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const itemToDelete = ref<EmployeeSkillItem | null>(null);
const selectedItemsForBulkDelete = ref<EmployeeSkillItem[]>([]);
const deleteForm = useForm({});

const showDeleteDialog = (row: EmployeeSkillItem) => {
    itemToDelete.value = row;
    deleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (!itemToDelete.value) return;
    deleteForm.delete(route("employee-skills.destroy", itemToDelete.value.id), {
        onBefore: () => {
            deleteDialogOpen.value = false;
            itemToDelete.value = null;
        },
        onSuccess: () => refresh(),
        onError: () => {},
    });
};

const showBulkDeleteDialog = (items: EmployeeSkillItem[]) => {
    selectedItemsForBulkDelete.value = items;
    bulkDeleteDialogOpen.value = true;
};

const confirmBulkDelete = () => {
    const ids = selectedItemsForBulkDelete.value.map((item) => item.id);
    router.post(
        route("employee-skills.bulk-destroy"),
        { ids },
        {
            onSuccess: () => {
                refresh();
                bulkDeleteDialogOpen.value = false;
                selectedItemsForBulkDelete.value = [];
            },
            onError: () => {
                bulkDeleteDialogOpen.value = false;
            },
        }
    );
};

const actions = [
    {
        icon: Eye,
        variant: "outline" as const,
        tooltip: "View details",
        onClick: (row: EmployeeSkillItem) => {
            router.visit(route("employee-skills.show", { id: row.id }));
        },
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit",
        onClick: (row: EmployeeSkillItem) => {
            router.visit(route("employee-skills.edit", { id: row.id }));
        },
        class: "text-green-600 hover:text-green-700 border-green-200 hover:border-green-300",
    },
    {
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete",
        onClick: showDeleteDialog,
        class: "text-red-600 hover:text-red-700",
    },
];

const handleBulkAction = (data: {
    action: string;
    selectedRows: EmployeeSkillItem[];
}) => {
    if (data.action === "delete") {
        showBulkDeleteDialog(data.selectedRows);
    }
};

const handleClearFilters = () => clearFilters();
</script>

<template>
    <Head title="Employee Skills" />
    <AppLayout>
        <div class="space-y-6 p-6">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Employee Skills
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage employee skills and proficiency levels
                    </p>
                </div>
                <div class="flex justify-end">
                    <Button
                        @click="router.visit(route('employee-skills.create'))"
                        class="flex w-full cursor-pointer items-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Add Employee Skill</span>
                    </Button>
                </div>
            </div>
            <ServerDataTable
                :data="employeeSkills.data"
                :columns="columns"
                :actions="actions"
                :total-records="employeeSkills.meta.total"
                :loading="loading"
                :current-page="employeeSkills.meta.current_page"
                :default-rows-per-page="employeeSkills.meta.per_page"
                :last-page="employeeSkills.meta.last_page"
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
                @refresh="refresh"
                @bulk-action="handleBulkAction"
            />
        </div>
        <!-- Single Delete Confirmation Dialog -->
        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle class="flex items-center space-x-2">
                        <Trash2 class="h-5 w-5 text-red-500" />
                        <span>Delete Employee Skill</span>
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this employee skill?
                    </DialogDescription>
                </DialogHeader>
                <div v-if="itemToDelete" class="space-y-4">
                    <div
                        class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20"
                    >
                        <div class="flex items-start space-x-2">
                            <Trash2
                                class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400"
                            />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{
                                    itemToDelete.employee?.full_name ??
                                    "Unknown"
                                }}</strong>
                                (<span>{{
                                    itemToDelete.skill?.skill_name ?? "Unknown"
                                }}</span
                                >)
                            </div>
                        </div>
                    </div>
                </div>
                <DialogFooter class="space-x-2">
                    <Button variant="outline" @click="deleteDialogOpen = false"
                        >Cancel</Button
                    >
                    <Button
                        variant="destructive"
                        @click="confirmDelete"
                        :loading="deleteForm.processing"
                    >
                        <Trash2 class="mr-2 h-4 w-4" />Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <!-- Bulk Delete Confirmation Dialog -->
        <Dialog v-model:open="bulkDeleteDialogOpen">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle class="flex items-center space-x-2">
                        <Trash2 class="h-5 w-5 text-red-500" />
                        <span>Delete Multiple Employee Skills</span>
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete
                        {{ selectedItemsForBulkDelete.length }} selected items?
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4">
                    <div
                        class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20"
                    >
                        <div class="flex items-start space-x-2">
                            <Trash2
                                class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400"
                            />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong
                                    >{{
                                        selectedItemsForBulkDelete.length
                                    }}
                                    items</strong
                                >
                                will be deleted.
                            </div>
                        </div>
                    </div>
                    <div class="max-h-32 overflow-y-auto">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Items to be deleted:</strong>
                            <ul class="mt-1 space-y-1">
                                <li
                                    v-for="item in selectedItemsForBulkDelete"
                                    :key="item.id"
                                    class="text-sm"
                                >
                                    •
                                    {{ item.employee?.full_name ?? "Unknown" }}
                                    ({{ item.skill?.skill_name ?? "Unknown" }})
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <DialogFooter class="space-x-2">
                    <Button
                        variant="outline"
                        @click="bulkDeleteDialogOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        variant="destructive"
                        @click="confirmBulkDelete"
                        :loading="deleteForm.processing"
                    >
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete All ({{ selectedItemsForBulkDelete.length }})
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
