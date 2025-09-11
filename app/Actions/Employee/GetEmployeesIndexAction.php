<?php

namespace App\Actions\Employee;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\HasDynamicDataTable;
use Illuminate\Support\Facades\DB;

class GetEmployeesIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        $query = Employee::with(['department', 'experiences', 'projects', 'skills', 'achievements', 'certifications', 'education', 'knowledgeSharing', 'endorsementsReceived.skill', 'endorsementsReceived.endorsedBy']);
        return $query->orderBy('first_name', 'asc')->get();
    }

    public function getAllDepartments()
    {
        return Department::orderBy('name', 'asc')->pluck('name')->toArray();
    }

    public function paginated(Request $request)
    {
        $query = Employee::with(['department', 'experiences', 'projects', 'skills', 'achievements', 'certifications', 'education', 'knowledgeSharing', 'endorsementsReceived.skill', 'endorsementsReceived.endorsedBy']);

        // Apply search filter
        if ($request->has('search') && !empty(trim($request->search))) {
            $searchTerm = trim($request->search);

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw("LOWER(first_name) LIKE LOWER(?)", ["%{$searchTerm}%"])
                    ->orWhereRaw("LOWER(last_name) LIKE LOWER(?)", ["%{$searchTerm}%"])
                    ->orWhereRaw("LOWER(email) LIKE LOWER(?)", ["%{$searchTerm}%"])
                    ->orWhereRaw("LOWER(position) LIKE LOWER(?)", ["%{$searchTerm}%"])
                    ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE LOWER(?)", ["%{$searchTerm}%"]);
            });
        }

        // Apply department filter - now filter by department name through relationship
        if ($request->has('department') && !empty(trim($request->department))) {
            $departmentName = trim($request->department);
            $query->whereHas('department', function ($q) use ($departmentName) {
                $q->where('name', $departmentName);
            });
        }

        // Apply skill filter (case-insensitive)
        if ($request->has('skill') && !empty(trim($request->skill))) {
            $skillTerm = trim($request->skill);
            $query->whereHas('skills', function ($q) use ($skillTerm) {
                $q->whereRaw('LOWER(skill_name) LIKE LOWER(?)', ["%{$skillTerm}%"]);
            });
        }

        // Manual pagination instead of using the trait
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);

        $result = $query->paginate($perPage, ['*'], 'page', $page);

        // Debug: Log the pagination metadata
        logger()->info('Pagination Debug', [
            'total' => $result->total(),
            'count' => $result->count(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'from' => $result->firstItem(),
            'to' => $result->lastItem(),
        ]);

        return $result;
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Employee();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['first_name', 'last_name', 'email', 'position', 'department']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['first_name', 'last_name'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return Employee::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return Employee::orderBy('first_name', 'asc')->get();
    }

    protected function validateColumns(array $columns, $model)
    {
        $fillable = $model->getFillable();
        return array_values(array_intersect($columns, $fillable));
    }

    public function getIndexData(Request $request): array
    {
        try {
            // Debug: Check if employees exist with similar names
            if ($request->has('search') && !empty(trim($request->search))) {
                $searchTerm = trim($request->search);
                $allEmployees = Employee::with('department')->select('first_name', 'last_name', 'email', 'department_id')->get();
                logger()->info('All Employees for Debug', [
                    'search_term' => $searchTerm,
                    'employees' => $allEmployees->map(function ($emp) {
                        return $emp->first_name . ' ' . $emp->last_name . ' (' . $emp->email . ')';
                    })->toArray()
                ]);
            }

            $employees = $this->paginated($request);
            $departments = $this->getAllDepartments();

            return [
                'success' => true,
                'employees' => $employees,
                'departments' => $departments,
            ];
        } catch (\Exception $e) {
            logger()->error('GetEmployeesIndexAction Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'employees' => collect(),
                'departments' => [],
            ];
        }
    }
}
