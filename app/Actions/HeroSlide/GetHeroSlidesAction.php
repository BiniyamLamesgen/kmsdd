<?php

namespace App\Actions\HeroSlide;

use Illuminate\Http\Request;
use App\Models\HeroSlide;
use App\HasDynamicDataTable;

class GetHeroSlidesAction {
    use HasDynamicDataTable;

    public function all() {
        $query = HeroSlide::query();
        return $query->orderBy('order', 'asc')->get();
    }

    public function paginated(Request $request) {
        $query = HeroSlide::query();
        $model = new HeroSlide();
        return $this->buildPaginatedQuery($query, $request, $model);
    }

    public function search(string $searchTerm, int $limit = 10, ?array $searchableColumns = null) {
        $model = new HeroSlide();
        if ($searchableColumns === null) {
            $fillableColumns = $model->getFillable();
            $searchableColumns = array_filter($fillableColumns, function ($column) {
                return in_array($column, ['name', 'title', 'slug', 'description', 'content', 'email']);
            });
            $searchableColumns = !empty($searchableColumns) ? $searchableColumns : ['title'];
        }
        $validatedColumns = $this->validateColumns($searchableColumns, $model);
        return HeroSlide::where(function ($query) use ($searchTerm, $validatedColumns) {
            foreach ($validatedColumns as $column) {
                $query->orWhere($column, 'like', "%{$searchTerm}%");
            }
        })->limit($limit)->get();
    }

    public function active() {
        return HeroSlide::orderBy('order', 'asc')->get();
    }

    public function getIndexData(Request $request): array {
        try {
            $heroSlides = $this->paginated($request);
            return [
                'success' => true,
                'heroSlides' => $heroSlides,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'heroSlides' => collect(),
            ];
        }
    }
}
