<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

import { createColumn, renderStatusBadge } from '../../components/DataTable/renderers';
import ServerDataTable from '../../components/DataTable/ServerDataTable.vue';
import { Button } from '../../components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '../../components/ui/dialog';
import { useServerDataTable } from '../../composables/useServerDataTable';
import AppLayout from '../../layouts/AppLayout.vue';
import { BreadcrumbItem } from '../../types';
import { ServerPaginatedResponse } from '../../types/datatable';

interface Experience {
    id: number;
    employee_id?: number;
    employee?: { id: number; first_name?: string; last_name?: string } | null;
    company_name: string;
    position: string;
    start_date?: string | null;
    end_date?: string | null;
    responsibilities?: string | null;
    duration?: string | null;
    is_current?: boolean;
}

const props = defineProps<{
    experiences: ServerPaginatedResponse<Experience>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Experiences', href: route('experiences.index') }];

const { handleSearch, handleSort, handlePageChange, handleFilter, clearFilters, refresh, loading } = useServerDataTable({
    baseRoute: route('experiences.index'),
    defaultSort: { field: 'company_name', order: 'asc' },
    defaultPerPage: 15,
});

const selectedItems = ref<Experience[]>([]);
const itemToDelete = ref<Experience | null>(null);
const deleteDialogOpen = ref(false);
const bulkDeleteDialogOpen = ref(false);
const selectedItemsForBulkDelete = ref<Experience[]>([]);

const deleteForm = useForm({});

// add serial number to each row based on current page & per_page
// robust: coerce meta values to numbers and avoid NaN
const transformedData = computed(() => {
	const data = Array.isArray(props.experiences?.data) ? props.experiences.data : [];
	const perPageRaw = props.experiences?.meta?.per_page;
	const currentPageRaw = props.experiences?.meta?.current_page;

	const perPage = Number.isFinite(Number(perPageRaw)) && Number(perPageRaw) > 0 ? Number(perPageRaw) : 15;
	const currentPage = Number.isFinite(Number(currentPageRaw)) && Number(currentPageRaw) > 0 ? Number(currentPageRaw) : 1;

	const startIndex = (currentPage - 1) * perPage;

	return data.map((item: any, idx: number) => {
		const serialNumCandidate = startIndex + idx + 1;
		const serial = Number.isFinite(Number(serialNumCandidate)) ? serialNumCandidate : null;
		// if serial is null we'll avoid prefixing it in the renderer
		return { ...item, serial };
	});
});

// Columns definition
const columns = computed(() => [
    createColumn('employee', 'Employee', {
        sortable: false,
        searchable: true,
        // only prefix with serial when it's a valid number
        render: (_employee: { first_name?: string; last_name?: string } | undefined, row: any) => {
            const name = row.employee ? `${row.employee.first_name ?? ''} ${row.employee.last_name ?? ''}`.trim() || `#${row.employee_id}` : `#${row.employee_id}`;
            return typeof row.serial === 'number' && Number.isFinite(row.serial) ? `${row.serial}. ${name}` : name;
        },
    }),
    createColumn('company_name', 'Company', { sortable: true, searchable: true }),
    createColumn('position', 'Position', { sortable: true, searchable: true }),
    createColumn('responsibilities', 'Responsibilities', { sortable: false, searchable: false, class: 'text-center' }),
 
    createColumn('start_date', 'Start Date', { sortable: true, searchable: false }),
    createColumn('end_date', 'End Date', { sortable: true, searchable: false }),
]);

const actions = computed(() => [
    {
        label: 'Edit',
        icon: Edit,
        variant: 'outline' as const,
        tooltip: 'Edit experience',
        onClick: (item: Experience) => router.visit(route('experiences.edit', item.id)),
        class: 'text-blue-600 hover:text-blue-700 border-blue-200 hover:border-blue-300',
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        tooltip: 'Delete experience',
        onClick: (item: Experience) => deleteItem(item.id),
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
    deleteForm.delete(route('experiences.destroy', itemToDelete.value.id), {
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
        route('experiences.bulk-destroy'),
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

const handleBulkAction = (data: { action: string; selectedRows: Experience[] }) => {
    selectedItemsForBulkDelete.value = data.selectedRows;
    if (data.action === 'delete') {
        bulkDeleteDialogOpen.value = true;
    }
};
</script>

<template>
    <Head title="Experiences Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header (Responsive) -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Experiences</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage employee experiences and their details.</p>
                </div>
                <div class="flex items-center space-x-0 sm:space-x-3">
                    <Button
                        @click="router.visit(route('experiences.create'))"
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 sm:w-auto"
                    >
                        <Plus class="h-4 w-4" />
                        <span>Create Experience</span>
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <ServerDataTable
                :data="transformedData"
                :columns="columns"
                :actions="actions"
                :bulk-actions="bulkActions"
                :total-records="experiences.meta.total"
                :current-page="experiences.meta.current_page"
                :loading="loading"
                :default-rows-per-page="10"
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

        <!-- Single Delete Dialog -->
        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle class="flex items-center space-x-2">
                        <Trash2 class="h-5 w-5 text-red-500" />
                        <span>Delete Experience</span>
                    </DialogTitle>
                    <DialogDescription> Are you sure you want to delete this experience? This action cannot be undone. </DialogDescription>
                </DialogHeader>
                <div v-if="itemToDelete" class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                        <div class="flex items-start space-x-2">
                            <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ itemToDelete.company_name }} — {{ itemToDelete.position }}</strong
                                ><br />
                                <span class="text-xs">Employee: {{ itemToDelete.employee ? (((itemToDelete.employee.first_name ?? '') + ' ' + (itemToDelete.employee.last_name ?? '')).trim() || ('#' + itemToDelete.employee_id)) : ('#' + itemToDelete.employee_id) }}</span
                                ><br />
                                <span class="text-xs">Duration: {{ itemToDelete.duration ?? 'N/A' }}</span
                                ><br />
                                <span class="text-xs">Start: {{ itemToDelete.start_date ?? 'N/A' }} | End: {{ itemToDelete.end_date ?? 'N/A' }}</span>
                                <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                    <strong>Responsibilities:</strong>
                                    <div class="mt-1 max-h-24 overflow-y-auto text-xs text-gray-600 dark:text-gray-400">
                                        {{ itemToDelete.responsibilities ?? '—' }}
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
                        Delete Experience
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
                        <span>Delete Multiple Experiences</span>
                    </DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete {{ selectedItemsForBulkDelete.length }} selected experiences? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                        <div class="flex items-start space-x-2">
                            <Trash2 class="mt-0.5 h-4 w-4 text-red-600 dark:text-red-400" />
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <strong>{{ selectedItemsForBulkDelete.length }} experiences</strong> will be permanently deleted.
                            </div>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto text-sm text-gray-600 dark:text-gray-400">
                        <strong>Experiences to be deleted:</strong>
                        <ul class="mt-1 space-y-1">
                            <li v-for="item in selectedItemsForBulkDelete" :key="item.id">
                                • {{ item.company_name }} — {{ item.position }} (Employee: {{ item.employee ? (((item.employee.first_name ?? '') + ' ' + (item.employee.last_name ?? '')).trim() || ('#' + item.employee_id)) : ('#' + item.employee_id) }})
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
