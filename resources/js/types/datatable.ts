// DataTable Types
/**
 * Server-side paginated response structure that matches Laravel Resource Collections
 * This interface ensures type safety between backend and frontend pagination data
 */
export interface ServerPaginatedResponse<T> {
  /** Array of data items for the current page */
  data: T[];

  /** Pagination links for navigation */
  links: {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };

  /** Pagination metadata */
  meta: {
    current_page: number;
    from: number | null;
    last_page: number;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  };

  // Additional compatibility fields for DataTable components
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
  first_page_url: string | null;
  last_page_url: string | null;
  prev_page_url: string | null;
  next_page_url: string | null;
  path: string;
}

/**
 * DataTable column configuration for server-side tables
 */
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
  exportable?: boolean;
}

/**
 * DataTable action configuration for row-level actions
 */
export interface DataTableAction<T = any> {
  label?: string;
  icon?: any;
  variant?: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost' | 'link';
  onClick: (row: T) => void;
  show?: (row: T) => boolean;
  class?: string;
  tooltip?: string;
}

/**
 * Sort configuration for server-side sorting
 */
export interface SortConfig {
  field: string;
  order: 'asc' | 'desc';
}

/**
 * Filter configuration for server-side filtering
 */
export interface FilterConfig {
  [key: string]: any;
}

/**
 * Pagination configuration for server-side pagination
 */
export interface PaginationConfig {
  page: number;
  perPage: number;
  total?: number;
}
