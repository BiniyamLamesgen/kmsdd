<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { Edit, Plus, Trash2, Eye, Image } from "lucide-vue-next";
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
    gallery: {
        data: Gallery[];
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
    { title: "Gallery", href: route("gallery.index") },
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
    baseRoute: route("gallery.index"),
    defaultSort: { field: "date", order: "desc" },
    defaultPerPage: 15,
});

const selectedItems = ref<Gallery[]>([]);
const itemToDelete = ref<Gallery | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Gallery[]>([]);
const deleteForm = useForm({});

const columns = computed(() => [
    createColumn("id", "ID", { sortable: true, searchable: false }),
    createColumn("image", "Image", {
        sortable: false,
        searchable: false,
        render: (val: string) => {
            if (!val) return "";
            const url = val.startsWith("http") ? val : `/storage/${val}`;
            return renderImage(url, "Gallery Image", 48);
        },
    }),
    createColumn("title", "Title", { sortable: true, searchable: true }),
    createColumn("alt", "Alt Text", { sortable: false, searchable: true }),
    createColumn("description", "Description", {
        sortable: false,
        searchable: true,
    }),
    createColumn("category", "Category", {
        sortable: true,
        searchable: true,
    }),
    createColumn("employee", "Employee", {
        sortable: true,
        searchable: true,
    }),
    createColumn("date", "Date", { sortable: true, searchable: false }),
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
        tooltip: "View image",
        onClick: (item: Gallery) =>
            router.visit(route("gallery.show", item.id)),
    },
    {
        label: "Edit",
        icon: Edit,
        variant: "outline" as const,
        tooltip: "Edit image",
        onClick: (item: Gallery) =>
            router.visit(route("gallery.edit", item.id)),
        class: "text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300",
    },
    {
        label: "Delete",
        icon: Trash2,
        variant: "destructive" as const,
        tooltip: "Delete image",
        onClick: (item: Gallery) => deleteItem(item.id),
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
    const item = props.gallery.data.find((i) => i.id === id);
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
    deleteForm.delete(route("gallery.destroy", itemToDelete.value.id), {
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
        route("gallery.bulk-destroy"),
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
    selectedRows: Gallery[];
}) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === "delete") {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Gallery Management" />
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
                        Gallery
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage gallery images and details.
                    </p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('gallery.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Add Gallery</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div>
                <ServerDataTable
                    :data="props.gallery.data"
                    :columns="columns"
                    :actions="actions"
                    :bulk-actions="bulkActions"
                    :total-records="props.gallery.meta.total"
                    :current-page="props.gallery.meta.current_page"
                    :loading="loading"
                    :default-rows-per-page="props.gallery.meta.per_page"
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
                                <span>Delete Image</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete this image?
                                This action cannot be undone.
                            </DialogDescription>
                        </DialogHeader>
                        <div v-if="itemToDelete" class="space-y-4">
                            <div
                                class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20"
                            >
                                <div class="flex items-start space-x-2">
                                    <Image
                                        class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400"
                                    />
                                    <div
                                        class="text-sm text-red-700 dark:text-red-300"
                                    >
                                        <strong>{{
                                            itemToDelete.title
                                        }}</strong
                                        ><br />
                                        <span class="text-xs"
                                            >Category:
                                            {{ itemToDelete.category }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Employee:
                                            {{ itemToDelete.employee }}</span
                                        ><br />
                                        <span class="text-xs"
                                            >Date:
                                            {{ itemToDelete.date }}</span
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
                                Delete Image
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
                                <span>Delete Multiple Images</span>
                            </DialogTitle>
                            <DialogDescription>
                                Are you sure you want to delete
                                {{ selectedItemsForBulkDelete.length }} selected
                                images? This action cannot be undone.
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
                                            images</strong
                                        >
                                        will be permanently deleted.
                                    </div>
                                </div>
                            </div>
                            <div
                                class="max-h-32 overflow-y-auto text-sm text-gray-600 dark:text-gray-400"
                            >
                                <strong>Images to be deleted:</strong>
                                <ul class="mt-1 space-y-1">
                                    <li
                                        v-for="item in selectedItemsForBulkDelete"
                                        :key="item.id"
                                    >
                                        • {{ item.title }} ({{
                                            item.category
                                        }}, {{ item.employee }})
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
