import { router } from '@inertiajs/vue3';
import { ref, watch, type Ref } from 'vue';

export interface ServerDataTableState {
    search: string;
    sortField: string | null;
    sortOrder: 'asc' | 'desc';
    page: number;
    perPage: number;
    filters: Record<string, any>;
    columnFilters: Record<string, any>;
    dateRangeFilters: Record<string, { from?: string; to?: string }>;
}

export interface ServerDataTableOptions {
    baseRoute: string;
    defaultSort?: { field: string; order: 'asc' | 'desc' };
    defaultPerPage?: number;
    preserveState?: boolean;
    preserveScroll?: boolean;
    debounceMs?: number;
    columns?: Array<{
        key: string;
        sortable?: boolean;
        searchable?: boolean;
    }>;
}

export function useServerDataTable(options: ServerDataTableOptions) {
    const state = ref<ServerDataTableState>({
        search: '',
        sortField: options.defaultSort?.field || null,
        sortOrder: options.defaultSort?.order || 'asc',
        page: 1,
        perPage: options.defaultPerPage || 10,
        filters: {},
        columnFilters: {},
        dateRangeFilters: {},
    });

    const loading = ref(false);

    // Build query parameters
    const buildParams = () => {
        const params: Record<string, any> = {
            page: state.value.page,
            per_page: state.value.perPage,
        };

        if (state.value.search && state.value.search.trim() !== '') {
            params.search = state.value.search;
        }

        if (state.value.sortField) {
            params.sort = state.value.sortField;
            params.order = state.value.sortOrder;
        }

        // Add filters only if they have values
        Object.entries(state.value.filters).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '' && String(value).trim() !== '') {
                params[key] = String(value); // Ensure it's always a string
            }
        });

        // Add column filters
        Object.entries(state.value.columnFilters).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '' && String(value).trim() !== '') {
                params[key] = String(value);
            }
        });

        // Add date range filters
        Object.entries(state.value.dateRangeFilters).forEach(([key, dateRange]) => {
            if (dateRange.from) {
                params[`${key}_from`] = dateRange.from;
            }
            if (dateRange.to) {
                params[`${key}_to`] = dateRange.to;
            }
        });

        // Send column metadata to backend for dynamic query building
        if (options.columns) {
            const searchableColumns = options.columns.filter((col) => col.searchable !== false).map((col) => col.key);

            const sortableColumns = options.columns.filter((col) => col.sortable !== false).map((col) => col.key);

            if (searchableColumns.length > 0) {
                params.searchable_columns = searchableColumns.join(',');
            }

            if (sortableColumns.length > 0) {
                params.sortable_columns = sortableColumns.join(',');
            }
        }

        return params;
    };

    // Fetch data from server
    const fetchData = (resetPage = false) => {
        if (resetPage) {
            state.value.page = 1;
        }

        loading.value = true;

        router.get(options.baseRoute, buildParams(), {
            preserveState: options.preserveState ?? true,
            preserveScroll: options.preserveScroll ?? true,
            onFinish: () => {
                loading.value = false;
            },
        });
    };

    // Debounced search
    let searchTimeout: ReturnType<typeof setTimeout>;
    const debouncedSearch = () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchData(true);
        }, options.debounceMs || 300);
    };

    // Watch for search changes
    watch(
        () => state.value.search,
        () => {
            debouncedSearch();
        },
    );

    // Search handler
    const handleSearch = (searchTerm: string) => {
        state.value.search = searchTerm;
    };

    // Sort handler
    const handleSort = (field: string, order: 'asc' | 'desc') => {
        state.value.sortField = field;
        state.value.sortOrder = order;
        fetchData();
    };

    // Page change handler
    const handlePageChange = (page: number, perPage: number) => {
        state.value.page = page;
        state.value.perPage = perPage;
        fetchData();
    };

    // Filter handler
    const handleFilter = (filters: Record<string, any>) => {
        state.value.filters = { ...state.value.filters, ...filters };
        fetchData(true);
    };

    // Clear filters
    const clearFilters = () => {
        state.value.filters = {};
        state.value.columnFilters = {};
        state.value.dateRangeFilters = {};
        state.value.search = '';
        state.value.page = 1;
        fetchData();
    };

    // Refresh data
    const refresh = () => {
        fetchData();
    };

    // Reset to initial state
    const reset = () => {
        state.value = {
            search: '',
            sortField: options.defaultSort?.field || null,
            sortOrder: options.defaultSort?.order || 'asc',
            page: 1,
            perPage: options.defaultPerPage || 10,
            filters: {},
            columnFilters: {},
            dateRangeFilters: {},
        };
        fetchData();
    };

    // Set filter
    const setFilter = (key: string, value: any) => {
        state.value.filters[key] = value;
        fetchData(true);
    };

    // Remove filter
    const removeFilter = (key: string) => {
        delete state.value.filters[key];
        fetchData(true);
    };

    // Set column filter
    const setColumnFilter = (key: string, value: any) => {
        state.value.columnFilters[key] = value;
        fetchData(true);
    };

    // Remove column filter
    const removeColumnFilter = (key: string) => {
        delete state.value.columnFilters[key];
        fetchData(true);
    };

    // Set date range filter
    const setDateRangeFilter = (key: string, from?: string, to?: string) => {
        state.value.dateRangeFilters[key] = { from, to };
        fetchData(true);
    };

    // Remove date range filter
    const removeDateRangeFilter = (key: string) => {
        delete state.value.dateRangeFilters[key];
        fetchData(true);
    };

    // Batch update filters
    const batchUpdateFilters = (filters: {
        filters?: Record<string, any>;
        columnFilters?: Record<string, any>;
        dateRangeFilters?: Record<string, { from?: string; to?: string }>;
    }) => {
        if (filters.filters) {
            state.value.filters = { ...state.value.filters, ...filters.filters };
        }
        if (filters.columnFilters) {
            state.value.columnFilters = { ...state.value.columnFilters, ...filters.columnFilters };
        }
        if (filters.dateRangeFilters) {
            state.value.dateRangeFilters = { ...state.value.dateRangeFilters, ...filters.dateRangeFilters };
        }
        fetchData(true);
    };

    return {
        state: state as Ref<ServerDataTableState>,
        loading,
        handleSearch,
        handleSort,
        handlePageChange,
        handleFilter,
        setFilter,
        removeFilter,
        setColumnFilter,
        removeColumnFilter,
        setDateRangeFilter,
        removeDateRangeFilter,
        batchUpdateFilters,
        clearFilters,
        refresh,
        reset,
        fetchData,
    };
}

// Helper function to format data for PrimeVue DataTable
export function formatServerDataForTable<T>(serverData: { data: T[]; current_page: number; per_page: number; total: number; last_page: number }) {
    return {
        data: serverData.data,
        currentPage: serverData.current_page,
        perPage: serverData.per_page,
        totalRecords: serverData.total,
        totalPages: serverData.last_page,
    };
}
