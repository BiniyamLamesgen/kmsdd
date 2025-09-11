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
import { renderImage } from "../../components/DataTable/renderers";

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
    social_links: {
        linkedin: string | null;
        facebook: string | null;
        twitter: string | null;
        github: string | null;
        personal_website: string | null;
    };
    languages: string;
    mentoring_interest: string;
    availability_for_sharing: boolean;
    experiences: any[];
    projects: any[];
    skills: any[];
    achievements: any[];
    certifications: any[];
    education: any[];
    knowledge_sharing: any[];
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    employees: {
        data: Employee[];
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
    { title: "Employees", href: route("employees.index") },
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
    baseRoute: route("employees.index"),
    defaultSort: { field: "first_name", order: "asc" },
    defaultPerPage: 15,
});

const selectedItems = ref<Employee[]>([]);
const itemToDelete = ref<Employee | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Employee[]>([]);
const deleteForm = useForm({});

const columns = computed(() => [
    createColumn("id", "ID", { sortable: true, searchable: false }),
    createColumn("first_name", "First Name", {
        sortable: true,
        searchable: true,
    }),
    createColumn("last_name", "Last Name", {
        sortable: true,
        searchable: true,
    }),
    createColumn("full_name", "Full Name", {
        sortable: true,
        searchable: true,
    }),
    createColumn("position", "Position", { sortable: true, searchable: true }),
    createColumn("department", "Department", {
        sortable: true,
        searchable: true,
    }),
    createColumn("email", "Email", { sortable: false, searchable: true }),
    createColumn("phone", "Phone", { sortable: false, searchable: true }),
    createColumn("internal_extension", "Extension", {
        sortable: false,
        searchable: false,
    }),
    createColumn("profile_picture", "Profile Picture", {
        sortable: false,
        searchable: false,
        render: (val: string | null) => {
            if (!val) return "";
            // If it's already a full URL, use it as-is
            const url = val.startsWith("http") ? val : `/storage/${val}`;
            return renderImage(url, "Profile", 32);
        },
    }),
    createColumn("date_joined", "Date Joined", {
        sortable: true,
        searchable: false,
    }),
    createColumn("languages", "Languages", {
        sortable: false,
        searchable: true,
    }),
    createColumn("mentoring_interest", "Mentoring Interest", {
        sortable: false,
        searchable: true,
    }),
    createColumn("availability_for_sharing", "Available for Sharing", {
        sortable: false,
        searchable: false,
        render: (val: boolean) => (val ? "Yes" : "No"),
    }),
    createColumn("created_at", "Created At", {
        sortable: false,
        searchable: false,
    }),
]);

const actions = computed(() => [
    {
        label: "View",
        icon: Eye,
        variant: "outline" as const,
        tooltip: "View employee",
        onClick: (item: Employee) =>
            router.visit(route("employees.show", item.id)),
    },
    {
        label: "Edit",
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit employee",
        onClick: (item: Employee) =>
            router.visit(route("employees.edit", item.id)),
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        label: "Delete",
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete employee",
        onClick: (item: Employee) => deleteItem(item.id),
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
    const item = props.employees.data.find((i) => i.id === id);
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
    deleteForm.delete(route("employees.destroy", itemToDelete.value.id), {
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
        route("employees.bulk-destroy"),
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
    selectedRows: Employee[];
}) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === "delete") {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Employees Management" />
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
                        Employees
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage organization employees and their details.
                    </p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('employees.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Create Employee</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div>
                <ServerDataTable
                    :data="props.employees.data"
                    :columns="columns"
                    :actions="actions"
                    :bulk-actions="bulkActions"
                    :total-records="props.employees.meta.total"
                    :current-page="props.employees.meta.current_page"
                    :loading="loading"
                    :default-rows-per-page="props.employees.meta.per_page"
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
                                <span>Delete Employee</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete this employee?
                                This action cannot be undone.
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
                                        <strong>{{
                                            itemToDelete.full_name
                                        }}</strong
                                        ><br />
                                        <span class="text-xs"
                                            >Position:
                                            {{ itemToDelete.position }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Department:
                                            {{ itemToDelete.department }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Email:
                                            {{ itemToDelete.email }}</span
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
                                Delete Employee
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
                                <span>Delete Multiple Employees</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete
                                {{ selectedItemsForBulkDelete.length }} selected
                                employees? This action cannot be undone.
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
                                            employees</strong
                                        >
                                        will be permanently deleted.
                                    </div>
                                </div>
                            </div>
                            <div
                                class="max-h-32 overflow-y-auto text-sm text-gray-600 dark:text-gray-400"
                            >
                                <strong>Employees to be deleted:</strong>
                                <ul class="mt-1 space-y-1">
                                    <li
                                        v-for="item in selectedItemsForBulkDelete"
                                        :key="item.id"
                                    >
                                        • {{ item.full_name }} ({{
                                            item.position
                                        }}, {{ item.department }})
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
