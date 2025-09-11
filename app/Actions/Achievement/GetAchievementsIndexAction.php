<?php

namespace App\Actions\Achievement;

use Illuminate\Http\Request;
use App\Models\Achievement;
use App\HasDynamicDataTable;

class GetAchievementsIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        $query = Achievement::with('employee');
        return $query->orderBy('title', 'asc')->get();
    }

    public function paginated(Request $request)
    {
        $query = Achievement::with('employee');
        $model = new Achievement();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Achievement();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['title', 'description']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['title'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return Achievement::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return Achievement::orderBy('title', 'asc')->get();
    }

    protected function validateColumns(array $columns, $model)
    {
        $fillable = $model->getFillable();
        return array_values(array_intersect($columns, $fillable));
    }

    public function getIndexData(Request $request): array
    {
        try {
            $achievements = $this->paginated($request);
            return [
                'success' => true,
                'achievements' => $achievements,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'achievements' => collect(),
            ];
        }
    }
}
