<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait HasDynamicDataTable
{
    /**
     * Get searchable columns from request or use defaults
     */
    protected function getSearchableColumns(Request $request, Model $model): array
    {
        $searchableColumns = $request->get('searchable_columns');

        if ($searchableColumns) {
            $columns = is_array($searchableColumns)
                ? $searchableColumns
                : explode(',', $searchableColumns);
            return $this->validateColumns($columns, $model);
        }

        // Default searchable columns from model's fillable attributes
        $fillableColumns = $model->getFillable();
        $defaultSearchable = array_filter($fillableColumns, function ($column) {
            // Default to text-based columns for searching
            return in_array($column, ['name', 'title', 'slug', 'description', 'content', 'email']);
        });

        return !empty($defaultSearchable) ? $defaultSearchable : ['name'];
    }

    /**
     * Get sortable columns from request or use defaults
     */
    protected function getSortableColumns(Request $request, Model $model): array
    {
        $sortableColumns = $request->get('sortable_columns');

        if ($sortableColumns) {
            $columns = is_array($sortableColumns)
                ? $sortableColumns
                : explode(',', $sortableColumns);
            return $this->validateColumns($columns, $model);
        }

        // Default sortable columns from model's fillable attributes plus system columns
        $fillableColumns = $model->getFillable();
        $systemColumns = ['id', 'created_at', 'updated_at'];

        return array_merge($fillableColumns, $systemColumns);
    }

    /**
     * Validate columns against allowed columns to prevent SQL injection
     */
    protected function validateColumns(array $columns, Model $model): array
    {
        $fillableColumns = $model->getFillable();
        $systemColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $allowedColumns = array_merge($fillableColumns, $systemColumns);

        return array_intersect($columns, $allowedColumns);
    }

    /**
     * Apply global search across specified columns
     */
    protected function applyGlobalSearch($query, Request $request, array $searchableColumns): void
    {
        $search = $request->get('search') ?? $request->get('global');

        if ($search && trim($search) !== '') {
            $query->where(function ($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
    }

    /**
     * Apply dynamic column filters based on request
     */
    protected function applyDynamicFilters($query, Request $request, array $searchableColumns): void
    {
        // Handle filters array format (from frontend)
        $columnFilters = $request->get('filters', []);
        if (is_array($columnFilters) && !empty($columnFilters)) {
            foreach ($columnFilters as $column => $value) {
                if (!empty($value) && trim((string)$value) !== '' && in_array($column, $searchableColumns)) {
                    $this->applyColumnFilter($query, $column, $value);
                }
            }
        }
        // Handle direct column filters (individual request parameters)
        foreach ($searchableColumns as $column) {
            $value = $request->get($column);
            if ($value !== null && trim((string)$value) !== '') {
                $this->applyColumnFilter($query, $column, $value);
            }
        }
    }

    /**
     * Apply sorting with validation
     */
    protected function applySorting($query, Request $request, array $sortableColumns): void
    {
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        if (in_array($sortField, $sortableColumns)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }
    }

    /**
     * Apply pagination with PrimeVue DataTable support
     */
    protected function applyPagination($query, Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $perPage = min(max($perPage, 1), 100); // Ensure between 1 and 100

        // Calculate page from first parameter if provided (PrimeVue sends 'first' parameter)
        $page = $request->get('page', 1);
        if ($request->has('first') && $request->has('rows')) {
            $first = max(0, (int) $request->get('first'));
            $rows = max(1, (int) $request->get('rows'));
            $page = floor($first / $rows) + 1;
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Apply filter to a specific column with appropriate logic
     */
    protected function applyColumnFilter($query, string $column, $value): void
    {
        $columnType = $this->getColumnType($column);
        switch ($columnType) {
            case 'boolean':
                $query->where($column, $this->parseBooleanValue($value));
                break;
            case 'integer':
                if (is_numeric($value)) {
                    $query->where($column, (int)$value);
                }
                break;
            case 'date':
                if ($this->isValidDate($value)) {
                    $query->whereDate($column, $value);
                }
                break;
            case 'string':
            default:
                $query->where($column, 'like', "%{$value}%");
                break;
        }
    }

    /**
     * Get column type for smart filtering
     */
    protected function getColumnType(string $column): string
    {
        $booleanColumns = ['is_active', 'is_enabled', 'is_default', 'is_public', 'is_featured'];
        $integerColumns = ['id', 'duration_days', 'sort_order', 'price', 'amount', 'quantity'];
        $dateColumns = ['created_at', 'updated_at', 'deleted_at', 'published_at', 'expires_at'];

        if (in_array($column, $booleanColumns)) {
            return 'boolean';
        }

        if (in_array($column, $integerColumns)) {
            return 'integer';
        }

        if (in_array($column, $dateColumns)) {
            return 'date';
        }

        return 'string';
    }

    /**
     * Parse boolean value from various formats
     */
    protected function parseBooleanValue($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            return in_array(strtolower($value), ['true', '1', 'yes', 'on', 'active']);
        }

        return (bool) $value;
    }

    /**
     * Check if value is a valid date
     */
    protected function isValidDate($value): bool
    {
        return (bool) strtotime($value);
    }

    /**
     * Apply date range filters
     */
    protected function applyDateRangeFilters($query, Request $request): void
    {
        // Handle created_at date range
        if ($request->has('created_from') && $request->get('created_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_from'));
        }

        if ($request->has('created_to') && $request->get('created_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_to'));
        }

        // Handle updated_at date range
        if ($request->has('updated_from') && $request->get('updated_from')) {
            $query->whereDate('updated_at', '>=', $request->get('updated_from'));
        }

        if ($request->has('updated_to') && $request->get('updated_to')) {
            $query->whereDate('updated_at', '<=', $request->get('updated_to'));
        }
    }

    /**
     * Apply range filters for numeric columns
     */
    protected function applyRangeFilters($query, Request $request, array $rangeColumns = []): void
    {
        foreach ($rangeColumns as $column) {
            $minValue = $request->get($column . '_min');
            $maxValue = $request->get($column . '_max');

            if ($minValue !== null && is_numeric($minValue)) {
                $query->where($column, '>=', $minValue);
            }

            if ($maxValue !== null && is_numeric($maxValue)) {
                $query->where($column, '<=', $maxValue);
            }
        }
    }

    /**
     * Apply special filters that need custom logic
     */
    protected function applySpecialFilters($query, Request $request): void
    {
        // Handle is_active filter
        if ($request->has('is_active') && $request->get('is_active') !== '' && $request->get('is_active') !== null) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Apply date range filters
        $this->applyDateRangeFilters($query, $request);

        // Apply range filters for common numeric columns
        $this->applyRangeFilters($query, $request, ['duration_days', 'price', 'amount', 'sort_order']);
    }

    /**
     * Build a complete paginated query with all filters
     */
    protected function buildPaginatedQuery(
        $query,
        Request $request,
        Model $model,
        ?array $customSearchableColumns = null,
        ?array $customSortableColumns = null
    ) {
        // Get column configurations
        $searchableColumns = $customSearchableColumns ?? $this->getSearchableColumns($request, $model);
        $sortableColumns = $customSortableColumns ?? $this->getSortableColumns($request, $model);

        // Apply all filters
        $this->applyGlobalSearch($query, $request, $searchableColumns);
        $this->applyDynamicFilters($query, $request, $searchableColumns);
        $this->applySpecialFilters($query, $request);
        $this->applySorting($query, $request, $sortableColumns);

        // Apply pagination
        return $this->applyPagination($query, $request);
    }
}
