<?php

namespace App\Actions\Project;

use App\Models\Project;

class EditProjectAction
{
    public function execute($id): array
    {
        try {
            $project = Project::with('employee')->findOrFail($id);
            return [
                'success' => true,
                'project' => $project,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
