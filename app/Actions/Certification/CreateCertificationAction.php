<?php

namespace App\Actions\Certification;

use App\Models\Certification;

class CreateCertificationAction
{
    public function execute(array $data): array
    {
        try {
            $model = Certification::with('employee')->create($data);
            return [
                'success' => true,
                'certification' => $model,
                'message' => 'Certification created successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating certification: '.$e->getMessage(),
            ];
        }
    }
}
