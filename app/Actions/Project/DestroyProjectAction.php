<?php

namespace App\Actions\Project;

use App\Models\Project;

class DestroyProjectAction
{
    public function execute(string $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return ['success' => false, 'message' => 'Project not found.'];
        }
        $project->delete();
        return ['success' => true, 'message' => 'Project deleted successfully.'];
    }
}
