<script setup lang="ts">
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import { Edit, Trash2, Plus } from "lucide-vue-next";
import { ref } from "vue";
import { route } from "ziggy-js";
import ServerDataTable from "../../components/DataTable/ServerDataTable.vue";
import {
    CommonColumns,
    createColumn,
} from "../../components/DataTable/renderers";
import { useServerDataTable } from "../../composables/useServerDataTable";
import AppLayout from "../../layouts/AppLayout.vue";
import { type BreadcrumbItem } from "../../types";
import { type ServerPaginatedResponse } from "../../types/datatable";
import { Button } from "../../components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from "../../components/ui/dialog";
import { onMounted, onUnmounted } from "vue";
import { useToast } from "primevue/usetoast";
import { usePage } from "@inertiajs/vue3";

interface Department {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    departments: ServerPaginatedResponse<Department>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Human Resource", href: route("dashboard") },
    { title: "Departments", href: route("departments.index") },
];

// Define table columns
const columns = [
    CommonColumns.id(),
    createColumn("name", "Name", {
        sortable: true,
        searchable: true,
        class: "font-semibold",
    }),
    createColumn("description", "Description", {
        sortable: false,
        searchable: true,
        render: (value) =>
            value?.length > 80 ? value.slice(0, 80) + "..." : value ?? "-",
        class: "text-gray-600 dark:text-gray-400",
    }),
    CommonColumns.createdAt(),
];

// Server datatable hooks
const {
    handleSearch,
    handleSort,
    handlePageChange,
    handleFilter,
    clearFilters,
    refresh,
    loading,
} = useServerDataTable({
    baseRoute: route("departments.index"),
    defaultSort: { field: "name", order: "asc" },
    defaultPerPage: 10,
    columns: columns.map((col) => ({
        key: col.key,
        sortable: col.sortable,
        searchable: col.searchable,
    })),
});

// Delete dialog (single)
const deleteDialogOpen = ref(false);
const itemToDelete = ref<Department | null>(null);
const deleteForm = useForm({});

const showDeleteDialog = (row: Department) => {
    itemToDelete.value = row;
    deleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (!itemToDelete.value) return;

    deleteForm.delete(route("departments.destroy", itemToDelete.value.id), {
        onBefore: () => {
            deleteDialogOpen.value = false;
            itemToDelete.value = null;
        },
        onSuccess: () => refresh(),
    });
};

// Bulk delete dialog
const bulkDeleteDialogOpen = ref(false);
const selectedItems = ref<Department[]>([]);

const handleBulkAction = ({ action, selectedRows }) => {
    if (action === "delete") {
        selectedItems.value = selectedRows;
        bulkDeleteDialogOpen.value = true;
    }
};

const confirmBulkDelete = () => {
    const ids = selectedItems.value.map((item) => item.id);

    router.post(
        route("departments.bulk-destroy"),
        {
            ids,
        },
        {
            onSuccess: () => {
                bulkDeleteDialogOpen.value = false;
                selectedItems.value = [];
                refresh();
            },
        }
    );
};

// Row-level actions
const actions = [
    {
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit department",
        onClick: (row: Department) => {
            router.visit(route("departments.edit", row.id));
        },
        class: "text-green-600 hover:text-green-700 border-green-200 hover:border-green-300",
    },
    {
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete department",
        onClick: showDeleteDialog,
        class: "text-red-600 hover:text-red-700",
    },
];

// Bulk action menu
const bulkActions = [
    {
        label: "Delete Selected",
        action: "delete",
        icon: Trash2,
    },
];

// ...other imports...

const page = usePage();

</script>

<template>
    <Head title="Departments" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Departments
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage departments in your organization.
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <Button
                        @click="router.visit(route('departments.create'))"
                        class="flex items-center space-x-2"
                    >
                        <Plus class="w-4 h-4" />
                        <span>Add Department</span>
                    </Button>
                </div>
            </div>

            <!-- Data Table -->
            <ServerDataTable
                :data="departments.data"
                :columns="columns"
                :actions="actions"
                :total-records="departments.total"
                :loading="loading"
                :current-page="departments.current_page"
                :default-rows-per-page="10"
                searchable
                sortable
                paginator
                filterable
                exportable
                selection-mode="multiple"
                :bulk-actions="bulkActions"
                @search="handleSearch"
                @sort="handleSort"
                @page-change="handlePageChange"
                @filter="handleFilter"
                @clear-filters="clearFilters"
                @refresh="refresh"
                @bulk-action="handleBulkAction"
            />

            <!-- Single Delete Dialog -->
            <Dialog v-model:open="deleteDialogOpen">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle class="flex items-center space-x-2">
                            <Trash2 class="w-5 h-5 text-red-500" />
                            <span>Delete Department</span>
                        </DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete this department? It
                            will be moved to trash.
                        </DialogDescription>
                    </DialogHeader>
                    <div v-if="itemToDelete" class="space-y-4">
                        <div
                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3"
                        >
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ itemToDelete.name }}</strong> will be
                                moved to trash.
                            </div>
                        </div>
                    </div>
                    <DialogFooter class="space-x-2">
                        <Button
                            variant="outline"
                            @click="deleteDialogOpen = false"
                            >Cancel</Button
                        >
                        <Button
                            variant="destructive"
                            @click="confirmDelete"
                            :loading="deleteForm.processing"
                        >
                            <Trash2 class="w-4 h-4 mr-2" />
                            Delete
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Bulk Delete Dialog -->
            <Dialog v-model:open="bulkDeleteDialogOpen">
                <DialogContent class="sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle class="flex items-center space-x-2">
                            <Trash2 class="w-5 h-5 text-red-500" />
                            <span>Delete Selected Departments</span>
                        </DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete
                            {{ selectedItems.length }} selected departments?
                        </DialogDescription>
                    </DialogHeader>
                    <div class="max-h-48 overflow-y-auto mt-2">
                        <ul
                            class="text-sm text-gray-600 dark:text-gray-400 list-disc pl-5"
                        >
                            <li v-for="item in selectedItems" :key="item.id">
                                {{ item.name }}
                            </li>
                        </ul>
                    </div>
                    <DialogFooter class="space-x-2">
                        <Button
                            variant="outline"
                            @click="bulkDeleteDialogOpen = false"
                            >Cancel</Button
                        >
                        <Button
                            variant="destructive"
                            @click="confirmBulkDelete"
                            :loading="deleteForm.processing"
                        >
                            <Trash2 class="w-4 h-4 mr-2" />
                            Delete All ({{ selectedItems.length }})
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
