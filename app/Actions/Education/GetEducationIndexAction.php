<?php

namespace App\Actions\Education;

use Illuminate\Http\Request;
use App\Models\Education;

class GetEducationIndexAction
{
    public function getIndexData(Request $request): array
    {
        try {
            $query = Education::with('employee');
            // Add search if needed
            if ($search = $request->input('search')) {
                $query->where('degree', 'like', "%{$search}%");
            }
            $education = $query->paginate(15);
            return [
                'success' => true,
                'education' => $education,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'education' => collect(),
            ];
        }
    }
}
