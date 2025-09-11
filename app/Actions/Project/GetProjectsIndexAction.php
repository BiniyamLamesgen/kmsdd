<?php

namespace App\Actions\Project;

use Illuminate\Http\Request;
use App\Models\Project;
use App\HasDynamicDataTable;

class GetProjectsIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        $query = Project::with(['employee']);
        return $query->orderBy('project_name', 'asc')->get();
    }

    public function paginated(Request $request)
    {
        $query = Project::with(['employee']);
        $model = new Project();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Project();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['project_name', 'description', 'role', 'outcome']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['project_name'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return Project::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return Project::orderBy('project_name', 'asc')->get();
    }

    protected function validateColumns(array $columns, $model)
    {
        $fillable = $model->getFillable();
        return array_values(array_intersect($columns, $fillable));
    }

    public function getIndexData(Request $request): array
    {
        try {
            $projects = $this->paginated($request);
            return [
                'success' => true,
                'projects' => $projects,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'projects' => collect(),
            ];
        }
    }
}
