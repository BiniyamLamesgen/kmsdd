<?php

namespace App\Actions\Education;

use App\Models\Education;

class StoreEducationAction
{
    public function execute(array $data): array
    {
        try {
            $education = Education::create($data);
            return [
                'success' => true,
                'message' => 'Education created successfully.',
                'education' => $education,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
