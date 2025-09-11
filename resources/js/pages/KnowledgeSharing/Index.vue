<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { Edit, Plus, Trash2, Eye, ExternalLink } from "lucide-vue-next";
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

interface KnowledgeSharing {
    id: number;
    employee_id: number;
    document_title: string;
    document_type: string;
    link: string | null;
    date_shared: string;
    formatted_date: string;
    time_since_shared: string;
    document_category: string;
    has_link: boolean;
    sharing_year: number;
    employee: Employee;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    knowledge_sharing: {
        data: KnowledgeSharing[];
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
    { title: "Knowledge Sharing", href: route("knowledge-management.index") },
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
    baseRoute: route("knowledge-management.index"),
    defaultSort: { field: "date_shared", order: "desc" },
    defaultPerPage: 10,
});

const selectedItems = ref<KnowledgeSharing[]>([]);
const itemToDelete = ref<KnowledgeSharing | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<KnowledgeSharing[]>([]);
const deleteForm = useForm({});

const columns = computed(() => [
    createColumn("id", "ID", { sortable: true, searchable: false }),
    createColumn("document_title", "Document Title", {
        sortable: true,
        searchable: true,
    }),
    createColumn("document_type", "Type", { sortable: true, searchable: true }),
    createColumn("document_category", "Category", {
        sortable: false,
        searchable: false,
    }),
    createColumn("employee.full_name", "Employee", {
        sortable: true,
        searchable: true,
        render: (_: any, row: KnowledgeSharing) =>
            row.employee?.full_name ?? "",
    }),
    createColumn("employee.position", "Position", {
        sortable: false,
        searchable: false,
        render: (_: any, row: KnowledgeSharing) => row.employee?.position ?? "",
    }),
    createColumn("employee.department", "Department", {
        sortable: false,
        searchable: false,
        render: (_: any, row: KnowledgeSharing) =>
            row.employee?.department ?? "",
    }),
    createColumn("link", "Link", {
        sortable: false,
        searchable: true,
        render: (val: string | null, row: KnowledgeSharing) =>
            val
                ? `<a href="${val}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                     <span>View</span>
                     <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                     </svg>
                   </a>`
                : "<span class='text-gray-400'>No link</span>",
    }),
    createColumn("formatted_date", "Date Shared", {
        sortable: true,
        searchable: false,
    }),
    createColumn("time_since_shared", "Time Since", {
        sortable: false,
        searchable: false,
    }),
    createColumn("sharing_year", "Year", { sortable: true, searchable: false }),
    createColumn("created_at", "Created At", {
        sortable: false,
        searchable: false,
    }),
    createColumn("updated_at", "Updated At", {
        sortable: false,
        searchable: false,
    }),
]);

const actions = computed(() => [
    {
        label: "View",
        icon: Eye,
        variant: "outline" as const,
        tooltip: "View knowledge sharing",
        onClick: (item: KnowledgeSharing) =>
            router.visit(route("knowledge-management.show", item.id)),
    },
    {
        label: "Edit",
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit knowledge sharing",
        onClick: (item: KnowledgeSharing) =>
            router.visit(route("knowledge-management.edit", item.id)),
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        label: "Delete",
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete knowledge sharing",
        onClick: (item: KnowledgeSharing) => deleteItem(item.id),
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
    const item = props.knowledge_sharing.data.find((i) => i.id === id);
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
    deleteForm.delete(
        route("knowledge-management.destroy", itemToDelete.value.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                deleteDialogOpen.value = false;
                itemToDelete.value = null;
                refresh();
            },
        }
    );
}

function confirmBulkDelete() {
    if (selectedItemsForBulkDelete.value.length === 0) return;
    router.post(
        route("knowledge-management.bulk-destroy"),
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
    selectedRows: KnowledgeSharing[];
}) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === "delete") {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Knowledge Sharing Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Knowledge Sharing
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage organization knowledge sharing and documentation.
                    </p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="
                            router.visit(route('knowledge-management.create'))
                        "
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Share Knowledge</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div>
                <ServerDataTable
                    :data="props.knowledge_sharing.data"
                    :columns="columns"
                    :actions="actions"
                    :bulk-actions="bulkActions"
                    :total-records="props.knowledge_sharing.meta.total"
                    :current-page="props.knowledge_sharing.meta.current_page"
                    :loading="loading"
                    :default-rows-per-page="
                        props.knowledge_sharing.meta.per_page
                    "
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
                                <span>Delete Knowledge Sharing</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete this knowledge
                                sharing item? This action cannot be undone.
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
                                            itemToDelete.document_title
                                        }}</strong
                                        ><br />
                                        <span class="text-xs"
                                            >Employee:
                                            {{
                                                itemToDelete.employee?.full_name
                                            }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Type:
                                            {{
                                                itemToDelete.document_type
                                            }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Date:
                                            {{
                                                itemToDelete.formatted_date
                                            }}</span
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
                                Delete Item
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
                                <span
                                    >Delete Multiple Knowledge Sharing
                                    Items</span
                                >
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete
                                {{ selectedItemsForBulkDelete.length }} selected
                                knowledge sharing items? This action cannot be
                                undone.
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
                                            knowledge sharing items</strong
                                        >
                                        will be permanently deleted.
                                    </div>
                                </div>
                            </div>
                            <div
                                class="max-h-32 overflow-y-auto text-sm text-gray-600 dark:text-gray-400"
                            >
                                <strong>Items to be deleted:</strong>
                                <ul class="mt-1 space-y-1">
                                    <li
                                        v-for="item in selectedItemsForBulkDelete"
                                        :key="item.id"
                                    >
                                        • {{ item.document_title }} ({{
                                            item.employee?.full_name
                                        }}, {{ item.document_type }})
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
