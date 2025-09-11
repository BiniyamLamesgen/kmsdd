<?php

namespace App\Actions\Certification;

use App\Models\Certification;
use Illuminate\Support\Facades\Storage;

class GetCertificationsIndexAction
{
    public function getIndexData($request)
    {
        // Make sure to eager load employee relationship
        $certifications = Certification::with('employee');

        if ($search = $request->input('search')) {
            $certifications->where(function ($q) use ($search) {
                $q->where('certification_name', 'like', "%{$search}%")
                  ->orWhere('issuing_organization', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $page = (int) $request->input('page', 1);

        if ($sortField = $request->input('sortField')) {
            $sortOrder = $request->input('sortOrder', 'asc') === 'desc' ? 'desc' : 'asc';
            $certifications->orderBy($sortField, $sortOrder);
        } else {
            $certifications->orderBy('certification_name', 'asc');
        }

        $result = $certifications->paginate($perPage, ['*'], 'page', $page);

        return [
            'success' => true,
            'certifications' => $result,
        ];
    }
}
