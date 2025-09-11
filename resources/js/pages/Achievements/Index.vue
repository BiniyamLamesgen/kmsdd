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
    first_name: string;
    last_name: string;
    position?: string;
    department?: string;
}

interface Achievement {
    id: number;
    employee_id: number;
    employee?: Employee;
    title: string;
    description: string;
    date_awarded: string;
    formatted_date: string;
    time_since_awarded: string;
    achievement_year: number;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    achievements: {
        data: Achievement[];
        meta: {
            total: number;
            count: number;
            per_page: number;
            current_page: number;
            total_pages: number;
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
    { title: "Achievements", href: route("achievements.index") },
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
    baseRoute: route("achievements.index"),
    defaultSort: { field: "date_awarded", order: "desc" },
    defaultPerPage: 15,
});

const selectedItems = ref<Achievement[]>([]);
const itemToDelete = ref<Achievement | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Achievement[]>([]);
const deleteForm = useForm({});

const columns = computed(() => [
    createColumn("title", "Title", { sortable: true, searchable: true }),
    createColumn("description", "Description", {
        sortable: false,
        searchable: true,
    }),
    createColumn("employee", "Employee", {
        sortable: false,
        searchable: false,
        render: (employee: Employee | undefined) =>
            employee ? `${employee.first_name} ${employee.last_name}` : "",
    }),
    createColumn("achievement_year", "Year", {
        sortable: true,
        searchable: false,
    }),
    createColumn("formatted_date", "Date Awarded", {
        sortable: true,
        searchable: false,
    }),
    createColumn("time_since_awarded", "Time Since Awarded", {
        sortable: false,
        searchable: false,
    }),
]);

const actions = computed(() => [
    {
        label: "View",
        icon: Eye,
        variant: "outline" as const,
        tooltip: "View achievement",
        onClick: (item: Achievement) =>
            router.visit(route("achievements.show", item.id)),
    },
    {
        label: "Edit",
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit achievement",
        onClick: (item: Achievement) =>
            router.visit(route("achievements.edit", item.id)),
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        label: "Delete",
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete achievement",
        onClick: (item: Achievement) => deleteItem(item.id),
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
    const item = props.achievements.data.find((i) => i.id === id);
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
    deleteForm.delete(route("achievements.destroy", itemToDelete.value.id), {
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
        route("achievements.bulk-destroy"),
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
    selectedRows: Achievement[];
}) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === "delete") {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Achievements Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header (Responsive) -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Achievements
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage organization achievements and their details.
                    </p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('achievements.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Create Achievement</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div>
                <ServerDataTable
                    :data="props.achievements.data"
                    :columns="columns"
                    :actions="actions"
                    :bulk-actions="bulkActions"
                    :total-records="props.achievements.meta.count"
                    :current-page="props.achievements.meta.current_page"
                    :loading="loading"
                    :default-rows-per-page="props.achievements.meta.per_page"
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
                                <span>Delete Achievement</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete this
                                achievement? This action cannot be undone.
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
                                    <div
                                        class="text-sm text-red-700 dark:text-red-300"
                                    >
                                        <strong>{{ itemToDelete.title }}</strong
                                        ><br />
                                        <span class="text-xs"
                                            >Employee:
                                            {{
                                                itemToDelete.employee
                                                    ? itemToDelete.employee
                                                          .first_name +
                                                      " " +
                                                      itemToDelete.employee
                                                          .last_name
                                                    : ""
                                            }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Date:
                                            {{
                                                itemToDelete.formatted_date
                                            }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Description:
                                            {{ itemToDelete.description }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <DialogFooter class="space-x-2">
                            <Button
                                variant="outline"
                                @click="deleteDialogOpen = false"
                                class="cursor-pointer"
                                >Cancel</Button
                            >
                            <Button
                                variant="destructive"
                                @click="confirmDelete"
                                :loading="deleteForm.processing"
                                class="cursor-pointer"
                            >
                                <Trash2 class="mr-2 h-4 w-4" />
                                Delete Achievement
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
                                <span>Delete Multiple Achievements</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete
                                {{ selectedItemsForBulkDelete.length }} selected
                                achievements? This action cannot be undone.
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
                                    <div
                                        class="text-sm text-red-700 dark:text-red-300"
                                    >
                                        <strong
                                            >{{
                                                selectedItemsForBulkDelete.length
                                            }}
                                            achievements</strong
                                        >
                                        will be permanently deleted.
                                    </div>
                                </div>
                            </div>
                            <div
                                class="max-h-32 overflow-y-auto text-sm text-gray-600 dark:text-gray-400"
                            >
                                <strong>Achievements to be deleted:</strong>
                                <ul class="mt-1 space-y-1">
                                    <li
                                        v-for="item in selectedItemsForBulkDelete"
                                        :key="item.id"
                                    >
                                        • {{ item.title }} (Employee:
                                        {{
                                            item.employee
                                                ? item.employee.first_name +
                                                  " " +
                                                  item.employee.last_name
                                                : ""
                                        }}, Date: {{ item.formatted_date }})
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <DialogFooter class="space-x-2">
                            <Button
                                variant="outline"
                                @click="bulkDeleteDialogOpen = false"
                                class="cursor-pointer"
                                >Cancel</Button
                            >
                            <Button
                                variant="destructive"
                                @click="confirmBulkDelete"
                                :loading="deleteForm.processing"
                                class="cursor-pointer"
                            >
                                <Trash2 class="mr-2 h-4 w-4" />
                                Delete All ({{
                                    selectedItemsForBulkDelete.length
                                }})
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    </AppLayout>
</template>
