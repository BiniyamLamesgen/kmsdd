<?php

namespace App\Actions\KnowledgeSharing;

use App\Models\KnowledgeSharing;

class DestroyKnowledgeSharingAction
{
    public function execute(string $id)
    {
        $knowledgeSharing = KnowledgeSharing::find($id);
        if (!$knowledgeSharing) {
            return ['success' => false, 'message' => 'Knowledge sharing not found.'];
        }
        $knowledgeSharing->delete();
        return ['success' => true, 'message' => 'Knowledge sharing deleted successfully.'];
    }
}
