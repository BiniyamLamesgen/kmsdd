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

interface Education {
    id: number;
    employee_id: number;
    employee?: Employee;
    field_of_study: string;
    institution: string;
    graduation_year: number;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    education: {
        data: any[];
        meta?: {
            per_page?: number | number[];
            current_page?: number | number[];
            total_pages?: number;
            count?: number | number[];
            total?: number | number[];
        };
        links?: {
            first?: string | string[];
            last?: string | string[];
            prev?: string | null | (string | null)[];
            next?: string | null | (string | null)[];
        };
        // allow legacy fields at top-level (some responses may include them)
        per_page?: number | number[];
        current_page?: number | number[];
        total?: number | number[];
    };
}>();

// Normalized view model so template always gets consistent values

// Props type for the component (extracted for clarity)
interface EducationsProps {
    education: EducationResponseRaw;
}

// Types for server data table handlers returned by useServerDataTable
interface ServerDataTableHandlers {
    handleSearch: (query: string) => void;
    handleSort: (sort: { field: string; order?: 'asc' | 'desc' | string }) => void;
    handlePageChange: (page: number) => void;
    handleFilter: (filters: Record<string, any>) => void;
    clearFilters: () => void;
    refresh: () => void;
    loading: import('vue').Ref<boolean>;
}

// Bulk action payload
interface BulkActionPayload {
    action: string;
    selectedRows: Education[];
}

interface PaginationMetaRaw {
    per_page?: number | number[];
    current_page?: number | number[];
    total_pages?: number;
    count?: number | number[];
    total?: number | number[];
}

interface PaginationLinksRaw {
    first?: string | string[];
    last?: string | string[];
    prev?: string | null | (string | null)[];
    next?: string | null | (string | null)[];
}

interface EducationResponseRaw {
    data: any[] | { data?: any[] };
    meta?: PaginationMetaRaw;
    links?: PaginationLinksRaw;
    per_page?: number | number[];
    current_page?: number | number[];
    total?: number | number[];
}

interface NormalizedPaginationMeta {
    per_page: number;
    current_page: number;
    total: number;
}

interface NormalizedEducationResponse {
    data: Education[];
    meta: NormalizedPaginationMeta;
    links: PaginationLinksRaw | Record<string, unknown>;
}

type Nullable<T> = T | null;
const educationNormalized = computed(() => {
    const ed = props.education ?? { data: [], meta: {} };
    // support both direct "data" array or nested/odd shapes
    const data = Array.isArray(ed.data) ? ed.data : (ed.data?.data ?? []);
    const metaSrc = ed.meta ?? {};
    const pick = (v: any) => (Array.isArray(v) ? v[0] : v);
    const per_page = Number(pick(metaSrc.per_page ?? ed.per_page)) || data.length;
    const current_page = Number(pick(metaSrc.current_page ?? ed.current_page)) || 1;
    const total = Number(pick(metaSrc.total ?? ed.total ?? metaSrc.count)) || data.length;
    const links = ed.links ?? {};
    return {
        data,
        meta: {
            per_page,
            current_page,
            total,
        },
        links,
    };
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Education", href: route("educations.index") },
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
    baseRoute: route("educations.index"),
    defaultSort: { field: "graduation_year", order: "desc" },
    defaultPerPage: 15,
});

const selectedItems = ref<Education[]>([]);
const itemToDelete = ref<Education | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Education[]>([]);
const deleteForm = useForm({});

const columns = computed(() => [
      createColumn("employee", "Employee", {
        sortable: false,
        searchable: false,
        render: (employee: Employee | undefined) =>
            employee ? `${employee.first_name} ${employee.last_name}` : "",
    }),
    createColumn("field_of_study", "Field of Study", { sortable: true, searchable: true }),
    createColumn("institution", "Institution", { sortable: true, searchable: true }),
    createColumn("graduation_year", "Graduation Year", { sortable: true, searchable: true }),
  
]);

const actions = computed(() => [
    {
        label: "View",
        icon: Eye,
        variant: "outline" as const,
        tooltip: "View education",
        onClick: (item: Education) =>
            router.visit(route("educations.show", item.id)),
    },
    {
        label: "Edit",
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit education",
        onClick: (item: Education) =>
            router.visit(route("educations.edit", item.id)),
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        label: "Delete",
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete education",
        onClick: (item: Education) => deleteItem(item.id),
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
    const item = educationNormalized.value.data.find((i) => i.id === id);
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
    deleteForm.delete(route("educations.destroy", itemToDelete.value.id), {
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
        route("educations.bulk-destroy"),
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
    selectedRows: Education[];
}) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === "delete") {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Education Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Education
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage education records and their details.
                    </p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('educations.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Create Education</span>
                    </Button>
                </div>
            </div>
            <!-- <pre>{{ educationNormalized }}</pre>  -->
            <div>
                <ServerDataTable
                    :data="educationNormalized.data"
                    :columns="columns"
                    :actions="actions"
                    :bulk-actions="bulkActions"
                    :total-records="educationNormalized.meta.total"
                    :current-page="educationNormalized.meta.current_page"
                    :loading="loading"
                    :default-rows-per-page="educationNormalized.meta.per_page"
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
                                <span>Delete Education</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete this education record? This action cannot be undone.
                            </DialogDescription>
                        </DialogHeader>
                        <div v-if="itemToDelete" class="space-y-4">
                            <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                                <div class="flex items-start space-x-2">
                                    <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                                    <div class="text-sm text-red-700 dark:text-red-300">
                                        <strong>{{ itemToDelete.institution }}</strong><br />
                                        <span class="text-xs">Employee: {{ itemToDelete.employee ? itemToDelete.employee.first_name + " " + itemToDelete.employee.last_name : "" }}</span><br />
                                        <span class="text-xs">Year: {{ itemToDelete.graduation_year }}</span><br />
                                        <span class="text-xs">Field: {{ itemToDelete.field_of_study }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <DialogFooter class="space-x-2">
                            <Button variant="outline" @click="deleteDialogOpen = false" class="cursor-pointer">Cancel</Button>
                            <Button variant="destructive" @click="confirmDelete" :loading="deleteForm.processing" class="cursor-pointer">
                                <Trash2 class="mr-2 h-4 w-4" />
                                Delete Education
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
                                <span>Delete Multiple Education Records</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete {{ selectedItemsForBulkDelete.length }} selected education records? This action cannot be undone.
                            </DialogDescription>
                        </DialogHeader>
                        <div class="space-y-4">
                            <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                                <div class="flex items-start space-x-2">
                                    <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                                    <div class="text-sm text-red-700 dark:text-red-300">
                                        <strong>{{ selectedItemsForBulkDelete.length }} education records</strong> will be permanently deleted.
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-32 overflow-y-auto text-sm text-gray-600 dark:text-gray-400">
                                <strong>Records to be deleted:</strong>
                                <ul class="mt-1 space-y-1">
                                    <li v-for="item in selectedItemsForBulkDelete" :key="item.id">
                                        • {{ item.institution }} (Employee: {{ item.employee ? item.employee.first_name + " " + item.employee.last_name : "" }}, Year: {{ item.graduation_year }})
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <DialogFooter class="space-x-2">
                            <Button variant="outline" @click="bulkDeleteDialogOpen = false" class="cursor-pointer">Cancel</Button>
                            <Button variant="destructive" @click="confirmBulkDelete" :loading="deleteForm.processing" class="cursor-pointer">
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
