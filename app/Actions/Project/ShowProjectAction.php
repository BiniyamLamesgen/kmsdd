<?php

namespace App\Actions\Project;

use App\Models\Project;

class ShowProjectAction
{
    public function execute(string $id)
    {
        $project = Project::with('employee')->find($id);
        if (!$project) {
            return ['success' => false, 'message' => 'Project not found.'];
        }
        return ['success' => true, 'project' => $project];
    }
}
