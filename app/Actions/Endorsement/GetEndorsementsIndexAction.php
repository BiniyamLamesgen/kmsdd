<?php

namespace App\Actions\Endorsement;

use Illuminate\Http\Request;
use App\Models\Endorsement;
use App\HasDynamicDataTable;

class GetEndorsementsIndexAction
{
    use HasDynamicDataTable;

    public function all()
    {
        return Endorsement::with('employee','skill','endorsedBy')->orderBy('created_at', 'desc')->get();
    }

    public function paginated(Request $request)
    {
        $query = Endorsement::with('employee','skill','endorsedBy');
        $model = new Endorsement();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null)
    {
        $model = new Endorsement();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['title', 'description']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['title'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return Endorsement::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active()
    {
        return Endorsement::orderBy('created_at', 'asc')->get();
    }

    public function getIndexData(Request $request): array
    {
        try {
            $endorsements = $this->paginated($request);
            return [
                'success' => true,
                'endorsements' => $endorsements,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'endorsements' => collect(),
            ];
        }
    }
}
