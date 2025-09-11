<?php

namespace App\Actions\KnowledgeSharing;

use App\Models\KnowledgeSharing;

class StoreKnowledgeSharingAction
{
    public function execute(array $data): array
    {
        try {
            $knowledgeSharing = KnowledgeSharing::create($data);
            return [
                'success' => true,
                'message' => 'Knowledge sharing created successfully.',
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
