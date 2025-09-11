<?php

namespace App\Actions\KnowledgeSharing;

use App\Models\KnowledgeSharing;

class ShowKnowledgeSharingAction
{
    public function execute(string $id)
    {
        $knowledgeSharing = KnowledgeSharing::with('employee')->find($id);
        if (!$knowledgeSharing) {
            return ['success' => false, 'message' => 'Knowledge sharing not found.'];
        }
        return ['success' => true, 'knowledge_sharing' => $knowledgeSharing];
    }
}
