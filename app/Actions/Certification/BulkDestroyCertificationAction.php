<?php

namespace App\Actions\Certification;

use App\Models\Certification;

class BulkDestroyCertificationAction
{
    public function execute(array $ids)
    {
        $count = Certification::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Certifications deleted successfully.' : 'No certifications deleted.',
        ];
    }
}
