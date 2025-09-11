export interface FormulaType {
  id: number;
  name: string;
  slug: string;
}

export interface Formula {
  id: number;
  formula_type_id: number;
  type: FormulaType | null;
  name: string;
  slug: string;
  expression: string;
  description: string;
  effective_from: string;
  effective_to: string;
  is_active: boolean;
  deleted_at: string | null;
}

export interface PaginationLinks {
  first: string | null;
  last: string | null;
  prev: string | null;
  next: string | null;
}

export interface PaginationMeta {
  current_page: number;
  from: number | null;
  last_page: number;
  path: string;
  per_page: number;
  to: number | null;
  total: number;
}

export interface ServerPaginatedResponse<T> {
  data: T[];

  // Required by PrimeVue/DataTable compatibility
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
  first_page_url: string | null;
  last_page_url: string | null;
  prev_page_url: string | null;
  next_page_url: string | null;
  path: string;

  // Optional but useful if your table uses them
  links: PaginationLinks;
  meta: PaginationMeta;
}
