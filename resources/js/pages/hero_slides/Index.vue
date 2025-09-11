<script setup lang="ts">
import ServerDataTable from '@/components/DataTable/ServerDataTable.vue';
import { CommonColumns, createColumn, renderImage } from '@/components/DataTable/renderers';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useServerDataTable } from '@/composables/useServerDataTable';
import AppLayout from '@/layouts/AppLayout.vue';
import { type ServerPaginatedResponse } from '@/types/datatable';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { route } from 'ziggy-js';

interface HeroSlide {
    id: number;
    title: string;
    description?: string;
    image?: string;
    order?: number;
    created_at: string;
    updated_at: string;
}

defineProps<{ heroSlides: ServerPaginatedResponse<HeroSlide> }>();

const columns = [
    CommonColumns.id(),
    createColumn('title', 'Title', { sortable: true, searchable: true, class: 'font-semibold' }),
    createColumn('description', 'Description', { sortable: true, searchable: true, class: 'text-gray-600 dark:text-gray-400' }),
    createColumn('order', 'Order', { sortable: true, searchable: false, class: 'text-center', headerClass: 'text-center' }),
    createColumn('image', 'Image', {
        sortable: false,
        searchable: false,
        render: (value) => renderImage(value, 'hero slide image', 40),
        class: 'text-center',
        headerClass: 'text-center',
    }),
    CommonColumns.createdAt(),
];

const { handleSearch, handleSort, handlePageChange, handleFilter, clearFilters, refresh, loading } = useServerDataTable({
    baseRoute: route('hero-slides.index'),
    defaultSort: { field: 'title', order: 'asc' },
    defaultPerPage: 8,
    columns: columns.map((col) => ({ key: col.key, sortable: col.sortable, searchable: col.searchable })),
});

const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const itemToDelete = ref<HeroSlide | null>(null);
const selectedItemsForBulkDelete = ref<HeroSlide[]>([]);
const deleteForm = useForm({});

const showDeleteDialog = (row: HeroSlide) => {
    itemToDelete.value = row;
    deleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (!itemToDelete.value) return;
    deleteForm.delete(route('hero-slides.destroy', itemToDelete.value.id), {
        onBefore: () => {
            deleteDialogOpen.value = false;
            itemToDelete.value = null;
        },
        onSuccess: () => refresh(),
        onError: () => {},
    });
};

const showBulkDeleteDialog = (items: HeroSlide[]) => {
    selectedItemsForBulkDelete.value = items;
    bulkDeleteDialogOpen.value = true;
};

const confirmBulkDelete = () => {
    const ids = selectedItemsForBulkDelete.value.map((item) => item.id);
    router.delete(route('hero-slides.bulk-destroy'), {
        data: { ids },
        onSuccess: () => {
            refresh();
            bulkDeleteDialogOpen.value = false;
            selectedItemsForBulkDelete.value = [];
        },
        onError: () => {
            bulkDeleteDialogOpen.value = false;
        },
    });
};

const actions = [
    {
        icon: Edit,
        variant: 'outline' as const,
        tooltip: 'Edit hero slide',
        onClick: (row: HeroSlide) => {
            router.visit(route('hero-slides.edit', { heroSlide: row.id }));
        },
        class: 'text-green-600 hover:text-green-700 border-green-200 hover:border-green-300',
    },
    {
        icon: Trash2,
        variant: 'destructive' as const,
        tooltip: 'Delete hero slide',
        onClick: showDeleteDialog,
        class: 'text-red-600 hover:text-red-700',
    },
];

const handleBulkAction = (data: { action: string; selectedRows: HeroSlide[] }) => {
    if (data.action === 'delete') {
        showBulkDeleteDialog(data.selectedRows);
    }
};

const handleClearFilters = () => clearFilters();
</script>
<template>
    <Head title="Hero Slides" />
    <AppLayout>
        <div class="space-y-6 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Hero Slides</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage hero slides for your homepage</p>
                </div>
                <Button
                    @click="router.visit(route('hero-slides.create'))"
                    class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                >
                    <Plus class="h-4 w-4" />
                    <span>Create Hero Slide</span>
                </Button>
            </div>
            <ServerDataTable
                :data="heroSlides.data"
                :columns="columns"
                :actions="actions"
                :total-records="heroSlides.total"
                :loading="loading"
                :current-page="heroSlides.current_page"
                :default-rows-per-page="8"
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
                        <span>Delete Hero Slide</span>
                    </DialogTitle>
                    <DialogDescription> Are you sure you want to delete this hero slide? </DialogDescription>
                </DialogHeader>
                <div v-if="itemToDelete" class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                        <div class="flex items-start space-x-2">
                            <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ itemToDelete.title }}</strong> will be deleted.
                            </div>
                        </div>
                    </div>
                </div>
                <DialogFooter class="space-x-2">
                    <Button variant="outline" @click="deleteDialogOpen = false">Cancel</Button>
                    <Button variant="destructive" @click="confirmDelete" :loading="deleteForm.processing">
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
                        <span>Delete Multiple Hero Slides</span>
                    </DialogTitle>
                    <DialogDescription> Are you sure you want to delete {{ selectedItemsForBulkDelete.length }} selected items? </DialogDescription>
                </DialogHeader>
                <div class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                        <div class="flex items-start space-x-2">
                            <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ selectedItemsForBulkDelete.length }} items</strong> will be deleted.
                            </div>
                        </div>
                    </div>
                    <div class="max-h-32 overflow-y-auto">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Items to be deleted:</strong>
                            <ul class="mt-1 space-y-1">
                                <li v-for="item in selectedItemsForBulkDelete" :key="item.id" class="text-sm">• {{ item.title }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <DialogFooter class="space-x-2">
                    <Button variant="outline" @click="bulkDeleteDialogOpen = false"> Cancel </Button>
                    <Button variant="destructive" @click="confirmBulkDelete" :loading="deleteForm.processing">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete All ({{ selectedItemsForBulkDelete.length }})
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
