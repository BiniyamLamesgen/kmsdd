<?php

namespace App\Actions\EmployeeSkill;

use Illuminate\Http\Request;
use App\Models\EmployeeSkill;
use App\HasDynamicDataTable;

class GetEmployeeSkillsIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        // Use query builder for ordering, not collection
        return EmployeeSkill::with('employee', 'skill')
            ->orderBy('proficiency_level', 'desc')
            ->get();
    }

    public function paginated(Request $request)
    {
        // Use query builder for ordering, not collection
        $query = EmployeeSkill::with('employee', 'skill');
        $model = new EmployeeSkill();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new EmployeeSkill();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['proficiency_level', 'endorsements_count']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['proficiency_level'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return EmployeeSkill::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return EmployeeSkill::orderBy('created_at', 'asc')->get();
    }

    public function getIndexData(Request $request): array
    {
        try {
            $employeeSkills = $this->paginated($request);
            return [
                'success' => true,
                'employeeSkills' => $employeeSkills,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'employeeSkills' => collect(),
            ];
        }
    }
}
