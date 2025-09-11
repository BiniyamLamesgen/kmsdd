<?php

namespace App\Actions\Education;

use App\Models\Education;

class CreateEducationAction
{
    public function execute()
    {
        try {
            $education = Education::create();
            return [
                'success' => true,
                'education' => $education,
                'message' => 'Education created successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create education: ' . $e->getMessage(),
            ];
        }
    }
}
