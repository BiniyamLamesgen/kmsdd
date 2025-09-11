<?php

namespace App\Actions\KnowledgeSharing;

use App\Models\KnowledgeSharing;

class BulkDestroyKnowledgeSharingAction
{
    public function execute(array $ids)
    {
        $count = KnowledgeSharing::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Knowledge sharing items deleted successfully.' : 'No knowledge sharing items deleted.',
        ];
    }
}
