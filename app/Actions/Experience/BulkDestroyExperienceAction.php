<?php

namespace App\Actions\Experience;

use App\Models\Experience;

class BulkDestroyExperienceAction
{
    public function execute(array $ids)
    {
        $count = Experience::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Experiences deleted successfully.' : 'No experiences deleted.',
        ];
    }
}
