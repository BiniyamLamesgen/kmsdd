<?php

namespace App\Actions\KnowledgeSharing;

use Illuminate\Http\Request;
use App\Models\KnowledgeSharing;
use App\HasDynamicDataTable;

class GetKnowledgeSharingIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        $query = KnowledgeSharing::with(['employee']);
        return $query->orderBy('date_shared', 'desc')->get();
    }

    public function paginated(Request $request)
    {
        $query = KnowledgeSharing::with(['employee']);
        $model = new KnowledgeSharing();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new KnowledgeSharing();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['document_title', 'document_type', 'link']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['document_title'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return KnowledgeSharing::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return KnowledgeSharing::orderBy('date_shared', 'desc')->get();
    }

    protected function validateColumns(array $columns, $model)
    {
        $fillable = $model->getFillable();
        return array_values(array_intersect($columns, $fillable));
    }

    public function getIndexData(Request $request): array
    {
        try {
            $knowledge_sharing = $this->paginated($request);
            return [
                'success' => true,
                'knowledge_sharing' => $knowledge_sharing,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'knowledge_sharing' => collect(),
            ];
        }
    }
}
