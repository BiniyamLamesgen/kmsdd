<script setup lang="ts" generic="T extends Record<string, any>">
import { router } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { Download, Filter, FilterX, Plus, RefreshCw, Search, Trash2 } from 'lucide-vue-next';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Tag from 'primevue/tag';
import Toolbar from 'primevue/toolbar';
import { computed, onMounted, ref, watch } from 'vue';

// Types
export interface DataTableColumn<T = any> {
    key: string;
    label: string;
    sortable?: boolean;
    searchable?: boolean;
    render?: (value: any, row: T) => any;
    class?: string;
    headerClass?: string;
    width?: string;
    style?: string;
}

export interface DataTableAction<T = any> {
    label?: string;
    icon?: any;
    variant?: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost' | 'link';
    onClick: (row: T) => void;
    show?: (row: T) => boolean;
    class?: string;
    tooltip?: string;
}

export interface DataTableProps<T> {
    // Core data props
    data: T[];
    columns: DataTableColumn<T>[];
    actions?: DataTableAction<T>[];
    totalRecords?: number;
    loading?: boolean;

    // Feature toggles
    searchable?: boolean;
    sortable?: boolean;
    paginator?: boolean;
    selectionMode?: 'single' | 'multiple' | null;
    filterable?: boolean;
    exportable?: boolean;
    columnsToggle?: boolean;

    // UI customization
    title?: string;
    subtitle?: string;
    createRoute?: string;
    createLabel?: string;
    striped?: boolean;
    hoverable?: boolean;
    bordered?: boolean;
    responsive?: boolean;
    size?: 'small' | 'normal' | 'large';

    // Pagination options
    rowsPerPageOptions?: number[];
    defaultRowsPerPage?: number;
    currentPage?: number;

    // Search and filtering
    globalFilterFields?: string[];
    emptyMessage?: string;

    // Bulk actions
    bulkActions?: Array<{
        label: string;
        action: string;
        variant?: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost';
        icon?: any;
    }>;

    // Event handlers
    onSearch?: (searchTerm: string) => void;
    onSort?: (field: string, order: 'asc' | 'desc') => void;
    onPageChange?: (page: number, rows: number) => void;
    onSelectionChange?: (selection: T | T[]) => void;
    onFilter?: (filters: Record<string, any>) => void;
    onExport?: (format: 'csv' | 'excel' | 'pdf') => void;
}

// Props with defaults
const props = withDefaults(defineProps<DataTableProps<T>>(), {
    loading: false,
    searchable: true,
    sortable: true,
    paginator: true,
    selectionMode: null,
    filterable: true,
    exportable: false,
    columnsToggle: true,
    striped: true,
    hoverable: true,
    bordered: true,
    responsive: true,
    size: 'normal',
    emptyMessage: 'No records found',
    createLabel: 'Add New',
    rowsPerPageOptions: () => [5, 8, 10, 25, 50, 100],
    defaultRowsPerPage: 8,
    currentPage: 1,
    globalFilterFields: () => [],
    bulkActions: () => [],
    actions: () => [],
});

// Emits
const emit = defineEmits<{
    bulkAction: [data: { action: string; selectedRows: T[] }];
    search: [searchTerm: string];
    filter: [filters: Record<string, any>];
    clearFilters: [];
    refresh: [];
    sort: [field: string, order: 'asc' | 'desc'];
    pageChange: [page: number, rows: number];
    selectionChange: [selection: T | T[]];
    export: [format: 'csv' | 'excel' | 'pdf'];
}>();

// Reactive state
const dt = ref();
const selectedRows = ref<T | T[] | null>(null);
const filters = ref({});
const visibleColumns = ref<string[]>(props.columns.map((col) => col.key));
const searchTerm = ref('');
const rows = ref(props.defaultRowsPerPage);
const first = ref((props.currentPage - 1) * props.defaultRowsPerPage);
const isExporting = ref(false);

// Initialize filters
const initFilters = () => {
    const newFilters: any = {};

    if (props.searchable) {
        newFilters.global = { value: null, matchMode: FilterMatchMode.CONTAINS };
    }

    props.columns.forEach((column) => {
        if (column.searchable !== false) {
            newFilters[column.key] = { value: null, matchMode: FilterMatchMode.CONTAINS };
        }
    });

    filters.value = newFilters;
};

onMounted(() => {
    initFilters();
});

// Computed properties
const hasSelectedRows = computed(() => {
    if (Array.isArray(selectedRows.value)) {
        return selectedRows.value.length > 0;
    }
    return selectedRows.value !== null && selectedRows.value !== undefined;
});

const selectedRowsCount = computed(() => {
    if (Array.isArray(selectedRows.value)) {
        return selectedRows.value.length;
    }
    return hasSelectedRows.value ? 1 : 0;
});

const visibleColumnsOptions = computed(() => props.columns.map((col) => ({ label: col.label, value: col.key })));

const filteredColumns = computed(() => props.columns.filter((col) => visibleColumns.value.includes(col.key)));

const paginatorFirst = computed(() => first.value);

const tableClass = computed(() => {
    const classes: string[] = [];
    if (props.striped) classes.push('p-datatable-striped');
    if (props.hoverable) classes.push('p-datatable-hoverable');
    if (props.size === 'small') classes.push('p-datatable-sm');
    if (props.size === 'large') classes.push('p-datatable-lg');
    return classes.join(' ');
});

// Watch for search changes
watch(searchTerm, (newValue) => {
    if ((filters.value as any).global) {
        (filters.value as any).global.value = newValue;
    }
    emit('search', newValue);
});

// Watch for filter changes
watch(
    filters,
    (newFilters) => {
        // Extract column-specific filters and send them to backend
        const columnFilters: Record<string, any> = {};

        Object.keys(newFilters).forEach((key) => {
            const filter = (newFilters as any)[key];
            if (filter?.value && key !== 'global') {
                columnFilters[key] = filter.value;
            }
        });

        // Always emit filter changes (even if empty) so backend can clear filters
        // emit('filter', columnFilters);
    },
    { deep: true },
);

// Watch for currentPage prop changes to update first value
watch(
    () => props.currentPage,
    (newPage) => {
        first.value = (newPage - 1) * rows.value;
    },
);

// Watch for rows per page changes
watch(
    () => props.defaultRowsPerPage,
    (newRows) => {
        rows.value = newRows;
        first.value = (props.currentPage - 1) * newRows;
    },
);

// Event handlers
const handleSort = (event: any) => {
    const { sortField, sortOrder } = event;
    const order = sortOrder === 1 ? 'asc' : 'desc';
    emit('sort', sortField, order);
};

const handlePageChange = (event: any) => {
    first.value = event.first;
    rows.value = event.rows;
    const currentPage = Math.floor(event.first / event.rows) + 1;
    emit('pageChange', currentPage, event.rows);
};

const handleSelectionChange = () => {
    emit('selectionChange', selectedRows.value as T | T[]);
};

// Action handlers
const handleAction = (action: DataTableAction<T>, row: T) => {
    action.onClick(row);
};

const shouldShowAction = (action: DataTableAction<T>, row: T) => {
    return action.show ? action.show(row) : true;
};

const getColumnValue = (row: T, column: DataTableColumn<T>) => {
    if (column.render) {
        return column.render(row[column.key], row);
    }
    return row[column.key];
};

// Utility functions
const handleCreate = () => {
    if (props.createRoute) {
        router.visit(props.createRoute);
    }
};

const handleRefresh = () => {
    // Reset to first page
    first.value = 0;

    // Clear selected rows
    selectedRows.value = props.selectionMode === 'multiple' ? [] : null;

    // Force a page refresh by visiting the current route
    router.reload({ only: ['billingCycles'] });

    // Also emit refresh event for parent component handling
    emit('refresh');
};

const exportCSV = () => {
    if (dt.value) {
        dt.value.exportCSV();
    }
    emit('export', 'csv');
};

const clearFilters = () => {
    // Clear search term
    searchTerm.value = '';

    // Reset filters to initial state
    const newFilters: any = {};

    if (props.searchable) {
        newFilters.global = { value: null, matchMode: FilterMatchMode.CONTAINS };
    }

    props.columns.forEach((column) => {
        if (column.searchable !== false) {
            newFilters[column.key] = { value: null, matchMode: FilterMatchMode.CONTAINS };
        }
    });

    filters.value = newFilters;

    // Reset to first page
    first.value = 0;

    // Clear selected rows
    selectedRows.value = props.selectionMode === 'multiple' ? [] : null;

    // Emit clear filters event
    emit('clearFilters');
};

const clearSelection = () => {
    selectedRows.value = props.selectionMode === 'multiple' ? [] : null;
    handleSelectionChange();
};

const handleBulkAction = (actionType: string) => {
    if (!hasSelectedRows.value) return;

    emit('bulkAction', {
        action: actionType,
        selectedRows: Array.isArray(selectedRows.value) ? selectedRows.value : [selectedRows.value as T],
    });
};

const handleExport = (format: 'csv' | 'excel' | 'pdf') => {
    isExporting.value = true;

    if (format === 'csv' && dt.value) {
        dt.value.exportCSV();
    }

    emit('export', format);

    setTimeout(() => {
        isExporting.value = false;
    }, 1000);
};

// Get action button severity based on variant
const getActionSeverity = (variant?: string) => {
    switch (variant) {
        case 'destructive':
            return 'danger';
        case 'outline':
            return 'secondary';
        case 'secondary':
            return 'secondary';
        case 'default':
            return 'info';
        default:
            return 'info';
    }
};
</script>

<template>
    <div class="space-y-6">
        <!-- Header Section with Title and Subtitle -->
        <div v-if="title || subtitle" class="space-y-1">
            <h2 v-if="title" class="text-2xl font-bold tracking-tight">
                {{ title }}
            </h2>
            <p v-if="subtitle" class="text-sm">
                {{ subtitle }}
            </p>
        </div>

        <!-- Card Container -->
        <Card>
            <!-- Toolbar -->
            <template #header>
                <Toolbar>
                    <template #start>
                        <!-- Selection Info -->
                        <Tag v-if="hasSelectedRows" :value="`${selectedRowsCount} selected`" severity="info" class="mr-2" />
                        <Button v-if="hasSelectedRows" label="Clear" severity="secondary" size="small" text class="mr-2" @click="clearSelection">
                            <template #icon>
                                <FilterX class="h-3 w-3" />
                            </template>
                        </Button>

                        <!-- Bulk Actions -->
                        <div v-if="hasSelectedRows" class="flex items-center gap-2">
                            <Button
                                v-for="bulkAction in bulkActions"
                                :key="bulkAction.action"
                                :label="bulkAction.label"
                                :severity="getActionSeverity(bulkAction.variant)"
                                size="small"
                                outlined
                                @click="handleBulkAction(bulkAction.action)"
                            >
                                <template #icon>
                                    <component :is="bulkAction.icon" class="h-3 w-3" />
                                </template>
                            </Button>

                            <!-- Default Delete Action (if no custom bulk actions) -->
                            <Button
                                v-if="!bulkActions || bulkActions.length === 0"
                                label="Delete Selected"
                                severity="danger"
                                size="small"
                                outlined
                                @click="handleBulkAction('delete')"
                            >
                                <template #icon>
                                    <Trash2 class="h-3 w-3" />
                                </template>
                            </Button>
                        </div>
                    </template>

                    <template #center>
                        <!-- Global Search -->
                        <IconField v-if="searchable" iconPosition="left">
                            <InputIcon>
                                <Search class="h-4 w-4" />
                            </InputIcon>
                            <InputText v-model="searchTerm" placeholder="Search..." />
                        </IconField>
                    </template>

                    <template #end>
                        <!-- Filters Toggle -->
                        <Button
                            v-if="filterable"
                            severity="secondary"
                            outlined
                            size="small"
                            class="mr-2"
                            @click="clearFilters"
                            :badge="Object.values(filters).some((f: any) => f?.value) ? '1' : undefined"
                            title="Clear Filters"
                        >
                            <template #icon>
                                <Filter class="h-3 w-3" />
                            </template>
                        </Button>

                        <!-- Column Visibility Toggle -->
                        <MultiSelect
                            v-if="columnsToggle"
                            v-model="visibleColumns"
                            :options="visibleColumnsOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Columns"
                            class="mr-2"
                            :maxSelectedLabels="0"
                            :selectedItemsLabel="`${visibleColumns.length} columns`"
                            title="Toggle Column Visibility"
                        />

                        <!-- Export Options -->
                        <Button
                            v-if="exportable"
                            severity="secondary"
                            outlined
                            size="small"
                            class="mr-2"
                            @click="handleExport('csv')"
                            :loading="isExporting"
                            title="Export to CSV"
                        >
                            <template #icon>
                                <Download class="h-3 w-3" />
                            </template>
                        </Button>

                        <!-- Refresh Button -->
                        <Button
                            severity="secondary"
                            outlined
                            size="small"
                            class="mr-2"
                            @click="handleRefresh"
                            :loading="loading"
                            title="Refresh Data"
                        >
                            <template #icon>
                                <RefreshCw class="h-3 w-3" />
                            </template>
                        </Button>

                        <!-- Create Button -->
                        <Button v-if="createRoute" :label="createLabel" size="small" @click="handleCreate" title="Add New Record">
                            <template #icon>
                                <Plus class="h-3 w-3" />
                            </template>
                        </Button>
                    </template>
                </Toolbar>
            </template>

            <!-- DataTable Content -->
            <template #content>
                <div class="relative">
                    <DataTable
                        ref="dt"
                        v-model:selection="selectedRows"
                        v-model:filters="filters"
                        :value="data"
                        :loading="loading"
                        :class="tableClass"
                        :sortMode="sortable ? 'single' : undefined"
                        :selectionMode="selectionMode || undefined"
                        :globalFilterFields="globalFilterFields"
                        :emptyMessage="emptyMessage"
                        :scrollable="true"
                        scrollHeight="55vh"
                        :resizableColumns="true"
                        columnResizeMode="expand"
                        filterDisplay="menu"
                        :paginator="paginator"
                        :rows="rows"
                        :totalRecords="totalRecords || data.length"
                        :first="first"
                        :rowsPerPageOptions="rowsPerPageOptions"
                        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} to {last} of {totalRecords} entries"
                        :lazy="true"
                        @sort="handleSort"
                        @selection-change="handleSelectionChange"
                        @page="handlePageChange"
                        tableStyle="min-width: 50rem"
                        dataKey="id"
                    >
                        <!-- Selection Column -->
                        <Column v-if="selectionMode" selectionMode="multiple" headerStyle="width: 3rem" :exportable="false" />

                        <!-- Data Columns -->
                        <Column
                            v-for="column in filteredColumns"
                            :key="column.key"
                            :field="column.key"
                            :header="column.label"
                            :sortable="sortable && column.sortable !== false"
                            :showFilterMatchModes="false"
                            :filterMenuStyle="{ width: '14rem' }"
                            :style="column.width ? { width: column.width, minWidth: column.width } : undefined"
                            :exportable="column.key !== 'actions'"
                        >
                            <template #body="{ data: row }">
                                <component
                                    :is="typeof getColumnValue(row, column) === 'object' ? getColumnValue(row, column) : 'span'"
                                    v-if="typeof getColumnValue(row, column) === 'object'"
                                />
                                <span v-else>{{ getColumnValue(row, column) }}</span>
                            </template>

                            <template #filter="{ filterModel }" v-if="filterable && column.searchable !== false">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    class="p-column-filter"
                                    :placeholder="`Search by ${column.label}`"
                                />
                            </template>
                        </Column>

                        <!-- Actions Column -->
                        <Column
                            v-if="actions && actions.length > 0"
                            header="Actions"
                            :exportable="false"
                            headerStyle="width: 8rem; text-align: center"
                            bodyStyle="text-align: center"
                            alignFrozen="right"
                        >
                            <template #body="{ data: row }">
                                <div class="flex items-center justify-center gap-1">
                                    <Button
                                        v-for="(action, index) in actions"
                                        :key="index"
                                        v-show="shouldShowAction(action, row)"
                                        :icon="action.icon ? 'pi pi-' + (action.variant === 'destructive' ? 'trash' : 'pencil') : undefined"
                                        :severity="getActionSeverity(action.variant)"
                                        :outlined="action.variant === 'outline'"
                                        size="small"
                                        :class="action.class"
                                        :title="action.tooltip || action.label || 'Action'"
                                        @click="handleAction(action, row)"
                                    >
                                        <template v-if="action.icon" #icon>
                                            <component :is="action.icon" class="h-3 w-3" />
                                        </template>
                                        <span v-if="!action.icon && action.label" class="text-xs">{{ action.label }}</span>
                                    </Button>
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </template>

            <!-- Optional Footer for additional information -->
            <template #footer v-if="!paginator && emptyMessage && data.length === 0">
                <div class="px-4 py-3 text-center text-sm">
                    {{ emptyMessage }}
                </div>
            </template>
        </Card>
    </div>
</template>
