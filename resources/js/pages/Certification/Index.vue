<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

import { createColumn, renderCertificationImage } from '../../components/DataTable/renderers';
import ServerDataTable from '../../components/DataTable/ServerDataTable.vue';
import { Button } from '../../components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '../../components/ui/dialog';
import { useServerDataTable } from '../../composables/useServerDataTable';
import AppLayout from '../../layouts/AppLayout.vue';
import { BreadcrumbItem } from '../../types';
import { ServerPaginatedResponse } from '../../types/datatable';

interface Certification {
    id: number;
    employee_id?: number;
    employee?: { id: number; first_name?: string; last_name?: string } | null;
    certification_name: string;
    issuing_organization: string;
    issue_date?: string | null;
    image?: string | null;
    image_url?: string | null;
    created_at?: string;
    updated_at?: string;
}

const props = defineProps<{
    certifications: ServerPaginatedResponse<Certification>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Certifications', href: route('certifications.index') }];

const { handleSearch, handleSort, handlePageChange, handleFilter, clearFilters, refresh, loading } = useServerDataTable({
    baseRoute: route('certifications.index'),
    defaultSort: { field: 'certification_name', order: 'asc' },
    defaultPerPage: 15,
});

const selectedItems = ref<Certification[]>([]);
const itemToDelete = ref<Certification | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Certification[]>([]);

const deleteForm = useForm({});

// Add serial number to each row based on current page & per_page
const transformedData = computed(() => {
    const data = Array.isArray(props.certifications?.data) ? props.certifications.data : [];
    const perPageRaw = props.certifications?.meta?.per_page;
    const currentPageRaw = props.certifications?.meta?.current_page;

    const perPage = Number.isFinite(Number(perPageRaw)) && Number(perPageRaw) > 0 ? Number(perPageRaw) : 15;
    const currentPage = Number.isFinite(Number(currentPageRaw)) && Number(currentPageRaw) > 0 ? Number(currentPageRaw) : 1;

    const startIndex = (currentPage - 1) * perPage;

    return data.map((item: any, idx: number) => {
        const serialNumCandidate = startIndex + idx + 1;
        const serial = Number.isFinite(Number(serialNumCandidate)) ? serialNumCandidate : null;
        return { ...item, serial };
    });
});

// Enhanced column definition with better image handling
const columns = computed(() => [
    createColumn('employee', 'Employee', {
        sortable: false,
        searchable: true,
        render: (_employee: { first_name?: string; last_name?: string } | undefined, row: any) => {
            const name = row.employee ? `${row.employee.first_name ?? ''} ${row.employee.last_name ?? ''}`.trim() || `#${row.employee_id}` : `#${row.employee_id}`;
            return typeof row.serial === 'number' && Number.isFinite(row.serial) ? `${row.serial}. ${name}` : name;
        },
    }),
    createColumn('certification_name', 'Certification', { sortable: true, searchable: true }),
    createColumn('issuing_organization', 'Issuing Organization', { sortable: true, searchable: true }),
    createColumn('issue_date', 'Issue Date', { sortable: true, searchable: false }),
    createColumn('image_url', 'Certificate', {
        sortable: false,
        searchable: false,
        render: (image_url: string, row: any) => {
            // Log for debugging
            if (image_url) {
                console.log(`Rendering certificate image for ID ${row.id}:`, image_url);
            }
            return renderCertificationImage(image_url, `Certificate for ${row.certification_name}`, 50);
        },
        class: 'text-center',
    }),
]);

const actions = computed(() => [
    {
        label: 'Edit',
        icon: Edit,
        variant: 'outline' as const,
        tooltip: 'Edit certification',
        onClick: (item: Certification) => router.visit(route('certifications.edit', item.id)),
        class: 'text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300',
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        tooltip: 'Delete certification',
        onClick: (item: Certification) => deleteItem(item.id),
        class: 'text-red-600 hover:text-red-700',
    },
]);

const bulkActions = computed(() => [
    {
        label: 'Delete Selected',
        action: 'delete',
        icon: Trash2,
        variant: 'destructive' as const,
    },
]);

function deleteItem(id: number) {
    // look up in transformedData (so item includes serial)
    const item = transformedData.value.find((i: { id: number }) => i.id === id);
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
    deleteForm.delete(route('certifications.destroy', itemToDelete.value.id), {
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
        route('certifications.bulk-destroy'),
        { ids: selectedItemsForBulkDelete.value.map((i) => i.id) },
        {
            preserveScroll: true,
            onSuccess: () => {
                bulkDeleteDialogOpen.value = false;
                selectedItemsForBulkDelete.value = [];
                refresh();
            },
        },
    );
}

const handleBulkAction = (data: { action: string; selectedRows: Certification[] }) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === 'delete') {
        bulkDeleteDialogOpen.value = true;
    }
};

// Add function to view certificate in large format
function viewCertificate(certification: Certification) {
    if (certification.image_url) {
        window.open(certification.image_url, '_blank');
    }
}
</script>

<template>
    <Head title="Certifications Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- <pre>{{ certifications }}</pre> -->
            <!-- Header (Responsive) -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Certifications</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage employee certifications and their details.</p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('certifications.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Create Certification</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <ServerDataTable
                :data="transformedData"
                :columns="columns"
                :actions="actions"
                :bulk-actions="bulkActions"
                :total-records="certifications.meta.total"
                :current-page="certifications.meta.current_page"
                :loading="loading"
                :default-rows-per-page="15"
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

        <!-- Delete Dialog -->
        <!-- Single Delete Dialog -->
        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle class="flex items-center space-x-2">
                        <Trash2 class="h-5 w-5 text-red-500" />
                        <span>Delete Certification</span>
                    </DialogTitle>
                    <DialogDescription> Are you sure you want to delete this certification? This action cannot be undone. </DialogDescription>
                </DialogHeader>
                <div v-if="itemToDelete" class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                        <div class="flex items-start space-x-2">
                            <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ itemToDelete.certification_name }} — {{ itemToDelete.issuing_organization }}</strong
                                ><br />
                                <span class="text-xs">Employee: {{ itemToDelete.employee ? (((itemToDelete.employee.first_name ?? '') + ' ' + (itemToDelete.employee.last_name ?? '')).trim() || ('#' + itemToDelete.employee_id)) : ('#' + itemToDelete.employee_id) }}</span
                                ><br />
                                <span class="text-xs">Issue Date: {{ itemToDelete.issue_date ?? 'N/A' }}</span>
                                <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                    <strong>Certificate Image:</strong>
                                    <div class="mt-1 flex items-center">
                                        <img
                                            v-if="itemToDelete?.image_url"
                                            :src="itemToDelete.image_url"
                                            alt="Certificate"
                                            style="max-width:100px;max-height:100px;"
                                            class="rounded border border-gray-200 hover:border-primary cursor-pointer"
                                            @click="viewCertificate(itemToDelete)"
                                            @error="(e) => { 
                                                console.error('Failed to load image in dialog:', itemToDelete.image_url);
                                                e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1IiAvPjx0ZXh0IHg9IjQwIiB5PSI0MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg=='; 
                                                e.target.classList.add('opacity-60');
                                            }"
                                        />
                                        <small
                                            v-if="itemToDelete?.image_url"
                                            class="ml-2 text-blue-600 dark:text-blue-400 cursor-pointer"
                                            @click="viewCertificate(itemToDelete)"
                                        >
                                            Click to view full size
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <DialogFooter class="space-x-2">
                    <Button variant="outline" @click="deleteDialogOpen = false" class="cursor-pointer">Cancel</Button>
                    <Button variant="destructive" @click="confirmDelete" :loading="deleteForm.processing" class="cursor-pointer">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete Certification
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
                        <span>Delete Multiple Certifications</span>
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete {{ selectedItemsForBulkDelete.length }} selected certifications? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                        <div class="flex items-start space-x-2">
                            <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ selectedItemsForBulkDelete.length }} certifications</strong> will be permanently deleted.
                            </div>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto text-sm text-gray-600 dark:text-gray-400">
                        <strong>Certifications to be deleted:</strong>
                        <ul class="mt-1 space-y-1">
                            <li v-for="item in selectedItemsForBulkDelete" :key="item.id">
                                • {{ item.certification_name }} — {{ item.issuing_organization }} (Employee: {{ item.employee ? (((item.employee.first_name ?? '') + ' ' + (item.employee.last_name ?? '')).trim() || ('#' + item.employee_id)) : ('#' + item.employee_id) }})
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
    </AppLayout>
</template>

<style scoped>
.error-image {
    opacity: 0.6;
    border: 1px dashed #ccc;
}
</style>
