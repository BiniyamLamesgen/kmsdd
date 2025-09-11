<?php

namespace App\Actions\Certification;

use App\Models\Certification;

class ShowCertificationAction
{
    public function execute(string $id)
    {
        $certification = Certification::with('employee')->find($id);
        if (!$certification) {
            return ['success' => false, 'message' => 'Certification not found.'];
        }
        return ['success' => true, 'certification' => $certification];
    }
}
