<?php

namespace App\Actions\Experience;

use App\Models\Experience;

class GetExperiencesIndexAction
{
    public function getIndexData($request)
    {
        // eager-load employee so the resource includes employee data
        $experiences = Experience::with('employee');

        if ($search = $request->input('search')) {
            $experiences->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // allow client to specify per-page, default to 15
        $perPage = (int) $request->input('per_page', 15);
        $page = (int) $request->input('page', 1);

        // optional: default sort
        if ($sortField = $request->input('sortField')) {
            $sortOrder = $request->input('sortOrder', 'asc') === 'desc' ? 'desc' : 'asc';
            $experiences->orderBy($sortField, $sortOrder);
        } else {
            $experiences->orderBy('company_name', 'asc');
        }

        // return a paginator (LengthAwarePaginator) so ResourceCollection meta methods exist
        $result = $experiences->paginate($perPage, ['*'], 'page', $page);

        return [
            'success' => true,
            'experiences' => $result,
        ];
    }
}
