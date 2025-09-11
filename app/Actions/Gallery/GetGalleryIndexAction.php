<?php

namespace App\Actions\Gallery;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\HasDynamicDataTable;

class GetGalleryIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        return Gallery::orderBy('date', 'desc')->get();
    }

    public function paginated(Request $request)
    {
        $query = Gallery::query();
        $model = new Gallery();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Gallery();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['title', 'description', 'category', 'employee']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['title'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return Gallery::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    protected function validateColumns(array $columns, $model)
    {
        $fillable = $model->getFillable();
        return array_values(array_intersect($columns, $fillable));
    }

    public function getIndexData(Request $request): array
    {
        try {
            $gallery = $this->paginated($request);
            return [
                'success' => true,
                'gallery' => $gallery,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'gallery' => collect(),
            ];
        }
    }
}
