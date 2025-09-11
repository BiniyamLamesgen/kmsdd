<?php

namespace App\Actions\Certification;

use App\Models\Certification;

class DestroyCertificationAction
{
    public function execute(string $id)
    {
        $certification = Certification::find($id);
        if (!$certification) {
            return ['success' => false, 'message' => 'Certification not found.'];
        }
        $certification->delete();
        return ['success' => true, 'message' => 'Certification deleted successfully.'];
    }
}
