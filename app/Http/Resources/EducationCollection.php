<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\EducationResource;

class EducationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        // Serialize items via EducationResource to ensure consistent shape
        $items = $this->collection->values()->map(function ($item) use ($request) {
            return (new EducationResource($item))->toArray($request);
        })->all();

        $isPaginated = $this->resource instanceof LengthAwarePaginator;

        if ($isPaginated) {
            return [
                'data' => $items,
                'meta' => [
                    'per_page'      => (int) $this->resource->perPage(),
                    'current_page'  => (int) $this->resource->currentPage(),
                    'total_pages'   => (int) $this->resource->lastPage(),
                    'total'         => (int) $this->resource->total(),
                ],
                'links' => [
                    'first' => $this->resource->url(1),
                    'last'  => $this->resource->url($this->resource->lastPage()),
                    'prev'  => $this->resource->previousPageUrl(),
                    'next'  => $this->resource->nextPageUrl(),
                ],
            ];
        }

        return [
            'data' => $items,
            'meta' => null,
            'links' => null,
        ];
    }
}
