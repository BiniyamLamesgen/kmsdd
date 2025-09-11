<?php

namespace App\Actions\KnowledgeSharing;

use App\Models\KnowledgeSharing;

class EditKnowledgeSharingAction
{
    public function execute($id): array
    {
        try {
            $knowledgeSharing = KnowledgeSharing::with('employee')->findOrFail($id);
            return [
                'success' => true,
                'knowledge_sharing' => $knowledgeSharing,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
