<?php

namespace App\Actions\KnowledgeSharing;

use App\Models\KnowledgeSharing;

class UpdateKnowledgeSharingAction
{
    public function execute(string $id, array $data)
    {
        $knowledgeSharing = KnowledgeSharing::find($id);
        if (!$knowledgeSharing) {
            return ['success' => false, 'message' => 'Knowledge sharing not found.'];
        }
        $knowledgeSharing->update($data);
        return ['success' => true, 'knowledge_sharing' => $knowledgeSharing, 'message' => 'Knowledge sharing updated successfully.'];
    }
}
