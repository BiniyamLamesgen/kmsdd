<?php

namespace App\Actions\Experience;

use App\Models\Experience;

class CreateExperienceAction
{
    public function execute(array $data): array
    {
        try {
            $model = Experience::with('employee')->create($data);
            return [
                'success' => true,
                'experience' => $model,
                'message' => 'Experience created successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating experience: '.$e->getMessage(),
            ];
        }
    }
}
