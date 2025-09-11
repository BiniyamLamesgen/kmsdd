<?php

namespace App\Actions\Endorsement;

use App\Models\Endorsement;

class BulkDestroyEndorsementAction
{
    public function execute(array $ids)
    {
        $count = Endorsement::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Endorsements deleted successfully.' : 'No endorsements deleted.',
        ];
    }
}
