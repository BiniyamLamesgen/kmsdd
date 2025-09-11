<?php

namespace App\Actions\Skill;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\HasDynamicDataTable;

class GetSkillsIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        $query = Skill::query();
        return $query->orderBy('skill_name', 'asc')->get();
    }

    public function paginated(Request $request)
    {
        $query = Skill::query();
        $model = new Skill();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Skill();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['skill_name']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['skill_name'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return Skill::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return Skill::orderBy('created_at', 'asc')->get();
    }

    public function getIndexData(Request $request): array
    {
        try {
            $skills = $this->paginated($request);
            return [
                'success' => true,
                'skills' => $skills,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'skills' => collect(),
            ];
        }
    }
}
