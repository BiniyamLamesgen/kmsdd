<?php

namespace App\Actions\Department;

use App\HasDynamicDataTable;
use App\Http\Resources\DepartmentCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Department;

class IndexDepartmentAction
{
    use HasDynamicDataTable;

    public function all()
    {
        $query = Department::query();
        return $query->orderBy('name', 'asc')->get();
    }

    public function paginated(Request $request): LengthAwarePaginator
    {
        $query = Department::query();
        $model = new Department();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Department();

        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['name', 'title', 'slug', 'description', 'content', 'email']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['name'];
        }

        $validatedColumns = $this->validateColumns($searchableColumns, $model);

        return Department::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }
public function getIndexData(Request $request): array
{
    try {
        $paginated = $this->paginated($request);

        return [
            'success' => true,
            'departments' => new DepartmentCollection($paginated),
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'departments' => collect(),
        ];
    }
}
}
